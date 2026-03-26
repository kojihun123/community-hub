<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ModerationAction extends Model
{
    protected $fillable = [
        'moderator_id',
        'report_id',
        'actionable_type',
        'actionable_id',
        'action',
        'reason',
    ];

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderator_id');
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function actionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function userSanctions(): HasMany
    {
        return $this->hasMany(UserSanction::class);
    }
}
