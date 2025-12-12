<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nrp',
        'pangkat',
        'jabatan',
        'role', // admin, operator
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationship: User has many Riwayat
    public function riwayats()
    {
        return $this->hasMany(RiwayatPerkara::class, 'user_id');
    }

    // Relationship: User has many Notifications
    public function notifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    // Relationship: User has one NotificationPreference
    public function notificationPreference()
    {
        return $this->hasOne(NotificationPreference::class);
    }

    // Get unread notifications count
    public function getUnreadNotificationsCountAttribute()
    {
        return $this->notifications()->where('is_read', false)->count();
    }

    // Check if user is admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Check if user is operator
    public function isOperator()
    {
        return $this->role === 'operator';
    }

    // Check if user has specific role
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    // Check if user has permission
    public function hasPermission($permission)
    {
        $permissions = [
            'admin' => [
                'view_cases',
                'manage_cases',
                'delete_cases',
                'manage_documents',
                'manage_history',
                'manage_categories',
                'manage_personnel',
                'view_statistics',
                'export_data',
                'manage_users',
                'view_logs',
            ],
            'operator' => [
                'view_cases',
                'manage_cases',
                'manage_documents',
                'manage_history',
                'view_statistics',
                'export_data',
            ],
        ];

        return in_array($permission, $permissions[$this->role] ?? []);
    }

    // Alias for compatibility
    public function userCan($permission)
    {
        return $this->hasPermission($permission);
    }

    // Get role badge HTML
    public function getRoleBadgeAttribute()
    {
        return match($this->role) {
            'admin' => '<span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">Admin</span>',
            'operator' => '<span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Operator</span>',
            default => '<span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold">Unknown</span>',
        };
    }

    // Get role display name
    public function getRoleNameAttribute()
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'operator' => 'Operator',
            default => 'Unknown',
        };
    }
}
