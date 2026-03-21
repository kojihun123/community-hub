<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Post;
use App\Models\PostLike;
use Illuminate\Support\Facades\DB;

class PostLikeController extends Controller
{
    public function store(Board $board, Post $post)
    {
        abort_if(!$board->is_active || $post->status !== 'published', 404);

        $message = DB::transaction(function () use ($post) {
            $lockedPost = Post::whereKey($post->id)
                ->lockForUpdate()
                ->firstOrFail();

            $postLike = PostLike::where('post_id', $lockedPost->id)
                ->where('user_id', auth()->id())
                ->first();

            if ($postLike) {
                $postLike->delete();
                $lockedPost->update([
                    'like_count' => $lockedPost->likes()->count(),
                ]);

                return '좋아요를 취소했습니다.';
            }

            PostLike::create([
                'post_id' => $lockedPost->id,
                'user_id' => auth()->id(),
            ]);

            $lockedPost->update([
                'like_count' => $lockedPost->likes()->count(),
            ]);

            return '좋아요를 눌렀습니다.';
        });

        return redirect()
            ->route('posts.show', [$board, $post])
            ->with('success', $message);
    }
}
