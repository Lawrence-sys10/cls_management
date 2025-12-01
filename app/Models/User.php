<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'user_type',
        'is_active',
        'last_login_at',
        'email_verified_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Add appends for accessors
    protected $appends = [
        'role_name',
        'is_administrator',
        'is_staff_member',
        'is_chief',
        'status',
        'status_badge_class',
        'initials'
    ];

    public function staff(): HasOne
    {
        return $this->hasOne(Staff::class);
    }

    /**
     * Relationship with chief profile
     */
    public function chief(): HasOne
    {
        return $this->hasOne(Chief::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Relationship with lands (for chiefs)
     */
    public function lands(): HasMany
    {
        return $this->hasMany(Land::class, 'chief_id');
    }

    /**
     * Relationship with clients (for chiefs)
     */
    public function clients(): HasMany
    {
        return $this->hasMany(Client::class, 'chief_id');
    }

    /**
     * Relationship with allocations (for chiefs)
     */
    public function allocations(): HasMany
    {
        return $this->hasMany(Allocation::class, 'chief_id');
    }

    /**
     * Relationship with disputes (for chiefs) - SAFE VERSION
     */
    public function disputes(): HasMany
    {
        // Check if Dispute model exists to prevent errors
        if (class_exists('App\Models\Dispute')) {
            return $this->hasMany(Dispute::class, 'chief_id');
        }
        
        // Return a safe fallback that won't cause errors
        return $this->hasMany(Land::class, 'chief_id')->whereRaw('1=0');
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive users.
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope a query to search users by name, email, or phone.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    /**
     * Scope a query to only include chiefs.
     */
    public function scopeChiefs($query)
    {
        return $query->whereHas('roles', function($q) {
            $q->where('name', 'chief');
        });
    }

    /**
     * Scope a query to only include admins.
     */
    public function scopeAdmins($query)
    {
        return $query->whereHas('roles', function($q) {
            $q->where('name', 'admin');
        });
    }

    /**
     * Scope a query to only include staff.
     */
    public function scopeStaff($query)
    {
        return $query->whereHas('roles', function($q) {
            $q->where('name', 'staff');
        });
    }

    /**
     * Get the user's primary role name for display.
     */
    protected function roleName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->roles->first()->name ?? 'No Role'
        );
    }

    /**
     * Check if user is administrator.
     */
    protected function isAdministrator(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->hasRole('admin')
        );
    }

    /**
     * Check if user is staff member.
     */
    protected function isStaffMember(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->hasRole('staff')
        );
    }

    /**
     * Check if user is chief.
     */
    protected function isChief(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->hasRole('chief')
        );
    }

    /**
     * Get the user's display status.
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->is_active ? 'Active' : 'Inactive'
        );
    }

    /**
     * Get the user's status badge class.
     */
    protected function statusBadgeClass(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->is_active ? 'bg-success' : 'bg-danger'
        );
    }

    /**
     * Get the user's initials for avatar.
     */
    protected function initials(): Attribute
    {
        return Attribute::make(
            get: function () {
                $names = explode(' ', $this->name);
                $initials = '';
                
                foreach ($names as $name) {
                    $initials .= strtoupper(substr($name, 0, 1));
                    if (strlen($initials) >= 2) break;
                }
                
                return $initials ?: 'U';
            }
        );
    }

    /**
     * Update last login timestamp.
     */
    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }

    /**
     * Activate the user.
     */
    public function activate(): bool
    {
        return $this->update(['is_active' => true]);
    }

    /**
     * Deactivate the user.
     */
    public function deactivate(): bool
    {
        return $this->update(['is_active' => false]);
    }

    /**
     * Check if user can be impersonated.
     */
    public function canBeImpersonated(): bool
    {
        // Don't allow impersonating other admins or inactive users
        return !$this->hasRole('admin') && $this->is_active;
    }

    /**
     * Check if current user can impersonate others.
     */
    public function canImpersonate(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Get users that this user can manage (for managers/staff).
     */
    public function manageableUsers()
    {
        if ($this->hasRole('admin')) {
            return User::query();
        }
        
        if ($this->hasRole('staff')) {
            return User::whereHas('roles', function($query) {
                $query->whereIn('name', ['chief', 'user']);
            });
        }
        
        return User::where('id', $this->id); // Can only manage themselves
    }

    /**
     * Get dashboard statistics for this user.
     */
    public function getDashboardStats(): array
    {
        $stats = [
            'total_users' => 0,
            'active_users' => 0,
            'inactive_users' => 0,
            'my_lands' => 0,
            'my_clients' => 0,
            'my_allocations' => 0,
            'pending_disputes' => 0,
        ];

        if ($this->hasRole('admin')) {
            // Admin can see all stats
            $stats['total_users'] = User::count();
            $stats['active_users'] = User::active()->count();
            $stats['inactive_users'] = User::inactive()->count();
            $stats['total_lands'] = Land::count();
            $stats['total_clients'] = Client::count();
            $stats['total_allocations'] = Allocation::count();
            
            // Safe dispute count for admin
            if (class_exists('App\Models\Dispute')) {
                $stats['pending_disputes'] = Dispute::where('status', 'pending')->count();
            } else {
                $stats['pending_disputes'] = 0;
            }
        } elseif ($this->hasRole('chief')) {
            // Chief sees only their own stats
            $stats['my_lands'] = $this->lands()->count();
            $stats['my_clients'] = $this->clients()->count();
            $stats['my_allocations'] = $this->allocations()->count();
            
            // Safe dispute count for chief
            try {
                $stats['pending_disputes'] = $this->disputes()->where('status', 'pending')->count();
            } catch (\Exception $e) {
                $stats['pending_disputes'] = 0;
            }
        } else {
            // Staff sees general stats
            $stats['total_lands'] = Land::count();
            $stats['total_clients'] = Client::count();
            $stats['total_allocations'] = Allocation::count();
            
            // Safe dispute count for staff
            if (class_exists('App\Models\Dispute')) {
                $stats['pending_disputes'] = Dispute::where('status', 'pending')->count();
            } else {
                $stats['pending_disputes'] = 0;
            }
        }

        return $stats;
    }

    /**
     * Create a staff record for this user.
     */
    public function createStaffRecord(array $data): Staff
    {
        return Staff::create(array_merge($data, ['user_id' => $this->id]));
    }

    /**
     * Create a chief record for this user.
     */
    public function createChiefRecord(array $data): Chief
    {
        return Chief::create(array_merge($data, ['user_id' => $this->id]));
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole($roles): bool
    {
        if (is_string($roles)) {
            $roles = func_get_args();
        }

        return $this->roles()->whereIn('name', (array)$roles)->exists();
    }

    /**
     * Get users by role.
     */
    public static function getByRole(string $role)
    {
        return static::whereHas('roles', function($query) use ($role) {
            $query->where('name', $role);
        });
    }

    /**
     * Assign default role based on user_type.
     */
    public function assignDefaultRole(): void
    {
        if ($this->roles->isEmpty()) {
            $role = match($this->user_type) {
                'admin' => 'admin',
                'chief' => 'chief',
                default => 'staff'
            };
            
            $this->assignRole($role);
        }
    }

    /**
     * Set password attribute with proper hashing - FIXED VERSION
     */
    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => !empty($value) && !Hash::needsRehash($value) ? $value : Hash::make($value),
        );
    }

    /**
     * Boot method for model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Set default user_type if not provided
        static::creating(function ($user) {
            if (empty($user->user_type)) {
                $user->user_type = 'staff';
            }
            if (is_null($user->is_active)) {
                $user->is_active = true;
            }
        });

        // Assign default role after creation
        static::created(function ($user) {
            $user->assignDefaultRole();
        });

        // Delete related records when user is deleted
        static::deleting(function ($user) {
            if ($user->staff) {
                $user->staff->delete();
            }
            
            // Delete chief profile if exists
            if ($user->chief) {
                $user->chief->delete();
            }
            
            // Delete related chief data
            if ($user->hasRole('chief')) {
                $user->lands()->update(['chief_id' => null]);
                $user->clients()->update(['chief_id' => null]);
                $user->allocations()->update(['chief_id' => null]);
                
                // Safe dispute handling
                try {
                    $user->disputes()->update(['chief_id' => null]);
                } catch (\Exception $e) {
                    // Ignore if disputes table doesn't exist
                }
            }
            
            // Delete activity logs
            $user->activityLogs()->delete();
            
            // Remove roles
            $user->roles()->detach();
        });
    }

    /**
     * Get chief-specific dashboard data
     */
    public function getChiefDashboardData(): array
    {
        if (!$this->hasRole('chief')) {
            return [];
        }

        $recentClients = $this->clients()
            ->with(['allocations'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentAllocations = $this->allocations()
            ->with(['client', 'land'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Safe dispute count
        $pendingDisputes = 0;
        try {
            $pendingDisputes = $this->disputes()->where('status', 'pending')->count();
        } catch (\Exception $e) {
            // Ignore if disputes don't exist
        }

        return [
            'stats' => [
                'total_lands' => $this->lands()->count(),
                'total_clients' => $this->clients()->count(),
                'total_allocations' => $this->allocations()->count(),
                'pending_disputes' => $pendingDisputes,
                'active_allocations' => $this->allocations()->where('status', 'active')->count(),
            ],
            'recent_clients' => $recentClients,
            'recent_allocations' => $recentAllocations,
            'recent_activities' => $this->activityLogs()
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
        ];
    }

    /**
     * Check if user can access chief features
     */
    public function canAccessChiefFeatures(): bool
    {
        return $this->hasRole('chief') && $this->is_active;
    }

    /**
     * Get display name with role badge
     */
    public function getDisplayNameWithRole(): string
    {
        $role = $this->role_name;
        $badgeClass = match($role) {
            'admin' => 'badge bg-danger',
            'chief' => 'badge bg-primary',
            'staff' => 'badge bg-success',
            default => 'badge bg-secondary'
        };

        return "{$this->name} <span class='{$badgeClass}'>{$role}</span>";
    }

    /**
     * Ensure chief has a chief profile
     */
    public function ensureChiefProfile(): Chief
    {
        if (!$this->chief) {
            return $this->createChiefRecord([
                'name' => $this->name,
                'jurisdiction' => 'Default Region',
                'phone' => $this->phone ?? 'default-phone-' . $this->id,
                'email' => $this->email,
                'is_active' => true,
            ]);
        }

        return $this->chief;
    }

    /**
     * Get chief ID safely (creates profile if needed)
     */
    public function getChiefId(): ?int
    {
        if (!$this->hasRole('chief')) {
            return null;
        }

        $chief = $this->ensureChiefProfile();
        return $chief->id;
    }

    /**
     * Get clients count for chief with optional filters
     */
    public function getClientsCount(array $filters = []): int
    {
        if (!$this->hasRole('chief')) {
            return 0;
        }

        $query = $this->clients();

        if (isset($filters['search']) && $filters['search']) {
            $query->search($filters['search']);
        }

        if (isset($filters['status']) && $filters['status']) {
            // Add status filtering logic if needed
        }

        return $query->count();
    }

    /**
     * Check if chief can create more clients (if there's a limit)
     */
    public function canCreateMoreClients(): bool
    {
        if (!$this->hasRole('chief')) {
            return false;
        }

        // You can implement client limits here if needed
        $currentClientCount = $this->clients()->count();
        $maxClients = config('app.max_clients_per_chief', 1000); // Set a high default or configure

        return $currentClientCount < $maxClients;
    }

    /**
     * Get chief's clients with pagination and filters
     */
    public function getClientsPaginated(array $filters = [], int $perPage = 15)
    {
        if (!$this->hasRole('chief')) {
            return collect();
        }

        $query = $this->clients()->with(['allocations']);

        if (isset($filters['search']) && $filters['search']) {
            $query->search($filters['search']);
        }

        if (isset($filters['has_allocations']) && $filters['has_allocations'] !== '') {
            if ($filters['has_allocations']) {
                $query->has('allocations');
            } else {
                $query->doesntHave('allocations');
            }
        }

        return $query->orderBy('created_at', 'desc')
                    ->paginate($perPage)
                    ->withQueryString();
    }
}