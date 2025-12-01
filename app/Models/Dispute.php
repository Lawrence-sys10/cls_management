<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dispute extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'land_id',
        'client_id',
        'chief_id',
        'status',
        'resolution',
        'resolved_at',
        'resolved_by',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    /**
     * Get the land that the dispute is about.
     */
    public function land(): BelongsTo
    {
        return $this->belongsTo(Land::class);
    }

    /**
     * Get the client who raised the dispute.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the chief who owns the dispute.
     */
    public function chief(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chief_id');
    }

    /**
     * Get the user who resolved the dispute.
     */
    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    /**
     * Scope a query to only include active disputes.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include resolved disputes.
     */
    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    /**
     * Scope a query to only include pending disputes.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Check if the dispute is resolved.
     */
    public function isResolved(): bool
    {
        return $this->status === 'resolved';
    }

    /**
     * Check if the dispute is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Mark the dispute as resolved.
     */
    public function markAsResolved(string $resolution, int $resolvedById): void
    {
        $this->update([
            'status' => 'resolved',
            'resolution' => $resolution,
            'resolved_by' => $resolvedById,
            'resolved_at' => now(),
        ]);
    }

    /**
     * Reopen a resolved dispute.
     */
    public function reopen(): void
    {
        $this->update([
            'status' => 'active',
            'resolution' => null,
            'resolved_by' => null,
            'resolved_at' => null,
        ]);
    }
}