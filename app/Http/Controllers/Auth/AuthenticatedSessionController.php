<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        Log::info('=== LOGIN ATTEMPT START ===');
        Log::info('Login attempt for email: ' . $request->email);

        try {
            $request->authenticate();
            Log::info('Authentication successful');
        } catch (\Exception $e) {
            Log::error('Authentication failed: ' . $e->getMessage());
            throw $e;
        }

        $request->session()->regenerate();
        Log::info('Session regenerated');

        $user = Auth::user();
        
        Log::info('User details:', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->getRoleNames()->toArray(),
            'hasRole(chief)' => $user->hasRole('chief') ? 'YES' : 'NO',
            'hasRole(admin)' => $user->hasRole('admin') ? 'YES' : 'NO',
            'hasRole(staff)' => $user->hasRole('staff') ? 'YES' : 'NO'
        ]);

        // Determine redirect based on user role
        if ($user->hasRole('chief')) {
            Log::info('REDIRECT: Chief user detected → redirecting to chief.dashboard');
            return redirect()->intended(route('chief.dashboard', absolute: false));
        } elseif ($user->hasRole('admin')) {
            Log::info('REDIRECT: Admin user detected → redirecting to admin.dashboard');
            return redirect()->intended(route('admin.dashboard', absolute: false));
        } else {
            Log::info('REDIRECT: Regular user detected → redirecting to dashboard');
            return redirect()->intended(route('dashboard', absolute: false));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        if ($user) {
            Log::info('User logout:', [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name
            ]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        Log::info('User logged out successfully');

        return redirect('/');
    }
}