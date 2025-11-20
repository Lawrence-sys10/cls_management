<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's password change form.
     */
    public function editPassword(Request $request): View
    {
        return view('profile.edit-password', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        Log::info('Password update attempt started for user: ' . $request->user()->id);
        Log::info('Request data:', $request->all());

        try {
            // Validate the request
            $validated = $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ], [
                'current_password.current_password' => 'The current password is incorrect.',
                'password.confirmed' => 'The new password confirmation does not match.',
            ]);

            Log::info('Validation passed');

            // Get the current user
            $user = $request->user();
            
            // Debug: Check current password hash
            Log::info('Current password hash in DB: ' . $user->password);
            Log::info('New password will be set');

            // Update the password - let the User model mutator handle the hashing
            $user->password = $validated['password']; // Don't hash here - let the mutator handle it
            $user->save();

            Log::info('Password updated in database. New hash: ' . $user->password);

            // Re-authenticate the user with the new password
            Auth::login($user);

            Log::info('User re-authenticated successfully');

            return Redirect::route('profile.password.edit')->with('status', 'password-updated');

        } catch (ValidationException $e) {
            Log::error('Password update validation failed: ', $e->errors());
            return redirect()->back()
                ->withErrors($e->errors(), 'updatePassword')
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Password update error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()
                ->withErrors(['error' => 'An unexpected error occurred. Please try again.'], 'updatePassword')
                ->withInput();
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}