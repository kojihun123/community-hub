<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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

    public function thumbnailAttachment(): HasOne
    {
        return $this->hasOne(Attachment::class)
            ->where('type', 'image')
            ->where('is_temporary', false)
            ->oldestOfMany();
    }

    public function comments() : HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function visibleComments(): HasMany
    {
        return $this->comments()->visible();
    }

    public function likes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }

    public function popularEntry(): HasOne
    {
        return $this->hasOne(PopularPost::class);
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function moderationHistory(): MorphMany
    {
        return $this->morphMany(ModerationAction::class, 'actionable');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopePublishedOnActiveBoards(Builder $query): Builder
    {
        return $query->published()
            ->whereHas('board', function (Builder $query) {
                $query->active();
            });
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
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
