<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Mail;
use App\Mail\LoginSecurityAlert;
use App\Models\User;
use Illuminate\Support\Facades\Log;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendLoginSecurityAlert implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;

        // Skip sending if we are in the middle of a 2FA challenge
        if (session('login.2fa_pending')) {
            return;
        }

        // Ensure we have a User model and an email address
        if ($user instanceof User) {
            $ipAddress = request()->ip();
            $userAgent = request()->userAgent();

            try {
                // Send the security alert email
                Mail::to($user->email)->send(new LoginSecurityAlert($user, $ipAddress, $userAgent));
            } catch (\Exception $e) {
                // Just log the error, don't crash the login
                Log::error('Login security alert failed: ' . $e->getMessage());
            }
        }
    }
}
