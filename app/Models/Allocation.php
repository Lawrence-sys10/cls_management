<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Allocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'land_id',
        'client_id',
        'chief_id',
        'processed_by',
        'allocation_date',
        'approval_status',
        'chief_approval_date',
        'registrar_approval_date',
        'allocation_letter_path',
        'payment_status',
        'payment_amount',
        'payment_date',
        'notes',
        'is_finalized',
    ];

    protected $casts = [
        'allocation_date' => 'datetime',
        'chief_approval_date' => 'datetime',
        'registrar_approval_date' => 'datetime',
        'payment_date' => 'datetime',
        'payment_amount' => 'decimal:2',
        'is_finalized' => 'boolean',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_FINALIZED = 'finalized';

    public function land(): BelongsTo
    {
        return $this->belongsTo(Land::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function chief(): BelongsTo
    {
        return $this->belongsTo(Chief::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'processed_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    // Add approval status scopes
    public function scopePending(Builder $query): Builder
    {
        return $query->where('approval_status', self::STATUS_PENDING);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('approval_status', self::STATUS_APPROVED);
    }

    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('approval_status', self::STATUS_REJECTED);
    }

    public function scopeFinalized(Builder $query): Builder
    {
        return $query->where('approval_status', self::STATUS_FINALIZED);
    }

    // Add payment status scopes
    public function scopePaymentPending(Builder $query): Builder
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopePaymentPaid(Builder $query): Builder
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopePaymentPartial(Builder $query): Builder
    {
        return $query->where('payment_status', 'partial');
    }

    // Add is_finalized scopes
    public function scopeFinalizedStatus(Builder $query): Builder
    {
        return $query->where('is_finalized', true);
    }

    public function scopeNotFinalized(Builder $query): Builder
    {
        return $query->where('is_finalized', false);
    }

    // Add date-based scopes
    public function scopeApprovedThisMonth(Builder $query): Builder
    {
        return $query->where('approval_status', self::STATUS_APPROVED)
                    ->whereMonth('chief_approval_date', now()->month)
                    ->whereYear('chief_approval_date', now()->year);
    }

    public function scopePendingForDays(Builder $query, int $days): Builder
    {
        return $query->where('approval_status', self::STATUS_PENDING)
                    ->where('allocation_date', '<=', now()->subDays($days));
    }
}