<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Comment extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'content',
        'author_name_snapshot',
        'status',
    ];

    protected $casts = [

    ];

    public function post() : BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent() : BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function children() : HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function visibleChildren(): HasMany
    {
        return $this->children()->visible()->oldest();
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function moderationHistory(): MorphMany
    {
        return $this->morphMany(ModerationAction::class, 'actionable');
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('status', 'visible');
    }

    public function scopeRoot(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function isVisible(): bool
    {
        return $this->status === 'visible';
    }

    public function isReply(): bool
    {
        return $this->parent_id !== null;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'hidden' => '숨김',
            'deleted' => '삭제됨',
            default => '게시됨',
        };
    }

    public function statusBadgeVariant(): string
    {
        return match ($this->status) {
            'hidden' => 'outline',
            'deleted' => 'danger',
            default => 'success',
        };
    }
}
