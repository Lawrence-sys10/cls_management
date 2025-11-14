<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'date_range',
        'generated_by',
        'file_path',
        'parameters',
        'is_scheduled',
    ];

    protected $casts = [
        'date_range' => 'array',
        'parameters' => 'array',
        'generated_at' => 'datetime',
        'is_scheduled' => 'boolean',
    ];

    const TYPE_LANDS = 'lands';
    const TYPE_ALLOCATIONS = 'allocations';
    const TYPE_CLIENTS = 'clients';
    const TYPE_PAYMENTS = 'payments';
    const TYPE_CHIEFS = 'chiefs';

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
