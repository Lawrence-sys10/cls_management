<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;

class Land extends Model
{
    use HasFactory;

    protected $fillable = [
        'plot_number',
        'area_acres',
        'area_hectares',
        'location',
        'boundary_description',
        'latitude',
        'longitude',
        'polygon_boundaries',
        'ownership_status',
        'chief_id',
        'price',
        'land_use',
        'soil_type',
        'topography',
        'access_roads',
        'utilities',
        'registration_date',
        'is_verified',
    ];

    protected $casts = [
        'polygon_boundaries' => 'array',
        'price' => 'decimal:2',
        'area_acres' => 'decimal:2',
        'area_hectares' => 'decimal:2',
        'registration_date' => 'date',
        'is_verified' => 'boolean',
        'utilities' => 'array',
    ];

    public function chief(): BelongsTo
    {
        return $this->belongsTo(Chief::class);
    }

    public function allocation(): HasOne
    {
        return $this->hasOne(Allocation::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    // Add ownership status scopes
    public function scopeVacant(Builder $query): Builder
    {
        return $query->where('ownership_status', 'vacant');
    }

    public function scopeAllocated(Builder $query): Builder
    {
        return $query->where('ownership_status', 'allocated');
    }

    public function scopeUnderDispute(Builder $query): Builder
    {
        return $query->where('ownership_status', 'under_dispute');
    }

    public function scopeReserved(Builder $query): Builder
    {
        return $query->where('ownership_status', 'reserved');
    }

    // Add status scopes (if needed for the 'status' column)
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', 'available');
    }

    public function scopeAllocatedStatus(Builder $query): Builder
    {
        return $query->where('status', 'allocated');
    }

    public function scopeReservedStatus(Builder $query): Builder
    {
        return $query->where('status', 'reserved');
    }
    public function allocations(): HasMany
{
    return $this->hasMany(Allocation::class);
}
}