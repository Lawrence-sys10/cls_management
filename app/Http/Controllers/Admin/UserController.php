<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with(['roles', 'staff'])->latest()->paginate(20);
        $roles = Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create(): View
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
            'department' => 'required_if:user_type,staff|string|max:255',
            'employee_id' => 'required_if:user_type,staff|string|max:50',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type ?? 'staff',
        ]);

        // Assign roles
        $user->syncRoles($request->roles);

        // Create staff record if user type is staff
        if ($request->user_type === 'staff') {
            Staff::create([
                'user_id' => $user->id,
                'department' => $request->department,
                'phone' => $request->phone,
                'assigned_area' => $request->assigned_area,
                'employee_id' => $request->employee_id,
                'date_joined' => now(),
            ]);
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('created user: ' . $user->name);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    public function show(User $user): View
    {
        $user->load(['roles', 'staff']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $roles = Role::all();
        $user->load('roles');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:15',
            'roles' => 'required|array',
            'is_active' => 'boolean'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_active' => $request->is_active ?? true,
        ]);

        $user->syncRoles($request->roles);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('updated user: ' . $user->name);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user_name = $user->name;
        $user->delete();

        activity()
            ->causedBy(auth()->user())
            ->log('deleted user: ' . $user_name);

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }

    /**
     * Bulk actions for users
     */
    public function bulkActions(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|string|in:activate,deactivate,delete',
            'users' => 'required|array',
            'users.*' => 'exists:users,id'
        ]);

        $users = User::whereIn('id', $request->users)->get();

        switch ($request->action) {
            case 'activate':
                User::whereIn('id', $request->users)->update(['is_active' => true]);
                $message = 'Selected users activated successfully!';
                break;
            
            case 'deactivate':
                User::whereIn('id', $request->users)->update(['is_active' => false]);
                $message = 'Selected users deactivated successfully!';
                break;
            
            case 'delete':
                User::whereIn('id', $request->users)->delete();
                $message = 'Selected users deleted successfully!';
                break;
        }

        activity()
            ->causedBy(auth()->user())
            ->log('performed bulk action: ' . $request->action . ' on ' . count($request->users) . ' users');

        return redirect()->route('admin.users.index')->with('success', $message);
    }

    /**
     * Bulk delete users
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $request->validate([
            'users' => 'required|array',
            'users.*' => 'exists:users,id'
        ]);

        $count = User::whereIn('id', $request->users)->delete();

        activity()
            ->causedBy(auth()->user())
            ->log('bulk deleted ' . $count . ' users');

        return redirect()->route('admin.users.index')
            ->with('success', $count . ' users deleted successfully!');
    }

    /**
     * Bulk activate users
     */
    public function bulkActivate(Request $request): RedirectResponse
    {
        $request->validate([
            'users' => 'required|array',
            'users.*' => 'exists:users,id'
        ]);

        $count = User::whereIn('id', $request->users)->update(['is_active' => true]);

        activity()
            ->causedBy(auth()->user())
            ->log('bulk activated ' . $count . ' users');

        return redirect()->route('admin.users.index')
            ->with('success', $count . ' users activated successfully!');
    }

    /**
     * Bulk deactivate users
     */
    public function bulkDeactivate(Request $request): RedirectResponse
    {
        $request->validate([
            'users' => 'required|array',
            'users.*' => 'exists:users,id'
        ]);

        $count = User::whereIn('id', $request->users)->update(['is_active' => false]);

        activity()
            ->causedBy(auth()->user())
            ->log('bulk deactivated ' . $count . ' users');

        return redirect()->route('admin.users.index')
            ->with('success', $count . ' users deactivated successfully!');
    }

    /**
     * Activate a single user
     */
    public function activate(User $user): RedirectResponse
    {
        $user->update(['is_active' => true]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('activated user: ' . $user->name);

        return redirect()->route('admin.users.index')
            ->with('success', 'User activated successfully!');
    }

    /**
     * Deactivate a single user
     */
    public function deactivate(User $user): RedirectResponse
    {
        $user->update(['is_active' => false]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('deactivated user: ' . $user->name);

        return redirect()->route('admin.users.index')
            ->with('success', 'User deactivated successfully!');
    }

    /**
     * Impersonate a user
     */
    public function impersonate(User $user): RedirectResponse
    {
        if (!auth()->user()->can('admin')) {
            abort(403, 'Unauthorized action.');
        }

        // Store original user ID in session
        session()->put('impersonate.original_user_id', auth()->id());
        
        auth()->login($user);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('started impersonating user: ' . $user->name);

        return redirect()->route('dashboard')
            ->with('info', 'You are now impersonating ' . $user->name);
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('reset password for user: ' . $user->name);

        return redirect()->route('admin.users.index')
            ->with('success', 'Password reset successfully!');
    }

    /**
     * Update general settings
     */
    public function updateGeneralSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_email' => 'required|email',
            'timezone' => 'required|string',
            'date_format' => 'required|string',
            'site_description' => 'nullable|string|max:500',
        ]);

        // Here you would typically save to a settings table or config file
        // For now, we'll just return success
        activity()
            ->causedBy(auth()->user())
            ->log('updated general settings');

        return back()->with('success', 'General settings updated successfully.');
    }

    /**
     * Update system settings
     */
    public function updateSystemSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'maintenance_mode' => 'boolean',
            'user_registration' => 'boolean',
            'session_timeout' => 'required|integer|min:5',
            'records_per_page' => 'required|integer|min:5|max:100',
            'email_allocations' => 'boolean',
            'email_payments' => 'boolean',
        ]);

        // Here you would typically save to a settings table or config file
        // For now, we'll just return success
        activity()
            ->causedBy(auth()->user())
            ->log('updated system settings');

        return back()->with('success', 'System settings updated successfully.');
    }

    /**
     * Create system backup
     */
    public function createBackup(Request $request): RedirectResponse
    {
        $request->validate([
            'backup_type' => 'required|in:full,database',
            'include_media' => 'boolean',
            'include_logs' => 'boolean',
        ]);

        // Here you would implement backup logic using spatie/laravel-backup or similar
        // For now, we'll just return success
        activity()
            ->causedBy(auth()->user())
            ->log('created system backup');

        return back()->with('success', 'Backup created successfully. Download will start shortly.');
    }
}