# Step 1: Generate Laravel Models for CLS Management System
# Save this as generate-models.ps1 and run from project root

@'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'user_type',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function staff(): HasOne
    {
        return $this->hasOne(Staff::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }
}

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

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department',
        'phone',
        'assigned_area',
        'employee_id',
        'date_joined',
    ];

    protected $casts = [
        'date_joined' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(Allocation::class, 'processed_by');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }
}

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
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

    public function allocations(): HasMany
    {
        return $this->hasMany(Allocation::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}

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
}

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

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'url',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'logged_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
        'sent_via',
        'message',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    const VIA_EMAIL = 'email';
    const VIA_SMS = 'sms';
    const VIA_BOTH = 'both';

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }
}

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
'@ | Out-File -FilePath "app/Models/AllModels.php" -Encoding UTF8

Write-Host "✅ All models generated successfully in app/Models/AllModels.php" -ForegroundColor Green
Write-Host "📁 Next step: Run migrations and create controllers" -ForegroundColor Yellow
