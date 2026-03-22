<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PopularPost extends Model
{
    protected $fillable = ['post_id', 'selected_at'];

    protected $casts = [
        'selected_at' => 'datetime',
    ];

    public function post() : BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->whereHas('post', function (Builder $query) {
            $query->published()
                ->whereHas('board', function (Builder $query) {
                    $query->active();
                });
        });
    }
}
