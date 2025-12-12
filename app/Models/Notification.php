<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'subject',
        'message',
        'data',
        'is_read',
        'is_emailed',
        'read_at',
        'emailed_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'is_emailed' => 'boolean',
        'read_at' => 'datetime',
        'emailed_at' => 'datetime',
    ];

    // Relationship: Notification belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Mark as read
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    // Mark as emailed
    public function markAsEmailed()
    {
        $this->update([
            'is_emailed' => true,
            'emailed_at' => now(),
        ]);
    }

    // Get icon based on type
    public function getIconAttribute()
    {
        return match($this->type) {
            'case_assigned' => 'fa-user-plus text-blue-600',
            'status_changed' => 'fa-exchange-alt text-green-600',
            'document_uploaded' => 'fa-file-upload text-purple-600',
            'deadline_reminder' => 'fa-clock text-red-600',
            default => 'fa-bell text-gray-600',
        };
    }

    // Get color class based on type
    public function getColorClassAttribute()
    {
        return match($this->type) {
            'case_assigned' => 'bg-blue-100',
            'status_changed' => 'bg-green-100',
            'document_uploaded' => 'bg-purple-100',
            'deadline_reminder' => 'bg-red-100',
            default => 'bg-gray-100',
        };
    }
}
