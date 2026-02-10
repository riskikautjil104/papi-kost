<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user()->load('usersExtended');
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Update extended profile
        if ($user->usersExtended) {
            $extendedData = $request->only([
                'phone',
                'address',
                'emergency_contact',
                'emergency_contact_name'
            ]);

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($user->usersExtended->profile_photo) {
                    Storage::disk('public')->delete($user->usersExtended->profile_photo);
                }

                $path = $request->file('profile_photo')->store('profiles', 'public');
                $extendedData['profile_photo'] = $path;
            }

            $user->usersExtended->update($extendedData);
        }

        // Redirect based on user role
        $redirectRoute = $user->is_admin ? 'profile.edit' : 'user.profile';
        return Redirect::route($redirectRoute)->with('status', 'Profil berhasil diperbarui!');
    }

    /**
     * Update user's profile photo only.
     */
    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_photo' => ['required', 'image', 'max:2048'], // max 2MB
        ]);

        $user = $request->user();

        if ($user->usersExtended) {
            // Delete old photo if exists
            if ($user->usersExtended->profile_photo) {
                Storage::disk('public')->delete($user->usersExtended->profile_photo);
            }

            $path = $request->file('profile_photo')->store('profiles', 'public');
            $user->usersExtended->update(['profile_photo' => $path]);
        }

        // Redirect based on user role
        $redirectRoute = $user->is_admin ? 'profile.edit' : 'user.profile';
        return Redirect::route($redirectRoute)->with('status', 'Foto profil berhasil diupdate!');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Redirect based on user role
        $redirectRoute = $request->user()->is_admin ? 'profile.edit' : 'user.profile';
        return Redirect::route($redirectRoute)->with('status', 'Password berhasil diperbarui!');
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

        // Delete profile photo if exists
        if ($user->usersExtended && $user->usersExtended->profile_photo) {
            Storage::disk('public')->delete($user->usersExtended->profile_photo);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
