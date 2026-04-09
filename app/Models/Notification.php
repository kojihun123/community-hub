<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'link',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead(Builder $query): Builder
    {
        return $query->whereNotNull('read_at');
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    public function isUnread(): bool
    {
        return ! $this->isRead();
    }

    public function markAsRead(): void
    {
        if ($this->isRead()) {
            return;
        }

        $this->forceFill([
            'read_at' => now(),
        ])->save();
    }

    public function markAsUnread(): void
    {
        if ($this->isUnread()) {
            return;
        }

        $this->forceFill([
            'read_at' => null,
        ])->save();
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'report_resolved' => '신고 처리',
            'report_rejected' => '신고 반려',
            'post_moderated' => '게시글 조치',
            'user_sanction_applied' => '계정 제재',
            'comment' => '댓글',
            'reply' => '답글',
            default => '일반 알림',
        };
    }

}
