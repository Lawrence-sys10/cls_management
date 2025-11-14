<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'land_id',
        'allocation_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'document_type',
        'description',
        'uploaded_by',
        'is_verified',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'is_verified' => 'boolean',
        'uploaded_at' => 'datetime',
    ];

    const TYPE_ID_CARD = 'id_card';
    const TYPE_PASSPORT_PHOTO = 'passport_photo';
    const TYPE_SURVEY_PLAN = 'survey_plan';
    const TYPE_SITE_PLAN = 'site_plan';
    const TYPE_TITLE_DEED = 'title_deed';
    const TYPE_ALLOCATION_LETTER = 'allocation_letter';
    const TYPE_SUPPORTING_LETTER = 'supporting_letter';
    const TYPE_OTHER = 'other';

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function land(): BelongsTo
    {
        return $this->belongsTo(Land::class);
    }

    public function allocation(): BelongsTo
    {
        return $this->belongsTo(Allocation::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
