<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'activity',
        'description',
        'ip_address',
        'user_agent',
        'subject_id',
        'subject_type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Helper to log an activity
     */
    public static function log($activity, $description = null, $subject = null)
    {
        return self::create([
            'user_id' => auth()->id(),
            'activity' => $activity,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'subject_id' => $subject ? $subject->getKey() : null,
            'subject_type' => $subject ? get_class($subject) : null,
        ]);
    }
}
