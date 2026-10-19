<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoginSecurityAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $ipAddress;
    public $loginTime;
    public $userAgent;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $ipAddress, $userAgent)
    {
        $this->user = $user;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
        $this->loginTime = now()->format('Y-m-d H:i:s');
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Security Alert: New Login Detected')
            ->view('emails.login-security-alert');
    }
}
