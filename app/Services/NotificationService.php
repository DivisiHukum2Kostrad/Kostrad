<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Perkara;
use App\Models\DokumenPerkara;
use App\Mail\CaseAssignedMail;
use App\Mail\StatusChangedMail;
use App\Mail\DocumentUploadedMail;
use App\Mail\DeadlineReminderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send case assigned notification
     */
    public function sendCaseAssigned(User $recipient, Perkara $perkara, User $assignedBy): void
    {
        try {
            // Create in-app notification
            $notification = Notification::create([
                'user_id' => $recipient->id,
                'type' => 'case_assigned',
                'subject' => 'Perkara Baru Ditugaskan',
                'message' => "Anda telah ditugaskan untuk menangani perkara: {$perkara->nama}",
                'data' => [
                    'perkara_id' => $perkara->id,
                    'perkara_nama' => $perkara->nama,
                    'perkara_nomor' => $perkara->nomor_perkara,
                    'assigned_by' => $assignedBy->name,
                    'assigned_by_id' => $assignedBy->id,
                ],
            ]);

            // Send email if user wants it
            $this->sendEmailIfEnabled($recipient, 'email_case_assigned', function () use ($recipient, $perkara, $assignedBy, $notification) {
                Mail::to($recipient->email)->send(new CaseAssignedMail($perkara, $assignedBy));
                $notification->markAsEmailed();
            });
        } catch (\Exception $e) {
            Log::error('Failed to send case assigned notification', [
                'recipient_id' => $recipient->id,
                'perkara_id' => $perkara->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send status changed notification
     */
    public function sendStatusChanged(User $recipient, Perkara $perkara, string $oldStatus, string $newStatus, User $changedBy): void
    {
        try {
            // Create in-app notification
            $notification = Notification::create([
                'user_id' => $recipient->id,
                'type' => 'status_changed',
                'subject' => 'Status Perkara Berubah',
                'message' => "Status perkara {$perkara->nama} berubah dari {$oldStatus} menjadi {$newStatus}",
                'data' => [
                    'perkara_id' => $perkara->id,
                    'perkara_nama' => $perkara->nama,
                    'perkara_nomor' => $perkara->nomor_perkara,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'changed_by' => $changedBy->name,
                    'changed_by_id' => $changedBy->id,
                ],
            ]);

            // Send email if user wants it
            $this->sendEmailIfEnabled($recipient, 'email_status_changed', function () use ($recipient, $perkara, $oldStatus, $newStatus, $changedBy, $notification) {
                Mail::to($recipient->email)->send(new StatusChangedMail($perkara, $oldStatus, $newStatus, $changedBy));
                $notification->markAsEmailed();
            });
        } catch (\Exception $e) {
            Log::error('Failed to send status changed notification', [
                'recipient_id' => $recipient->id,
                'perkara_id' => $perkara->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send document uploaded notification
     */
    public function sendDocumentUploaded(User $recipient, DokumenPerkara $document, Perkara $perkara, User $uploadedBy): void
    {
        try {
            // Create in-app notification
            $notification = Notification::create([
                'user_id' => $recipient->id,
                'type' => 'document_uploaded',
                'subject' => 'Dokumen Baru Diunggah',
                'message' => "Dokumen {$document->nama_file} telah diunggah untuk perkara: {$perkara->nama}",
                'data' => [
                    'document_id' => $document->id,
                    'document_name' => $document->nama_file,
                    'perkara_id' => $perkara->id,
                    'perkara_nama' => $perkara->nama,
                    'perkara_nomor' => $perkara->nomor_perkara,
                    'uploaded_by' => $uploadedBy->name,
                    'uploaded_by_id' => $uploadedBy->id,
                ],
            ]);

            // Send email if user wants it
            $this->sendEmailIfEnabled($recipient, 'email_document_uploaded', function () use ($recipient, $document, $perkara, $uploadedBy, $notification) {
                Mail::to($recipient->email)->send(new DocumentUploadedMail($document, $perkara, $uploadedBy));
                $notification->markAsEmailed();
            });
        } catch (\Exception $e) {
            Log::error('Failed to send document uploaded notification', [
                'recipient_id' => $recipient->id,
                'document_id' => $document->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send deadline reminder notification
     */
    public function sendDeadlineReminder(User $recipient, Perkara $perkara, int $daysRemaining): void
    {
        try {
            // Create in-app notification
            $notification = Notification::create([
                'user_id' => $recipient->id,
                'type' => 'deadline_reminder',
                'subject' => 'Pengingat Deadline Perkara',
                'message' => "Deadline perkara {$perkara->nama} tinggal {$daysRemaining} hari lagi",
                'data' => [
                    'perkara_id' => $perkara->id,
                    'perkara_nama' => $perkara->nama,
                    'perkara_nomor' => $perkara->nomor_perkara,
                    'days_remaining' => $daysRemaining,
                    'deadline_date' => $perkara->tanggal_perkara,
                ],
            ]);

            // Send email if user wants it
            $this->sendEmailIfEnabled($recipient, 'email_deadline_reminder', function () use ($recipient, $perkara, $daysRemaining, $notification) {
                Mail::to($recipient->email)->send(new DeadlineReminderMail($perkara, $daysRemaining));
                $notification->markAsEmailed();
            });
        } catch (\Exception $e) {
            Log::error('Failed to send deadline reminder notification', [
                'recipient_id' => $recipient->id,
                'perkara_id' => $perkara->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send email if user has enabled this notification type
     */
    private function sendEmailIfEnabled(User $user, string $preferenceKey, callable $sendCallback): void
    {
        // Get or create notification preference using firstOrCreate to avoid duplicates
        $preference = \App\Models\NotificationPreference::firstOrCreate(
            ['user_id' => $user->id],
            [
                'email_case_assigned' => true,
                'email_status_changed' => true,
                'email_document_uploaded' => true,
                'email_deadline_reminder' => true,
                'email_daily_summary' => false,
            ]
        );

        // Check if user wants this type of email
        if ($preference->$preferenceKey) {
            $sendCallback();
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification): void
    {
        $notification->markAsRead();
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead(User $user): void
    {
        $user->notifications()->whereNull('read_at')->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Get unread notifications for a user
     */
    public function getUnreadNotifications(User $user, int $limit = 10)
    {
        return $user->notifications()
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get all notifications for a user with pagination
     */
    public function getUserNotifications(User $user, int $perPage = 15)
    {
        return $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
