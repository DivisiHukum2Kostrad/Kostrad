<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationPreference extends Model
{
    protected $fillable = [
        'user_id',
        'email_case_assigned',
        'email_status_changed',
        'email_document_uploaded',
        'email_deadline_reminder',
        'email_daily_summary',
    ];

    protected $casts = [
        'email_case_assigned' => 'boolean',
        'email_status_changed' => 'boolean',
        'email_document_uploaded' => 'boolean',
        'email_deadline_reminder' => 'boolean',
        'email_daily_summary' => 'boolean',
    ];

    // Relationship: Preference belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Check if user wants email for specific type
    public function wantsEmailFor($type)
    {
        $field = 'email_' . $type;
        return $this->$field ?? false;
    }
}
