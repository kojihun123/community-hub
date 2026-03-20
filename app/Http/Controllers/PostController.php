<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Attachment;
use App\Models\Board;
use App\Models\Post;
use DOMDocument;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function show(Board $board, Post $post)
    {
        abort_if(!$board->is_active || $post->status !== 'published', 404);

        $post->load([
            'user', 'board', 
            'comments' => function ($query) {
                $query->where('status', 'visible')
                    ->whereNull('parent_id')
                    ->with([
                        'user',
                        'children' => function ($query) {
                            $query->where('status', 'visible')
                                ->with('user')
                                ->oldest();
                    }
                    ])
                    ->oldest();
            },
        ]);

        return view('posts.show', compact('post'));
    }

    public function create(Board $board)
    {
        abort_if(!$board->is_active, 404);

        return view('posts.create', compact('board'));
    }

    public function store(Board $board, PostRequest $request)
    {
        abort_if(!$board->is_active, 404);

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
        abort_if(!$board->is_active || $post->status !== 'published', 404);

        $this->authorize('update', $post);

        $post->load(['user', 'board']);

        return view('posts.edit', compact('post'));
    }

    public function update(Board $board, Post $post, PostRequest $request)
    {
        abort_if(!$board->is_active || $post->status !== 'published', 404);

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

    public function destroy(Board $board, Post $post)
    {
        abort_if(!$board->is_active || $post->status !== 'published', 404);

        $this->authorize('delete', $post);

        $post->update([
            'status' => 'deleted'
        ]);

        return redirect()
            ->route('boards.show', $board)
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

        Attachment::where('user_id', auth()->id())
            ->where('is_temporary', true)
            ->whereIn('path', $paths)
            ->update([
                'post_id' => $post->id,
                'is_temporary' => false,
            ]);
    }
}
