<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'log_type',
        'loggable_type',
        'loggable_id',
        'user_id',
        'action',
        'description',
        'old_values',
        'new_values',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    // Relationship to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Polymorphic relationship to loggable model
    public function loggable()
    {
        return $this->morphTo();
    }

    // Get icon based on log type
    public function getIconAttribute()
    {
        return match($this->log_type) {
            'created' => '➕',
            'updated' => '✏️',
            'deleted' => '🗑️',
            'status_changed' => '🔄',
            'file_uploaded' => '📎',
            'file_deleted' => '🗂️',
            default => '📝',
        };
    }

    // Get color class based on log type
    public function getColorClassAttribute()
    {
        return match($this->log_type) {
            'created' => 'text-green-600 bg-green-50',
            'updated' => 'text-blue-600 bg-blue-50',
            'deleted' => 'text-red-600 bg-red-50',
            'status_changed' => 'text-purple-600 bg-purple-50',
            'file_uploaded' => 'text-indigo-600 bg-indigo-50',
            'file_deleted' => 'text-orange-600 bg-orange-50',
            default => 'text-gray-600 bg-gray-50',
        };
    }
}
