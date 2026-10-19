<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use PragmaRX\Google2FA\Google2FA;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'g-recaptcha-response' => 'required'
        ]);

        // 1. Validate Captcha
        if (!$this->validateCaptcha($request->input('g-recaptcha-response'))) {
            return back()->withErrors([
                'g-recaptcha-response' => 'Security verification failed. Please try again.'
            ])->withInput();
        }

        // 2. Initial Auth Attempt
        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $user = Auth::user();

            // 3. Check for Two-Factor Authentication
            if ($user->hasTwoFactorEnabled()) {
                // Temporarily log them out and store ID for 2FA verification
                $remember = $request->boolean('remember');
                Auth::logout();

                $request->session()->put('login.id', $user->id);
                $request->session()->put('login.remember', $remember);
                $request->session()->put('login.2fa_pending', true);

                return redirect()->route('admin.login.two-factor');
            }

            // Standard login success
            $request->session()->regenerate();

            // Update last login details
            $user->last_login_at = now();
            $user->last_login_ip = $request->ip();
            $user->save();

            // Log Activity
            \App\Models\ActivityLog::log('Login', 'Admin logged into dashboard');

            return redirect()->route('admin.dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show Two-Factor Authentication Challenge
     */
    public function showTwoFactorChallenge(Request $request)
    {
        if (!$request->session()->has('login.id')) {
            return redirect()->route('admin.login');
        }

        return view('admin.auth.two-factor');
    }

    /**
     * Verify Two-Factor Authentication Code
     */
    public function verifyTwoFactor(Request $request)
    {
        $request->validate([
            'code' => 'required|string|min:6|max:12',
        ]);

        if (!$request->session()->has('login.id')) {
            return redirect()->route('admin.login');
        }

        $user = User::findOrFail($request->session()->get('login.id'));

        // Check if user submitted a valid backup code
        $enteredCode = strtoupper(trim($request->code));
        $backupCodes = $user->two_factor_backup_codes ?? [];

        if (is_array($backupCodes) && in_array($enteredCode, array_map('strtoupper', $backupCodes))) {
            // Remove the used backup code
            $user->two_factor_backup_codes = array_values(array_filter($backupCodes, fn($code) => strtoupper($code) !== $enteredCode));
            $user->save();

            // Log in the user
            Auth::login($user, $request->session()->get('login.remember', false));
            $request->session()->forget(['login.id', 'login.remember', 'login.2fa_pending']);
            $request->session()->regenerate();

            // Update login stats
            $user->last_login_at = now();
            $user->last_login_ip = $request->ip();
            $user->save();

            \App\Models\ActivityLog::log('Login-2FA', 'Admin logged in using 2FA backup code');

            return redirect()->route('admin.dashboard')->with('success', 'Logged in successfully using backup code.');
        }

        // Check TOTP code
        if ($user->two_factor_secret) {
            try {
                $google2fa = new Google2FA();
                $secret = decrypt($user->two_factor_secret);

                if ($google2fa->verifyKey($secret, $request->code)) {
                    // Success! Login the user
                    Auth::login($user, $request->session()->get('login.remember', false));

                    $request->session()->forget(['login.id', 'login.remember', 'login.2fa_pending']);
                    $request->session()->regenerate();

                    // Update stats
                    $user->last_login_at = now();
                    $user->last_login_ip = $request->ip();
                    $user->save();

                    \App\Models\ActivityLog::log('Login-2FA', 'Admin logged in with 2FA');

                    return redirect()->route('admin.dashboard')->with('success', 'Logged in successfully with 2FA.');
                }
            } catch (\Exception $e) {
                \Log::error('TOTP verification error: ' . $e->getMessage());
            }
        }

        return back()->withErrors(['code' => 'Invalid authentication or backup code. Please try again.']);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    /**
     * Show the password reset link request form.
     */
    public function showForgotPassword()
    {
        return view('admin.auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show the password reset form.
     */
    public function showResetPassword(Request $request)
    {
        return view('admin.auth.reset-password', [
            'token' => $request->token,
            'email' => $request->email
        ]);
    }

    /**
     * Handle an incoming new password request.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('admin.login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    /**
     * Show the password confirmation form.
     */
    public function showConfirmPassword()
    {
        return view('admin.auth.confirm-password');
    }

    /**
     * Confirm the user's password.
     */
    public function confirmPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended('/admin/dashboard');
    }

    /**
     * Captcha validation helper
     */
    private function validateCaptcha($response)
    {
        if (config('app.env') === 'local' && $response === 'test') {
            return true;
        }

        if (empty($response)) {
            return false;
        }

        $secret = config('captcha.secret_key');
        $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';

        try {
            $data = [
                'secret' => $secret,
                'response' => $response,
                'remoteip' => request()->ip()
            ];

            $context = stream_context_create([
                'http' => [
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method' => 'POST',
                    'content' => http_build_query($data)
                ]
            ]);

            $result = @file_get_contents($verifyUrl, false, $context);
            if ($result === false)
                return false;

            $resultJson = json_decode($result);
            return $resultJson->success ?? false;
        } catch (\Exception $e) {
            \Log::error('Captcha validation error: ' . $e->getMessage());
            return false;
        }
    }
}
