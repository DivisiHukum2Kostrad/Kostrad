<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perkara extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'perkaras';

    protected $fillable = [
        'nomor_perkara',
        'jenis_perkara',
        'nama',
        'deskripsi',
        'kategori_id',
        'tanggal_masuk',
        'tanggal_perkara',
        'tanggal_selesai',
        'status',
        'priority',
        'deadline',
        'progress',
        'estimated_days',
        'assigned_to',
        'keterangan',
        'internal_notes',
        'tags',
        'file_dokumentasi',
        'is_public',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_perkara' => 'date',
        'tanggal_selesai' => 'date',
        'deadline' => 'date',
        'is_public' => 'boolean',
        'tags' => 'array',
        'progress' => 'integer',
        'estimated_days' => 'integer',
    ];

    // Relationship: Perkara belongs to Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // Relationship: Perkara has many Personel (Many-to-Many)
    public function personels()
    {
        return $this->belongsToMany(Personel::class, 'perkara_personel')
                    ->withPivot('peran')
                    ->withTimestamps();
    }

    // Relationship: Perkara has many Dokumen
    public function dokumens()
    {
        return $this->hasMany(DokumenPerkara::class, 'perkara_id');
    }

    // Relationship: Perkara has many Riwayat
    public function riwayats()
    {
        return $this->hasMany(RiwayatPerkara::class, 'perkara_id');
    }

    // Scope: Hanya data publik
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    // Scope: Hanya status Selesai
    public function scopeSelesai($query)
    {
        return $query->where('status', 'Selesai');
    }

    // Scope: Hanya status Proses
    public function scopeProses($query)
    {
        return $query->where('status', 'Proses');
    }

    // Accessor: Get badge color berdasarkan status
    public function getStatusBadgeAttribute()
    {
        return $this->status === 'Selesai'
            ? 'bg-green-100 text-green-800'
            : 'bg-yellow-100 text-yellow-800';
    }

    // Accessor: Get kategori badge color
    public function getKategoriBadgeAttribute()
    {
        $colors = [
            'Disiplin' => 'bg-purple-100 text-purple-800',
            'Administrasi' => 'bg-blue-100 text-blue-800',
            'Pidana' => 'bg-red-100 text-red-800',
        ];

        return $colors[$this->kategori->nama] ?? 'bg-gray-100 text-gray-800';
    }

    // Method: Auto generate nomor perkara
    public static function generateNomorPerkara()
    {
        $year = date('Y');
        $lastPerkara = self::whereYear('created_at', $year)
                          ->orderBy('id', 'desc')
                          ->first();

        $nextNumber = $lastPerkara
            ? (int) substr($lastPerkara->nomor_perkara, -3) + 1
            : 1;

        return sprintf('PERK/DIV2/%s/%03d', $year, $nextNumber);
    }

    // Accessor: Priority badge
    public function getPriorityBadgeAttribute()
    {
        $badges = [
            'Low' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">🟢 Rendah</span>',
            'Medium' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">🔵 Sedang</span>',
            'High' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">🟠 Tinggi</span>',
            'Urgent' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">🔴 Mendesak</span>',
        ];

        return $badges[$this->priority] ?? $badges['Medium'];
    }

    // Accessor: Priority color class
    public function getPriorityColorAttribute()
    {
        $colors = [
            'Low' => 'text-gray-600',
            'Medium' => 'text-blue-600',
            'High' => 'text-orange-600',
            'Urgent' => 'text-red-600',
        ];

        return $colors[$this->priority] ?? 'text-gray-600';
    }

    // Accessor: Progress badge
    public function getProgressBadgeAttribute()
    {
        $progress = $this->progress;
        
        if ($progress >= 75) {
            $color = 'bg-green-500';
        } elseif ($progress >= 50) {
            $color = 'bg-blue-500';
        } elseif ($progress >= 25) {
            $color = 'bg-yellow-500';
        } else {
            $color = 'bg-gray-400';
        }

        return "<div class='w-full bg-gray-200 rounded-full h-2'>
                    <div class='{$color} h-2 rounded-full' style='width: {$progress}%'></div>
                </div>
                <span class='text-xs text-gray-600 mt-1'>{$progress}%</span>";
    }

    // Accessor: Days until deadline
    public function getDaysUntilDeadlineAttribute()
    {
        if (!$this->deadline) {
            return null;
        }

        $now = now()->startOfDay();
        $deadline = $this->deadline->startOfDay();
        
        return $now->diffInDays($deadline, false);
    }

    // Accessor: Deadline status
    public function getDeadlineStatusAttribute()
    {
        $days = $this->days_until_deadline;

        if ($days === null) {
            return null;
        }

        if ($days < 0) {
            return 'overdue';
        } elseif ($days <= 3) {
            return 'urgent';
        } elseif ($days <= 7) {
            return 'warning';
        }

        return 'normal';
    }

    // Accessor: Deadline badge
    public function getDeadlineBadgeAttribute()
    {
        $days = $this->days_until_deadline;

        if ($days === null) {
            return '<span class="text-xs text-gray-500">Tidak ada deadline</span>';
        }

        if ($days < 0) {
            return sprintf(
                '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">⏰ Terlambat %d hari</span>',
                abs($days)
            );
        } elseif ($days === 0) {
            return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">⏰ Hari ini!</span>';
        } elseif ($days <= 3) {
            return sprintf(
                '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">⚠️ %d hari lagi</span>',
                $days
            );
        } elseif ($days <= 7) {
            return sprintf(
                '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">📅 %d hari lagi</span>',
                $days
            );
        }

        return sprintf(
            '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">✓ %d hari lagi</span>',
            $days
        );
    }

    // Scope: Filter by priority
    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // Scope: Urgent cases
    public function scopeUrgent($query)
    {
        return $query->where('priority', 'Urgent');
    }

    // Scope: High priority
    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['High', 'Urgent']);
    }

    // Scope: Overdue cases
    public function scopeOverdue($query)
    {
        return $query->whereNotNull('deadline')
                    ->where('deadline', '<', now())
                    ->where('status', '!=', 'Selesai');
    }

    // Scope: Upcoming deadline
    public function scopeUpcomingDeadline($query, $days = 7)
    {
        return $query->whereNotNull('deadline')
                    ->whereBetween('deadline', [now(), now()->addDays($days)])
                    ->where('status', '!=', 'Selesai');
    }

    // Scope: Assigned to specific person
    public function scopeAssignedTo($query, $assignee)
    {
        return $query->where('assigned_to', $assignee);
    }

    // Check if case is overdue
    public function isOverdue()
    {
        return $this->deadline && 
               $this->deadline->isPast() && 
               $this->status !== 'Selesai';
    }

    // Check if deadline is approaching (within 7 days)
    public function isDeadlineApproaching()
    {
        return $this->deadline && 
               $this->deadline->isFuture() &&
               $this->deadline->diffInDays(now()) <= 7 &&
               $this->status !== 'Selesai';
    }
}
