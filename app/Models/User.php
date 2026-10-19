<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ResetPasswordNotification; // Add this line

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'timezone',
        'profile_photo_path',
        'two_factor_enabled',
        'two_factor_method',
        'two_factor_secret',
        'two_factor_backup_codes',
        'two_factor_confirmed_at',
        'password_changed_at',
        'last_login_ip',
        'last_login_at',
        'banned_at',
        'banned_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_backup_codes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_enabled' => 'boolean',
            'two_factor_confirmed_at' => 'datetime',
            'password_changed_at' => 'datetime',
            'last_login_at' => 'datetime',
            'two_factor_backup_codes' => 'array',
        ];
    }

    /**
     * Send password reset notification
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Check if 2FA is enabled and confirmed
     */
    public function hasTwoFactorEnabled(): bool
    {
        return $this->two_factor_enabled && $this->two_factor_confirmed_at !== null;
    }

    /**
     * Get profile photo URL
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        if ($this->profile_photo_path && Storage::disk('public')->exists($this->profile_photo_path)) {
            return Storage::url($this->profile_photo_path);
        }

        return null;
    }

    /**
     * Get user's initials for avatar
     */
    public function getInitialsAttribute(): string
    {
        $name = trim($this->name);
        if (empty($name)) {
            return '??';
        }

        $names = explode(' ', $name);

        if (count($names) >= 2) {
            $first = substr($names[0], 0, 1);
            $second = substr($names[1], 0, 1);
            return strtoupper($first . $second);
        }

        return strtoupper(substr($name, 0, 2));
    }

    /**
     * Check if user has uploaded a profile photo
     */
    public function hasProfilePhoto(): bool
    {
        return !empty($this->profile_photo_path);
    }

    /**
     * Get 2FA backup codes
     */
    public function getTwoFactorBackupCodes(): array
    {
        return $this->two_factor_backup_codes ?? [];
    }

    /**
     * Generate new 2FA backup codes
     */
    public function generateTwoFactorBackupCodes(): array
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(bin2hex(random_bytes(3))); // 6 character codes
        }

        $this->two_factor_backup_codes = $codes;
        $this->save();

        return $codes;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStaff(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Check if user is banned
     */
    public function isBanned(): bool
    {
        return !is_null($this->banned_at);
    }
}
