<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'chief_id',
        'full_name',
        'phone',
        'email',
        'id_type',
        'id_number',
        'address',
        'occupation',
        'date_of_birth',
        'gender',
        'emergency_contact',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Get the chief that owns the client.
     */
    public function chief(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chief_id');
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(Allocation::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    // Accessor for display name
    public function getDisplayNameAttribute(): string
    {
        return $this->full_name . ' (' . $this->id_number . ')';
    }

    // Accessor for formatted ID type
    public function getIdTypeFormattedAttribute(): string
    {
        return match($this->id_type) {
            'ghanacard' => 'Ghana Card',
            'passport' => 'Passport',
            'drivers_license' => 'Driver\'s License',
            'voters_id' => 'Voter\'s ID',
            default => ucfirst($this->id_type),
        };
    }

    // Accessor for age
    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth ? Carbon::parse($this->date_of_birth)->age : null;
    }

    // Check if client has allocations
    public function hasAllocations(): bool
    {
        return $this->allocations()->exists();
    }

    // Scope for searching clients
    public function scopeSearch($query, $search)
    {
        return $query->where('full_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('id_number', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
    }

    // Scope for clients belonging to a specific chief
    public function scopeForChief($query, $chiefId)
    {
        return $query->where('chief_id', $chiefId);
    }
}