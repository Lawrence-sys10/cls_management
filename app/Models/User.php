<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

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

    public function staff(): HasOne
    {
        return $this->hasOne(Staff::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
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
     * Get the user's primary role name for display.
     */
    public function getRoleNameAttribute(): string
    {
        return $this->roles->first()->name ?? 'No Role';
    }

    /**
     * Check if user is administrator.
     */
    public function getIsAdministratorAttribute(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is staff member.
     */
    public function getIsStaffAttribute(): bool
    {
        return $this->hasRole('staff') || $this->user_type === 'staff';
    }

    /**
     * Check if user is chief.
     */
    public function getIsChiefAttribute(): bool
    {
        return $this->hasRole('chief');
    }

    /**
     * Get the user's display status.
     */
    public function getStatusAttribute(): string
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    /**
     * Get the user's status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return $this->is_active ? 'bg-success' : 'bg-danger';
    }

    /**
     * Get the user's initials for avatar.
     */
    public function getInitialsAttribute(): string
    {
        $names = explode(' ', $this->name);
        $initials = '';
        
        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
            if (strlen($initials) >= 2) break;
        }
        
        return $initials;
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
        ];

        if ($this->canImpersonate()) {
            // Admin can see all stats
            $stats['total_users'] = User::count();
            $stats['active_users'] = User::active()->count();
            $stats['inactive_users'] = User::inactive()->count();
        } else {
            // Regular users see limited stats
            $stats['total_users'] = 1;
            $stats['active_users'] = $this->is_active ? 1 : 0;
            $stats['inactive_users'] = $this->is_active ? 0 : 1;
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
     * Check if user has any of the given roles.
     */
    public function hasAnyRole($roles): bool
    {
        if (is_string($roles)) {
            $roles = func_get_args();
        }

        return $this->roles()->whereIn('name', $roles)->exists();
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
        });

        // Hash password when setting it
        static::saving(function ($user) {
            if ($user->isDirty('password') && !empty($user->password)) {
                $user->password = Hash::make($user->password);
            }
        });

        // Delete related records when user is deleted
        static::deleting(function ($user) {
            if ($user->staff) {
                $user->staff->delete();
            }
            
            // Delete activity logs
            $user->activityLogs()->delete();
        });
    }
}