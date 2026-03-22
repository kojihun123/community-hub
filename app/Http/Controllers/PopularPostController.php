<?php

namespace App\Http\Controllers;

use App\Models\PopularPost;
use Illuminate\Http\Request;

class PopularPostController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->input('q'));
        $field = $request->input('field', 'title');

        $allowedFields = ['id', 'title', 'author'];

        if (! in_array($field, $allowedFields, true)) {
            $field = 'title';
        }

        $popularItems = PopularPost::available()
            ->with([
                'post.board',
                'post.thumbnailAttachment',
            ])
            ->when($keyword !== '', function ($query) use ($field, $keyword) {
                $query->whereHas('post', function ($query) use ($field, $keyword) {
                    if ($field === 'id' && ctype_digit($keyword)) {
                        $query->whereKey((int) $keyword);

                        return;
                    }

                    if ($field === 'author') {
                        $query->where('author_name_snapshot', 'like', "%{$keyword}%");

                        return;
                    }

                    $query->where('title', 'like', "%{$keyword}%");
                });
            })
            ->latest('selected_at')
            ->paginate(15)
            ->withQueryString();

        return view('popular.index', compact('popularItems', 'field', 'keyword'));
    }
}
