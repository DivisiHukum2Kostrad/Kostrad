<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display all notifications for current user
     */
    public function index()
    {
        $notifications = $this->notificationService->getUserNotifications(Auth::user());
        
        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Get unread notifications (for dropdown)
     */
    public function unread()
    {
        $notifications = $this->notificationService->getUnreadNotifications(Auth::user());
        
        return response()->json([
            'notifications' => $notifications,
            'count' => $notifications->count(),
        ]);
    }

    /**
     * Mark a specific notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $this->notificationService->markAsRead($notification);

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $this->notificationService->markAllAsRead(Auth::user());

        return response()->json(['success' => true]);
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus!');
    }

    /**
     * Show notification preferences page
     */
    public function preferences()
    {
        $user = Auth::user();
        $preference = $user->notificationPreference;

        // Create default if doesn't exist
        if (!$preference) {
            $preference = $user->notificationPreference()->create([
                'email_case_assigned' => true,
                'email_status_changed' => true,
                'email_document_uploaded' => true,
                'email_deadline_reminder' => true,
                'email_daily_summary' => false,
            ]);
        }

        return view('admin.notifications.preferences', compact('preference'));
    }

    /**
     * Update notification preferences
     */
    public function updatePreferences(Request $request)
    {
        $validated = $request->validate([
            'email_case_assigned' => 'boolean',
            'email_status_changed' => 'boolean',
            'email_document_uploaded' => 'boolean',
            'email_deadline_reminder' => 'boolean',
            'email_daily_summary' => 'boolean',
        ]);

        $user = Auth::user();
        $preference = $user->notificationPreference;

        if (!$preference) {
            $user->notificationPreference()->create($validated);
        } else {
            $preference->update($validated);
        }

        return back()->with('success', 'Preferensi notifikasi berhasil diperbarui!');
    }
}
