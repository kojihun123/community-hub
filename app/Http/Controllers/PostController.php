<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Attachment;
use App\Models\Board;
use App\Models\Post;
use DOMDocument;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function show(Board $board, Post $post)
    {
        abort_unless(
            $post->board_id === $board->id && $board->is_active && $post->status === 'published',
            404
        );

        $post->load(['user', 'board']);

        return view('posts.show', compact('post'));
    }

    public function create(Board $board)
    {
        abort_unless($board->is_active, 404); 

        return view('posts.create', compact('board'));
    }

    public function store(Board $board, StorePostRequest $request)
    {
        abort_unless($board->is_active, 404);

        $data = $request->validated();

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($data['content']);

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

        $post = DB::transaction(function () use ($board, $data, $paths) {
            $post = Post::create([
                'board_id' => $board->id,
                'user_id' => auth()->id(),
                'title' => $data['title'],
                'content' => $data['content'],
                'author_name_snapshot' => auth()->user()->name,
                'status' => 'published',
            ]);

            Attachment::where('user_id', auth()->id())
                ->where('is_temporary', true)
                ->whereIn('path', $paths)
                ->update([
                    'post_id' => $post->id,
                    'is_temporary' => false,
                ]);

            return $post;
        });

        return redirect()
            ->route('posts.show', [$board, $post])
            ->with('success', '게시글이 등록되었습니다.');
    }

}
