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
            'case_assigned' => '<i class="fas fa-user-plus"></i>',
            'status_changed' => '<i class="fas fa-exchange-alt"></i>',
            'document_uploaded' => '<i class="fas fa-file-upload"></i>',
            'deadline_reminder' => '<i class="fas fa-clock"></i>',
            'case_completed' => '<i class="fas fa-check-circle"></i>',
            default => '<i class="fas fa-bell"></i>',
        };
    }

    // Get icon color class based on type
    public function getIconColorAttribute()
    {
        return match($this->type) {
            'case_assigned' => 'text-blue-600',
            'status_changed' => 'text-green-600',
            'document_uploaded' => 'text-purple-600',
            'deadline_reminder' => 'text-red-600',
            'case_completed' => 'text-green-600',
            default => 'text-gray-600',
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
            'case_completed' => 'bg-green-100',
            default => 'bg-gray-100',
        };
    }
}
