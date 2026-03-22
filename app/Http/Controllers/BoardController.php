<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\BoardGroup;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index() {
        $board_groups = BoardGroup::active()
            ->with(['boards' => function ($query) {
                $query->active()
                    ->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        return view('boards.index', compact('board_groups'));
    }

    public function show(Board $board, Request $request)
    {
        abort_if(! $board->isEnabled(), 404);

        $keyword = trim((string) $request->input('q'));
        $field = $request->input('field', 'title');

        $allowedFields = ['id', 'title', 'author'];

        if (! in_array($field, $allowedFields, true)) {
            $field = 'title';
        }

        $posts = $board->posts()
            ->published()
            ->withExists(['popularEntry'])
            ->when($keyword !== '', function ($query) use ($field, $keyword) {
                if ($field === 'id' && ctype_digit($keyword)) {
                    $query->whereKey((int) $keyword);

                    return;
                }

                if ($field === 'author') {
                    $query->where('author_name_snapshot', 'like', "%{$keyword}%");

                    return;
                }

                $query->where('title', 'like', "%{$keyword}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('boards.show', compact('board', 'posts', 'field', 'keyword'));
    }
}
