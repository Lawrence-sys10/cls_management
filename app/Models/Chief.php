<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chief extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'jurisdiction',
        'phone',
        'email',
        'area_boundaries',
        'user_id',
        'is_active',
    ];

    protected $casts = [
        'area_boundaries' => 'array',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lands(): HasMany
    {
        return $this->hasMany(Land::class);
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(Allocation::class);
    }
}