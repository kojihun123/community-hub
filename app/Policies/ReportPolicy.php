<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class ReportPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        
    }

    public function create(User $user, Post $post): bool
    {
        if ($post->board->isNoticeBoard()) {
            return false;
        }

        if ($post->user_id === $user->id) {
            return false;
        }

        return ! $post->reports()
            ->where('reporter_id', $user->id)
            ->exists();
    }

}
