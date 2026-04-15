<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminReportActionRequest;
use App\Models\ModerationAction;
use App\Models\Notification;
use App\Models\Post;
use App\Models\Report;
use App\Models\UserSanction;
use App\Notifications\RealTimeAlert;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->input('q'));
        $field = $request->input('field', 'title');
        $statusFilter = $request->input('status', 'all');

        $allowedFields = ['id', 'post_id', 'title', 'reporter', 'reason'];
        $allowedStatuses = ['all', 'pending', 'resolved', 'rejected'];

        if (! in_array($field, $allowedFields, true)) {
            $field = 'title';
        }

        if (! in_array($statusFilter, $allowedStatuses, true)) {
            $statusFilter = 'all';
        }

        $reportQuery = Report::with([
                'reporter',
                'handler',
                'reportable' => function (MorphTo $morphTo) {
                    $morphTo->morphWith([
                        Post::class => ['board'],
                    ]);
                },
            ])
            ->when($statusFilter !== 'all', function ($query) use ($statusFilter) {
                $query->where('status', $statusFilter);
            })
            ->when($keyword !== '', function ($query) use ($field, $keyword) {
                if ($field === 'id' && ctype_digit($keyword)) {
                    $query->whereKey((int) $keyword);

                    return;
                }

                if ($field === 'post_id' && ctype_digit($keyword)) {
                    $query->whereHasMorph('reportable', [Post::class], function ($query) use ($keyword) {
                        $query->whereKey((int) $keyword);
                    });

                    return;
                }

                if ($field === 'reporter') {
                    $query->whereHas('reporter', function ($query) use ($keyword) {
                        $query->where('name', 'like', "%{$keyword}%");
                    });

                    return;
                }

                if ($field === 'reason') {
                    $query->where('reason', 'like', "%{$keyword}%");

                    return;
                }

                $query->whereHasMorph('reportable', [Post::class], function ($query) use ($keyword) {
                    $query->where('title', 'like', "%{$keyword}%");
                });
            })
            ->latest();

        $pendingCount = (clone $reportQuery)
            ->where('status', 'pending')
            ->count();

        $totalCount = (clone $reportQuery)->count();

        $reports = $reportQuery
            ->paginate(15)
            ->withQueryString();

        return view('admin.reports.index', compact(
            'reports',
            'field',
            'keyword',
            'statusFilter',
            'pendingCount',
            'totalCount',
        ));
    }

    public function update(AdminReportActionRequest $request, Report $report)
    {
        if ($report->status !== 'pending') {
            return back()->with('error', '이미 처리된 신고입니다.');
        }

        $data = $request->validated();

        DB::transaction(function () use ($report, $data) {
            $decision = $data['decision'];
            $handledNote = $decision === 'resolved'
                ? $data['resolved_reason']
                : $data['rejected_reason'];

            $report->update([
                'status' => $decision,
                'handled_by' => auth()->id(),
                'handled_note' => $handledNote,
            ]);

            $this->notifyReporter($report, $decision, $handledNote);

            if ($decision === 'rejected') {
                return;
            }

            $reportable = $report->reportable;

            if (! $reportable instanceof Post) {
                return;
            }

            $contentAction = $data['content_action'] ?? 'none';
            $sanctionType = $data['sanction_type'] ?? 'none';

            if ($contentAction !== 'none') {
                $postStatus = $contentAction === 'hide' ? 'hidden' : 'deleted';

                $reportable->update([
                    'status' => $postStatus,
                ]);

                $this->notifyPostAuthor($reportable, $contentAction, $handledNote);

                ModerationAction::create([
                    'moderator_id' => auth()->id(),
                    'report_id' => $report->id,
                    'actionable_type' => $reportable::class,
                    'actionable_id' => $reportable->id,
                    'action' => $contentAction,
                    'reason' => $handledNote,
                ]);
            }

            if ($sanctionType === 'none' || ! $reportable->user) {
                return;
            }

            [$moderationActionName, $sanctionName, $startsAt, $endsAt] = $this->resolveSanctionPreset($sanctionType);

            $userModerationAction = ModerationAction::create([
                'moderator_id' => auth()->id(),
                'report_id' => $report->id,
                'actionable_type' => $reportable->user::class,
                'actionable_id' => $reportable->user->id,
                'action' => $moderationActionName,
                'reason' => $handledNote,
            ]);

            UserSanction::create([
                'user_id' => $reportable->user->id,
                'moderator_id' => auth()->id(),
                'report_id' => $report->id,
                'moderation_action_id' => $userModerationAction->id,
                'type' => $sanctionName,
                'reason' => $handledNote,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'status' => 'active',
            ]);

            $reportable->user->update([
                'status' => $moderationActionName === 'ban' ? 'banned' : ($moderationActionName === 'suspend' ? 'suspended' : $reportable->user->status),
                'suspended_until' => $moderationActionName === 'suspend' ? $endsAt : null,
                'current_sanction_reason' => $handledNote,
            ]);

            $this->notifySanctionedUser(
                $reportable->user,
                $moderationActionName,
                $handledNote,
                $endsAt,
            );
        });

        return back()->with('success', '신고가 처리되었습니다.');
    }

    private function notifyReporter(Report $report, string $decision, string $handledNote): void
    {
        if (! $report->reporter) {
            return;
        }

        $link = $report->reportable instanceof Post
            ? route('posts.show', [$report->reportable->board, $report->reportable])
            : null;

        $notification = [
            'type' => $decision === 'resolved' ? 'report_resolved' : 'report_rejected',
            'title' => $decision === 'resolved'
                ? '신고가 처리되었습니다.'
                : '신고가 반려되었습니다.',
            'message' => $handledNote ?: (
                $decision === 'resolved'
                    ? '회원님이 접수한 신고가 운영 검토 후 처리되었습니다.'
                    : '회원님이 접수한 신고가 검토 후 반려되었습니다.'
            ),
            'link' => $link,
        ];

        $this->createAndBroadcastNotification($report->reporter, $notification);
    }

    private function notifyPostAuthor(Post $post, string $contentAction, string $handledNote): void
    {
        if (! $post->user) {
            return;
        }

        $title = $contentAction === 'hide'
            ? '회원님의 게시글이 숨김 처리되었습니다.'
            : '회원님의 게시글이 삭제 처리되었습니다.';

        $defaultMessage = $contentAction === 'hide'
            ? '운영 검토 결과 회원님의 게시글이 숨김 처리되었습니다.'
            : '운영 검토 결과 회원님의 게시글이 삭제 처리되었습니다.';

        $this->createAndBroadcastNotification($post->user, [
            'type' => 'post_moderated',
            'title' => $title,
            'message' => $handledNote ?: $defaultMessage,
            'link' => route('mypage.posts'),
        ]);
    }

    private function notifySanctionedUser($user, string $moderationActionName, string $handledNote, $endsAt): void
    {
        $title = match ($moderationActionName) {
            'warn' => '회원님 계정에 경고가 적용되었습니다.',
            'suspend' => '회원님 계정이 일시 정지되었습니다.',
            default => '회원님 계정이 영구 정지되었습니다.',
        };

        $defaultMessage = match ($moderationActionName) {
            'warn' => '운영 검토 결과 회원님 계정에 경고가 적용되었습니다.',
            'suspend' => '운영 검토 결과 회원님 계정이 '
                . ($endsAt ? $endsAt->format('Y.m.d H:i') . '까지 일시 정지되었습니다.' : '일시 정지되었습니다.'),
            default => '운영 검토 결과 회원님 계정이 영구 정지되었습니다.',
        };

        $this->createAndBroadcastNotification($user, [
            'type' => 'user_sanction_applied',
            'title' => $title,
            'message' => $handledNote ?: $defaultMessage,
            'link' => route('mypage.profile.edit'),
        ]);
    }

    private function createAndBroadcastNotification($user, array $payload): void
    {
        Notification::create([
            'user_id' => $user->id,
            'type' => $payload['type'],
            'title' => $payload['title'],
            'message' => $payload['message'] ?? '',
            'link' => $payload['link'] ?? null,
        ]);

        $user->notify(new RealTimeAlert([
            'type' => $payload['type'],
            'title' => $payload['title'],
            'message' => $payload['message'] ?? '',
            'link' => $payload['link'] ?? null,
            'sender_name' => auth()->user()->name,
        ]));
    }

    private function resolveSanctionPreset(string $sanctionType): array
    {
        $startsAt = now();

        return match ($sanctionType) {
            'warning' => ['warn', 'warning', null, null],
            'suspension_3d' => ['suspend', 'suspension', $startsAt, $startsAt->copy()->addDays(3)],
            'suspension_7d' => ['suspend', 'suspension', $startsAt, $startsAt->copy()->addDays(7)],
            'suspension_30d' => ['suspend', 'suspension', $startsAt, $startsAt->copy()->addDays(30)],
            default => ['ban', 'ban', $startsAt, null],
        };
    }
}
