<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Attachment;
use App\Models\Board;
use App\Models\Post;
use App\Services\RecentBoardService;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function show(Board $board, Post $post, RecentBoardService $recentBoardService)
    {
        abort_if(! $board->isEnabled() || ! $post->isPublished(), 404);

        $recentBoardService->push($board);

        $post->load([
            'user', 'board',
            'comments' => function ($query) {
                $query->visible()
                    ->root()
                    ->with([
                        'user',
                        'visibleChildren.user',
                    ])
                    ->oldest();
            },
        ])
        ->loadExists('popularEntry');

        if (auth()->check()) {
            $post->loadExists([
                'likes as is_liked_by_user' => function ($query) {
                    $query->where('user_id', auth()->id());
                },
                'reports as is_reported_by_user' => function ($query) {
                    $query->where('reporter_id', auth()->id());
                },
            ]);
        } else {
            $post->setAttribute('is_liked_by_user', false);
            $post->setAttribute('is_reported_by_user', false);
        }

        $viewerKey = 'viewer:' . md5(request()->ip() . '|' . request()->userAgent());
        $userAgent = strtolower((string) request()->userAgent());
        $isBot = str_contains($userAgent, 'bot')
            || str_contains($userAgent, 'crawl')
            || str_contains($userAgent, 'spider');

        $postViewKey = 'post:viewed:' . $post->id . ':' . $viewerKey;

        if (! $isBot && Cache::add($postViewKey, true, now()->addDay())) {
            $post->increment('view_count');
        }

        return view('posts.show', compact('post'));
    }

    public function create(Board $board)
    {
        abort_if(! $board->isEnabled(), 404);

        $this->authorize('create', [Post::class, $board]);

        return view('posts.create', compact('board'));
    }

    public function store(Board $board, PostRequest $request)
    {
        abort_if(! $board->isEnabled(), 404);

        $this->authorize('create', [Post::class, $board]);

        $data = $request->validated();

        $paths = $this->extractAttachmentPaths($data['content']);

        $post = DB::transaction(function () use ($board, $data, $paths) {
            $post = Post::create([
                'board_id' => $board->id,
                'user_id' => auth()->id(),
                'title' => $data['title'],
                'content' => $data['content'],
                'author_name_snapshot' => auth()->user()->name,
                'status' => 'published',
            ]);

            $this->syncAttachmentsForPost($post, $paths);

            return $post;
        });

        return redirect()
            ->route('posts.show', [$board, $post])
            ->with('success', '게시글이 등록되었습니다.');
    }

    public function edit(Board $board, Post $post)
    {
        abort_if(! $board->isEnabled() || ! $post->isPublished(), 404);

        $this->authorize('update', $post);

        $post->load(['user', 'board']);

        return view('posts.edit', compact('post'));
    }

    public function update(Board $board, Post $post, PostRequest $request)
    {
        abort_if(! $board->isEnabled() || ! $post->isPublished(), 404);

        $this->authorize('update', $post);

        $data = $request->validated();

        $paths = $this->extractAttachmentPaths($data['content']);

        $post = DB::transaction(function () use ($post, $data, $paths) {
            $post->update([
                'title' => $data['title'],
                'content' => $data['content'],
            ]);

            $this->syncAttachmentsForPost($post, $paths);

            return $post;
        });

        return redirect()
            ->route('posts.show', [$board, $post])
            ->with('success', '게시글이 수정되었습니다.');
    }

    public function destroy(Board $board, Post $post, Request $request)
    {
        abort_if(! $board->isEnabled() || ! $post->isPublished(), 404);

        $this->authorize('delete', $post);

        $post->update([
            'status' => 'deleted',
        ]);

        return redirect($request->input('redirect_to', route('boards.show', $board)))
            ->with('success', '게시글이 삭제되었습니다.');
    }

    private function extractAttachmentPaths(string $content): array
    {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($content);

        $images = $dom->getElementsByTagName('img');

        $srcs = [];

        foreach ($images as $img) {
            $srcs[] = $img->getAttribute('src');
        }

        $paths = collect($srcs)
            ->map(function ($src) {
                $path = parse_url($src, PHP_URL_PATH) ?? '';

                return str_replace('/storage/', '', $path);
            })
            ->filter()
            ->values()
            ->all();

        libxml_clear_errors();
        libxml_use_internal_errors(false);

        return $paths;
    }

    private function syncAttachmentsForPost(Post $post, array $paths): void
    {
        Attachment::where('user_id', auth()->id())
            ->where('post_id', $post->id)
            ->whereNotIn('path', $paths)
            ->update([
                'post_id' => null,
                'is_temporary' => true,
            ]);

        Attachment::temporary()
            ->where('user_id', auth()->id())
            ->whereIn('path', $paths)
            ->update([
                'post_id' => $post->id,
                'is_temporary' => false,
            ]);
    }
}
