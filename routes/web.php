<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SettingsController;

// CSRF Token management routes (must be before any other routes)
Route::get('/refresh-csrf', function () {
    return response()->json([
        'csrf_token' => csrf_token(),
        'timestamp' => now()->toISOString()
    ]);
})->name('csrf.refresh');

Route::post('/validate-csrf', function (Request $request) {
    $request->validate([
        '_token' => 'required|string'
    ]);

    $isValid = hash_equals(session()->token(), $request->input('_token'));

    return response()->json([
        'valid' => $isValid,
        'message' => $isValid ? 'Token is valid' : 'Token is invalid'
    ]);
})->name('csrf.validate')->middleware('throttle:30,1');

// Home Page Route
Route::get('/', [HomeController::class, 'home'])->name('home');

// Form Selection Routes
Route::get('/admission/select-form', [AdmissionController::class, 'showFormSelection'])->name('admission.select');
Route::post('/admission/select-form', [AdmissionController::class, 'processFormSelection'])->name('admission.select.post');

// Form 1 Routes (Pre-primary to Class 2)
Route::get('/admission/form1', [AdmissionController::class, 'showForm1'])->name('admission.form1');
Route::post('/admission/form1', [AdmissionController::class, 'submitForm1'])->name('admission.form1.submit')->middleware('throttle:10,1');

// Form 2 Routes (Class 3 to 10)
Route::get('/admission/form2', [AdmissionController::class, 'showForm2'])->name('admission.form2');
Route::post('/admission/form2', [AdmissionController::class, 'submitForm2'])->name('admission.form2.submit')->middleware('throttle:10,1');

// Form 3 Routes (Higher Secondary - 11th only)
Route::get('/admission/form3', [AdmissionController::class, 'showForm3'])->name('admission.form3');
Route::post('/admission/form3', [AdmissionController::class, 'submitForm3'])->name('admission.form3.submit')->middleware('throttle:10,1');

// Success & Download Routes
Route::get('/admission/success/{id}', [AdmissionController::class, 'showSuccess'])->name('admission.success');
Route::get('/admission/download-pdf/{id}', [AdmissionController::class, 'downloadPDF'])->name('admission.download');


// Admin Authentication Routes
Route::prefix('admin')->group(function () {
    // Show login form (only for guests)
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('admin.login')
        ->middleware('guest');

    // Handle login submission (throttled to 5 attempts per minute)
    Route::post('/login', [AuthController::class, 'login'])
        ->name('admin.login.post')
        ->middleware('throttle:5,1');

    // Two-Factor Authentication Challenge Routes (throttled to 5 attempts per minute)
    Route::get('/login/two-factor', [AuthController::class, 'showTwoFactorChallenge'])->name('admin.login.two-factor');
    Route::post('/login/two-factor', [AuthController::class, 'verifyTwoFactor'])
        ->name('admin.login.two-factor.post')
        ->middleware('throttle:5,1');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

    // Password Reset Routes (throttled to 3 attempts per minute)
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
        ->name('password.email')
        ->middleware('throttle:3,1');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])
        ->name('password.update')
        ->middleware('throttle:5,1');
});

// Password Confirmation Routes (PROTECTED - requires auth)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/confirm-password', [AuthController::class, 'showConfirmPassword'])->name('password.confirm');
    Route::post('/confirm-password', [AuthController::class, 'confirmPassword'])->name('password.confirm.post');
});

// Protected Admin Routes (require authentication)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Dashboard - Main landing page after login
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Application Management
    Route::get('/applications', [AdminController::class, 'applications'])->name('admin.applications');
    Route::get('/application/{id}', [AdminController::class, 'viewApplication'])->name('admin.application.view');
    Route::post('/application/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.application.status');
    Route::post('/application/{id}/update', [AdminController::class, 'updateApplication'])->name('admin.application.update');
    Route::get('/application/{id}/download', [AdminController::class, 'downloadApplication'])->name('admin.application.download');
    Route::post('/application/{id}/reset-download', [AdminController::class, 'resetDownload'])->name('admin.application.reset-download');

    // Email Sending Routes
    Route::get('/application/{id}/send-email', [AdminController::class, 'showSendEmail'])->name('admin.application.send-email.show');
    Route::post('/application/{id}/send-email', [AdminController::class, 'sendApplicationEmail'])->name('admin.application.send-email');

    // Bulk Actions
    Route::post('/applications/bulk-update', [AdminController::class, 'bulkUpdateStatus'])->name('admin.applications.bulk-update');
    // Delete moved to Admin Only group

    // Statistics
    Route::get('/statistics', [AdminController::class, 'statistics'])->name('admin.statistics');
    Route::get('/api/statistics', [AdminController::class, 'getStatistics'])->name('admin.statistics.api');

    // Serve uploaded files securely
    Route::get('/file/{path}', [AdminController::class, 'serveFile'])->name('admin.file.serve');

    // Export
    Route::post('/applications/export', [AdminController::class, 'exportApplications'])->name('admin.applications.export');
    // Activity Logs export moved to Admin Only group

    // Notifications Management
    Route::prefix('notifications')->name('admin.notifications.')->group(function () {
        Route::get('/', function () {
            $notifications = auth()->user()->notifications()->paginate(20);
            return view('admin.notifications.index', compact('notifications'));
        })->name('index');

        Route::post('/{notification}/read', function ($notificationId) {
            $notification = auth()->user()->notifications()->findOrFail($notificationId);
            $notification->markAsRead();
            return response()->json(['success' => true]);
        })->name('read');

        Route::post('/mark-all-read', function () {
            auth()->user()->unreadNotifications->markAsRead();
            return response()->json(['success' => true]);
        })->name('mark-all-read');

        Route::delete('/{notification}', function ($notificationId) {
            auth()->user()->notifications()->where('id', $notificationId)->delete();
            return response()->json(['success' => true]);
        })->name('delete');

        Route::get('/count/unread', function () {
            $count = auth()->user()->unreadNotifications()->count();
            return response()->json(['count' => $count]);
        })->name('count.unread');
    });

    // Applications Count API
    Route::get('/applications/count/pending', function () {
        $count = \App\Models\AdmissionQuery::where('status', 'pending')->count();
        return response()->json(['count' => $count]);
    })->name('admin.applications.count.pending');

    // Settings moved to Admin Only group

    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::post('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        Route::post('/photo', [ProfileController::class, 'uploadPhoto'])->name('photo.upload');
        Route::delete('/photo', [ProfileController::class, 'removePhoto'])->name('photo.remove');
        Route::post('/two-factor/enable', [ProfileController::class, 'enableTwoFactor'])->name('two-factor.enable');
        Route::post('/two-factor/confirm', [ProfileController::class, 'confirmTwoFactor'])->name('two-factor.confirm');
        Route::post('/two-factor/disable', [ProfileController::class, 'disableTwoFactor'])->name('two-factor.disable');
        Route::post('/two-factor/regenerate-backup-codes', [ProfileController::class, 'regenerateBackupCodes'])->name('two-factor.regenerate-backup');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Admin Only Routes (User Management, Settings, Delete)
    Route::middleware(['role:admin'])->group(function () {
        // User Management
        Route::get('/users', [AdminController::class, 'indexUsers'])->name('admin.users.index');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');

        // Sensitive Actions
        Route::delete('/application/{id}', [AdminController::class, 'deleteApplication'])->name('admin.application.delete');

        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings');
        Route::post('/settings/application', [SettingsController::class, 'updateApplicationSettings'])->name('admin.settings.application');
        Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('admin.settings.profile');
        Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('admin.settings.password');
        Route::post('/settings/clear-cache', [SettingsController::class, 'clearCache'])->name('admin.settings.clear-cache');

        // Activity Logs
        Route::get('/activity-logs', [AdminController::class, 'activityLogs'])->name('admin.activity-logs.index');
        Route::get('/activity-logs/export', [AdminController::class, 'exportActivityLog'])->name('admin.activity-logs.export');

        // User Ban/Unban
        Route::post('/users/{user}/ban', [AdminController::class, 'banUser'])->name('admin.users.ban');
        Route::post('/users/{user}/unban', [AdminController::class, 'unbanUser'])->name('admin.users.unban');
    });
});

// Additional redirect routes for better UX
Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login');
    });

    Route::get('', function () {
        return redirect()->route('admin.login');
    });
});

// Fallback route for any 'login' route references
Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('admin.login');
})->name('login');

// Redirect root /admin to appropriate page
Route::get('/admin', function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('admin.login');
});




// API Routes for real-time features
Route::middleware(['auth'])->prefix('api/admin')->group(function () {
    Route::get('/notifications/unread', function () {
        $notifications = auth()->user()->unreadNotifications()->latest()->take(10)->get();
        return response()->json($notifications);
    });

    Route::get('/stats/quick', function () {
        $stats = [
            'pending' => \App\Models\AdmissionQuery::where('status', 'pending')->count(),
            'reviewed' => \App\Models\AdmissionQuery::where('status', 'reviewed')->count(),
            'accepted' => \App\Models\AdmissionQuery::where('status', 'accepted')->count(),
            'rejected' => \App\Models\AdmissionQuery::where('status', 'rejected')->count(),
            'total' => \App\Models\AdmissionQuery::count(),
            'today' => \App\Models\AdmissionQuery::whereDate('created_at', today())->count(),
        ];
        return response()->json($stats);
    });

    // Live activity feed endpoint (Restricted to Administrator)
    Route::get('/activity/recent', function () {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $activities = \App\Models\ActivityLog::with('user')
            ->latest()
            ->limit(15)
            ->get()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'user_name' => $activity->user ? $activity->user->name : 'System',
                    'user_initial' => $activity->user ? strtoupper(substr($activity->user->name, 0, 1)) : 'S',
                    'user_role' => $activity->user ? ($activity->user->isAdmin() ? 'Administrator' : 'Office Staff') : 'Automated',
                    'activity' => $activity->activity,
                    'description' => $activity->description,
                    'ip_address' => $activity->ip_address,
                    'time_ago' => $activity->created_at->diffForHumans(),
                    'created_at' => $activity->created_at->toIso8601String(),
                ];
            });
        return response()->json($activities);
    });
});

// WebSocket/Pusher auth endpoint
Route::middleware(['auth'])->post('/broadcasting/auth', function () {
    if (!class_exists('Pusher\Pusher')) {
        return response('Pusher not configured', 503);
    }
    $pusher = new \Pusher\Pusher(
        config('broadcasting.connections.pusher.key'),
        config('broadcasting.connections.pusher.secret'),
        config('broadcasting.connections.pusher.app_id'),
        [
            'cluster' => config('broadcasting.connections.pusher.options.cluster'),
            'encrypted' => true
        ]
    );

    $channelName = \Illuminate\Support\Facades\Request::input('channel_name');
    $socketId = \Illuminate\Support\Facades\Request::input('socket_id');

    if (str_starts_with($channelName, 'private-user.')) {
        $userId = str_replace('private-user.', '', $channelName);
        if ($userId == auth()->id()) {
            $auth = $pusher->authorizeChannel($channelName, $socketId);
            return response($auth);
        }
    }

    return response('Forbidden', 403);
});

// Serve public storage files directly (e.g. profile-photos and public uploads)
Route::get('/storage/{path}', function ($path) {
    // Prohibit unauthenticated access to confidential admission documents
    if (str_starts_with($path, 'admission-documents/') || str_contains($path, 'admission-documents')) {
        abort(403, 'Unauthorized access to applicant documents');
    }

    if (str_contains($path, '..') || str_contains($path, '\\') || str_contains($path, "\0")) {
        abort(403, 'Invalid file path');
    }

    $basePath = realpath(storage_path('app/public'));
    $fullPath = storage_path('app/public/' . $path);
    $realFullPath = realpath($fullPath);

    if (!$realFullPath || !$basePath || !str_starts_with($realFullPath, $basePath . DIRECTORY_SEPARATOR) || !file_exists($realFullPath) || is_dir($realFullPath)) {
        abort(404, 'File not found');
    }

    $mime = mime_content_type($realFullPath) ?: 'application/octet-stream';

    return response()->file($realFullPath, [
        'Content-Type' => $mime,
        'Cache-Control' => 'public, max-age=86400',
        'X-Content-Type-Options' => 'nosniff',
    ]);
})->where('path', '.*')->name('storage.file');

// Catch-all route for 404
Route::fallback(function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('admin.login');
});
