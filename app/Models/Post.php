<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'board_id',
        'user_id',
        'title',
        'content',
        'author_name_snapshot',
        'status',
        'is_notice',
        'is_pinned',
        'view_count',
        'like_count',
        'comment_count',
    ];

    protected $casts = [
        'is_notice' => 'boolean',
        'is_pinned' => 'boolean',
        'view_count' => 'integer',
        'like_count' => 'integer',
        'comment_count' => 'integer',
    ];

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public function comments() : HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
