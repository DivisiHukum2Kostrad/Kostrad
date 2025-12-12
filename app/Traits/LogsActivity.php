<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('created', 'Dibuat');
        });

        static::updated(function ($model) {
            if ($model->wasChanged()) {
                $changes = $model->getChanges();
                $original = $model->getOriginal();

                // Check if status changed
                if (isset($changes['status']) && isset($original['status'])) {
                    $model->logActivity(
                        'status_changed',
                        "Status diubah dari '{$original['status']}' menjadi '{$changes['status']}'"
                    );
                } else {
                    $model->logActivity('updated', 'Diperbarui');
                }
            }
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted', 'Dihapus');
        });
    }

    public function logActivity($type, $description, $metadata = null)
    {
        $changes = $this->wasChanged() ? $this->getChanges() : [];
        $original = $this->getOriginal();

        ActivityLog::create([
            'log_type' => $type,
            'loggable_type' => get_class($this),
            'loggable_id' => $this->id,
            'user_id' => Auth::id(),
            'description' => $description,
            'old_values' => !empty($original) ? $original : null,
            'new_values' => !empty($changes) ? $changes : ($this->exists ? $this->toArray() : null),
            'metadata' => $metadata,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'loggable')->latest();
    }
}
