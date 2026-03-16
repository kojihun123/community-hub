<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\BoardGroup;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index() {
        $board_groups = BoardGroup::where('is_active', true)
            ->with(['boards' => function ($query) {
                $query->where('is_active', true)
                    ->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        return view('boards.index', compact('board_groups'));
    }

    public function show($slug){
        $board = Board::where('is_active', true)
            ->with(['posts' => function($query){
                $query->where('status', 'published')
                    ->latest();
            }])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('boards.show', compact('board'));
    }
}
