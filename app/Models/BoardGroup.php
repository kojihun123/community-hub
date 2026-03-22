<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BoardGroup extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name', 
        'slug', 
        'description', 
        'is_active', 
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function boards(): HasMany
    {
        return $this->hasMany(Board::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function isEnabled(): bool
    {
        return $this->is_active;
    }
}
