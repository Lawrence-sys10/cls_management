<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
