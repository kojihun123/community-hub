<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
        'type',
        'original_name',
        'path',
        'mime_type',
        'size',
        'is_temporary',
        'sort_order',
    ];

    protected $casts = [
        'is_temporary' => 'boolean',
        'size' => 'integer',
        'sort_order' => 'integer',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeTemporary(Builder $query): Builder
    {
        return $query->where('is_temporary', true);
    }

    public function scopeAttached(Builder $query): Builder
    {
        return $query->whereNotNull('post_id');
    }

    public function scopeUnusedTemporary(Builder $query): Builder
    {
        return $query->temporary()->whereNull('post_id');
    }
}
