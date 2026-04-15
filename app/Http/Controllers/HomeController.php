<?php

namespace App\Http\Controllers;

use App\Models\PopularPost;
use App\Models\Post;
use App\Services\OnlinePresenceService;
use App\Services\RecentBoardService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function index(
        Request $request,
        RecentBoardService $recentBoardService,
        OnlinePresenceService $onlinePresenceService,
    )
    {
        $popularItems = Cache::remember('home:popular_posts', now()->addMinute(), function () {
            return PopularPost::available()
                ->with([
                    'post.board:id,name,slug',
                    'post.thumbnailAttachment' => function ($query) {
                        $query->select('attachments.id', 'attachments.post_id', 'attachments.path');
                    },
                ])
                ->latest('selected_at')
                ->take(10)
                ->get()
                ->map(function (PopularPost $popularPost) {
                    $post = $popularPost->post;

                    return [
                        'url' => route('posts.show', [$post->board, $post]),
                        'image_url' => $post->thumbnailAttachment
                            ? Storage::url($post->thumbnailAttachment->path)
                            : null,
                        'title' => $post->title,
                        'badge' => '댓글 ' . number_format($post->comment_count),
                        'meta' => $post->board->name . ' · ' . $popularPost->selected_at->diffForHumans(),
                    ];
                })
                ->all();
        });

        $recentBoards = $recentBoardService->all();

        $noticePosts = Post::publishedOnActiveBoards()->where('is_notice', true)
            ->whereHas('board', function  ($query) {
                $query->where('slug', 'notice');
            })
            ->with('board:id,name,slug')
            ->latest()
            ->take(5)
            ->get();

        $onlineCounts = $onlinePresenceService->touch(
            $request->user(),
            $request->session()->getId(),
        );

        return view('home', compact('popularItems', 'recentBoards', 'noticePosts', 'onlineCounts'));
    }

    public function heartbeat(Request $request, OnlinePresenceService $onlinePresenceService): JsonResponse
    {
        $onlineCounts = $onlinePresenceService->touch(
            $request->user(),
            $request->session()->getId(),
        );

        return response()->json($onlineCounts);
    }
}
