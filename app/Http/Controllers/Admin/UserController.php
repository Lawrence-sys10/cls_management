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
}
