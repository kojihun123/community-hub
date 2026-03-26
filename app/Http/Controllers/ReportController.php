<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Models\Board;
use App\Models\Post;
use App\Models\Report;

class ReportController extends Controller
{
    public function store(Board $board, Post $post, ReportRequest $request)
    {
        abort_if(! $board->isEnabled() || ! $post->isPublished(), 404);

        $this->authorize('create', [Report::class, $post]);

        $post->reports()->create([
            'reporter_id' => auth()->id(),
            'reason' => $request->reason === 'other'
                ? $request->custom_reason
                : $request->reason,
            'status' => 'pending',
        ]);
    
        return back()->with('success', '신고가 접수되었습니다.');
    
    }
}
