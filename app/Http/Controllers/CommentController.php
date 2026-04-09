<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Board;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function store(Board $board, Post $post, CommentRequest $request)
    {
        abort_if(! $board->isEnabled() || ! $post->isPublished(), 404);

        $this->authorize('create', [Comment::class, $post]);

        $data = $request->validated();
        $contentField = $request->commentField();
        $parentId = $request->input('parent_id');
        $parent = null;

        if ($parentId) {
            $parent = Comment::findOrFail($parentId);
            abort_if($parent->post_id !== $post->id, 404);
            abort_if(! $parent->isVisible(), 404);
            abort_if($parent->parent_id !== null, 404);
        }

        DB::transaction(function () use ($post, $data, $parent, $contentField) {

            $lockedPost = Post::whereKey($post->id)
                ->lockForUpdate()
                ->firstOrFail();

            Comment::create([
                'post_id' => $post->id,
                'user_id' => auth()->id(),
                'parent_id' => $parent?->id,
                'content' => $data[$contentField],
                'author_name_snapshot' => auth()->user()->name,
            ]);

            $lockedPost->update([
                'comment_count' => $lockedPost->visibleComments()->count(),
            ]);

            if (! $parent && $post->user_id !== auth()->id()) {
                Notification::create([
                    'user_id' => $post->user_id,
                    'type' => 'comment',
                    'title' => '댓글이 달렸습니다.',
                    'message' => $data[$contentField],
                    'link' => route('posts.show', [$post->board, $post]),
                ]);
            }

            if ($parent && $parent->user_id !== auth()->id()) {
                Notification::create([
                    'user_id' => $parent->user_id,
                    'type' => 'reply',
                    'title' => '답글이 달렸습니다.',
                    'message' => $data[$contentField],
                    'link' => route('posts.show', [$post->board, $post]),
                ]);
            }

        });

        return redirect()
            ->route('posts.show', [$board, $post])
            ->with('success', '댓글이 등록되었습니다.');
    }

    public function update(Board $board, Post $post, Comment $comment, CommentRequest $request)
    {
        abort_if(
            ! $board->isEnabled() || ! $post->isPublished() || ! $comment->isVisible(),
            404
        );
        
        $this->authorize('update', $comment);

        $data = $request->validated();
        $contentField = $request->commentField();

        $comment->update([
            'content' => $data[$contentField],
        ]);

        return redirect()
            ->route('posts.show', [$board, $post])
            ->with('success', '댓글이 수정되었습니다.');            
    }

    public function destroy(Board $board, Post $post, Comment $comment)
    {
        abort_if(
            ! $board->isEnabled() || ! $post->isPublished() || ! $comment->isVisible(),
            404
        );

        $this->authorize('delete', $comment);

        DB::transaction(function () use ($post, $comment) {

            $lockedPost = Post::whereKey($post->id)
                ->lockForUpdate()
                ->firstOrFail();

            $comment->update([
                'status' => 'deleted',
            ]);

            $lockedPost->update([
                'comment_count' => $lockedPost->visibleComments()->count(),
            ]);
        });

        return redirect()
            ->route('posts.show', [$board, $post])
            ->with('success', '댓글이 삭제되었습니다.');
    }
}
