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

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('admin.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Add phone and timezone to the update
        $additionalData = $request->only(['phone', 'timezone']);

        $request->user()->fill(array_merge($validated, $additionalData));

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated')
            ->with('success', 'Profile details updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
            'password_changed_at' => now(),
        ]);

        return back()
            ->with('status', 'password-updated')
            ->with('success', 'Password updated successfully.');
    }

    /**
     * Upload profile photo.
     */
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);

        $user = $request->user();

        // Delete old photo if exists
        if ($user->profile_photo_path) {
            if (Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $oldPublic = public_path('storage/' . $user->profile_photo_path);
            if (file_exists($oldPublic)) {
                @unlink($oldPublic);
            }
        }

        // Store new photo
        $path = $request->file('profile_photo')->store('profile-photos', 'public');

        // Mirror to public/storage/profile-photos for direct web server delivery
        try {
            $dest = public_path('storage/' . $path);
            $destDir = dirname($dest);
            if (!file_exists($destDir)) {
                @mkdir($destDir, 0755, true);
            }
            @copy(storage_path('app/public/' . $path), $dest);
        } catch (\Throwable $e) {
            // fallback to /storage/{path} route
        }

        $user->profile_photo_path = $path;
        $user->save();

        return response()->json([
            'success' => true,
            'photo_url' => Storage::url($path),
            'message' => 'Profile photo updated successfully!'
        ]);
    }

    /**
     * Remove profile photo.
     */
    public function removePhoto(Request $request)
    {
        $user = $request->user();

        if ($user->profile_photo_path) {
            if (Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $pubFile = public_path('storage/' . $user->profile_photo_path);
            if (file_exists($pubFile)) {
                @unlink($pubFile);
            }
        }

        $user->profile_photo_path = null;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile photo removed successfully.',
            'initials' => $user->initials
        ]);
    }

    /**
     * Enable two-factor authentication.
     */
    public function enableTwoFactor(Request $request)
    {
        try {
            $validated = $request->validate([
                'method' => ['required', 'in:app,sms'],
            ]);

            $user = $request->user();
            \Log::info('2FA Enable Request', ['user_id' => $user->id, 'method' => $validated['method']]);

            if ($validated['method'] === 'app') {
                // Generate secret for authenticator app
                $google2fa = app('pragmarx.google2fa');
                $secret = $google2fa->generateSecretKey();
                \Log::info('2FA Secret Generated');

                $user->two_factor_method = 'app';
                $user->two_factor_secret = encrypt($secret);
                $user->save();
                \Log::info('2FA User Updated');

                // Generate QR code URL
                $qrCodeUrl = $google2fa->getQRCodeUrl(
                    str_replace(' ', '', config('app.name', 'Laravel')),
                    $user->email,
                    $secret
                );
                \Log::info('2FA QR Code URL Generated');

                return response()->json([
                    'success' => true,
                    'method' => 'app',
                    'secret' => $secret,
                    'qr_code_url' => $qrCodeUrl,
                ]);
            } else {
                $user->two_factor_method = 'sms';
                $user->save();

                return response()->json([
                    'success' => true,
                    'method' => 'sms',
                    'message' => 'Verification code would be sent via SMS.',
                ]);
            }
        } catch (\Throwable $e) {
            \Log::error('DEBUG 2FA ENABLE FAILURE: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Debug Error: ' . $e->getMessage(),
                'debug_info' => 'Check log for trace'
            ], 500);
        }
    }

    /**
     * Verify and confirm two-factor authentication.
     */
    public function confirmTwoFactor(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'min:6', 'max:6'],
        ]);

        $user = $request->user();

        if ($user->two_factor_method === 'app' && $user->two_factor_secret) {
            try {
                $google2fa = app('pragmarx.google2fa');
                $secret = decrypt($user->two_factor_secret);

                $valid = $google2fa->verifyKey($secret, $validated['code']);

                if ($valid) {
                    $user->two_factor_enabled = true;
                    $user->two_factor_confirmed_at = now();
                    $user->save();

                    // Generate backup codes
                    $backupCodes = $this->generateBackupCodes($user);

                    return response()->json([
                        'success' => true,
                        'backup_codes' => $backupCodes,
                        'message' => 'Two-factor authentication enabled successfully!'
                    ]);
                }
            } catch (\Exception $e) {
                // Log error if needed
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid verification code.',
        ], 422);
    }

    /**
     * Generate backup codes for user.
     */
    private function generateBackupCodes($user): array
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(bin2hex(random_bytes(3))); // 6 character codes
        }

        $user->two_factor_backup_codes = $codes;
        $user->save();

        return $codes;
    }

    /**
     * Disable two-factor authentication.
     */
    public function disableTwoFactor(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = $request->user();

        $user->two_factor_enabled = false;
        $user->two_factor_method = null;
        $user->two_factor_secret = null;
        $user->two_factor_backup_codes = null;
        $user->two_factor_confirmed_at = null;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Two-factor authentication disabled.'
        ]);
    }

    /**
     * Regenerate backup codes.
     */
    public function regenerateBackupCodes(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if (!$user->two_factor_enabled || !$user->two_factor_confirmed_at) {
            return response()->json([
                'success' => false,
                'message' => 'Two-factor authentication is not enabled.',
            ], 400);
        }

        $backupCodes = $this->generateBackupCodes($user);

        return response()->json([
            'success' => true,
            'backup_codes' => $backupCodes,
            'message' => 'Backup codes regenerated successfully.'
        ]);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
            'confirm' => ['required', 'string'],
        ]);

        $user = $request->user();

        // Verify confirmation text
        if ($request->confirm !== "DELETE {$user->email}") {
            return back()->withErrors(['confirm' => 'Confirmation text does not match.']);
        }

        // Delete profile photo if exists
        if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // Logout user
        Auth::logout();

        // Delete user
        $user->delete();

        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('success', 'Your account has been deleted.');
    }
}
