<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {

        $filter = $request->input('filter', 'all');

        $notifications = auth()->user()
            ->notifications()
            ->when($filter === 'unread', function ($query){
                $query->unread();
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('notifications.index', compact('notifications', 'filter'));
    }

    public function markAllAsRead()
    {
        auth()->user()->notifications()->unread()->update(['read_at' => now()]);

        return redirect()->back()->with('message', '모두 읽음 처리 되었습니다.');
    }
}
