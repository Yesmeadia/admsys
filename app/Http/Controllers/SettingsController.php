<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\SiteSetting;

class SettingsController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $user = Auth::user();
        $settings = SiteSetting::pluck('value', 'key');

        return view('admin.settings', compact('user', 'settings'));
    }

    /**
     * Update profile information
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:15',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()
                ->route('admin.settings.index')
                ->with('error', 'Current password is incorrect.');
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Password updated successfully.');
    }

    /**
     * Update profile picture
     */
    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Delete old profile picture if exists
        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // Store new profile picture
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');

        $user->update([
            'profile_picture' => $path,
        ]);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Profile picture updated successfully.');
    }

    /**
     * Update application settings
     */
    public function updateApplicationSettings(Request $request)
    {
        $request->validate([
            'site_name' => 'nullable|string|max:255',
            'academic_year' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'form1_enabled' => 'nullable|string',
            'form2_enabled' => 'nullable|string',
            'form3_enabled' => 'nullable|string',
            'auto_responder_enabled' => 'nullable|string',
            'admin_alerts_enabled' => 'nullable|string',
            'form1_classes' => 'nullable|string',
            'form2_classes' => 'nullable|string',
            'form3_classes' => 'nullable|string',
            'form3_science_groups' => 'nullable|string',
            'form3_arts_groups' => 'nullable|string',
        ]);

        if ($request->filled('site_name')) {
            $schoolName = trim($request->site_name);
            SiteSetting::set('site_name', $schoolName);

            // Automatically rewrite/overwrite SCHOOL_NAME in .env file
            $envPath = base_path('.env');
            if (file_exists($envPath) && is_writable($envPath)) {
                $envContent = file_get_contents($envPath);
                $escapedSchoolName = addcslashes($schoolName, '"\\$');
                $replacement = 'SCHOOL_NAME="' . $escapedSchoolName . '"';

                if (preg_match('/^SCHOOL_NAME=.*/m', $envContent)) {
                    $envContent = preg_replace('/^SCHOOL_NAME=.*$/m', $replacement, $envContent);
                } else {
                    $envContent .= "\n" . $replacement . "\n";
                }

                file_put_contents($envPath, $envContent);
            }

            config(['app.school_name' => $schoolName]);
        }
        if ($request->has('academic_year')) {
            SiteSetting::set('academic_year', $request->academic_year);
        }
        if ($request->has('contact_email')) {
            SiteSetting::set('contact_email', $request->contact_email);
        }
        if ($request->has('contact_phone')) {
            SiteSetting::set('contact_phone', $request->contact_phone);
        }

        SiteSetting::set('form1_enabled', $request->has('form1_enabled') ? '1' : '0');
        SiteSetting::set('form2_enabled', $request->has('form2_enabled') ? '1' : '0');
        SiteSetting::set('form3_enabled', $request->has('form3_enabled') ? '1' : '0');
        SiteSetting::set('auto_responder_enabled', $request->has('auto_responder_enabled') ? '1' : '0');
        SiteSetting::set('admin_alerts_enabled', $request->has('admin_alerts_enabled') ? '1' : '0');

        // Manage dynamic classes for each admission form
        if ($request->has('form1_classes')) {
            SiteSetting::set('form1_classes', $request->input('form1_classes'));
        }
        if ($request->has('form2_classes')) {
            SiteSetting::set('form2_classes', $request->input('form2_classes'));
        }
        if ($request->has('form3_classes')) {
            SiteSetting::set('form3_classes', $request->input('form3_classes'));
        }

        // Manage dynamic subject groups for Form 3
        if ($request->has('form3_science_groups') || $request->has('form3_arts_groups')) {
            SiteSetting::saveForm3SubjectGroups(
                $request->input('form3_science_groups', ''),
                $request->input('form3_arts_groups', '')
            );
        }

        return redirect()
            ->route('admin.settings')
            ->with('success', 'System, classes, and portal configurations updated successfully.');
    }

    /**
     * Clear application cache
     */
    public function clearCache()
    {
        try {
            \Artisan::call('cache:clear');
            \Artisan::call('view:clear');
            \Artisan::call('config:clear');
            \Artisan::call('route:clear');

            return redirect()
                ->route('admin.settings')
                ->with('success', 'System cache and views cleared successfully.');
        } catch (\Exception $e) {
            \Log::error('Failed to clear cache: ' . $e->getMessage());
            return redirect()
                ->route('admin.settings')
                ->with('error', 'Failed to clear cache. Please check file permissions.');
        }
    }
}
