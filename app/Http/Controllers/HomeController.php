<?php

namespace App\Http\Controllers;

use App\Models\PopularPost;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
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

        return view('home', compact('popularItems'));
    }
}
