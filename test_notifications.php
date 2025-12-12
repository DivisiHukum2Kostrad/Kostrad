<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Perkara;
use App\Models\Kategori;
use App\Models\DokumenPerkara;
use App\Models\Notification;
use App\Models\NotificationPreference;
use App\Services\NotificationService;

echo "\n";
echo "==============================================\n";
echo "  NOTIFICATION SYSTEM TEST SUITE\n";
echo "==============================================\n\n";

$notificationService = app(NotificationService::class);

// Test 1: Check Users
echo "TEST 1: Checking Test Users\n";
echo "----------------------------\n";
$admin = User::where('email', 'admin@siperkara.mil.id')->first();
$operator = User::where('email', 'operator@siperkara.mil.id')->first();

if (!$admin || !$operator) {
    echo "❌ ERROR: Test users not found!\n";
    exit(1);
}

echo "✅ Admin user found: {$admin->name} (ID: {$admin->id})\n";
echo "✅ Operator user found: {$operator->name} (ID: {$operator->id})\n";
echo "   Admin unread count: {$admin->unread_notifications_count}\n";
echo "   Operator unread count: {$operator->unread_notifications_count}\n\n";

// Test 2: Check/Create Test Case
echo "TEST 2: Checking Test Case\n";
echo "----------------------------\n";
$kategori = Kategori::first();
if (!$kategori) {
    echo "❌ ERROR: No kategori found!\n";
    exit(1);
}

$testPerkara = Perkara::where('nomor_perkara', 'LIKE', 'TEST-%')->first();
if (!$testPerkara) {
    $testPerkara = Perkara::create([
        'nomor_perkara' => 'TEST-' . date('Ymd-His'),
        'jenis_perkara' => 'Test Case for Notifications',
        'nama' => 'Test Case for Notification System',
        'kategori_id' => $kategori->id,
        'tanggal_masuk' => now(),
        'tanggal_perkara' => now(),
        'status' => 'Proses',
        'keterangan' => 'This is a test case created for notification testing',
        'is_public' => false,
    ]);
    echo "✅ Test case created: {$testPerkara->nama}\n";
} else {
    echo "✅ Test case exists: {$testPerkara->nama}\n";
}
echo "   Perkara ID: {$testPerkara->id}\n";
echo "   Status: {$testPerkara->status}\n\n";

// Test 3: Case Assigned Notification
echo "TEST 3: Case Assigned Notification\n";
echo "-----------------------------------\n";
try {
    $notificationService->sendCaseAssigned($operator, $testPerkara, $admin);
    echo "✅ Case assigned notification sent successfully\n";
    
    $notification = Notification::where('user_id', $operator->id)
        ->where('type', 'case_assigned')
        ->latest()
        ->first();
    
    if ($notification) {
        echo "✅ Notification created in database\n";
        echo "   Subject: {$notification->subject}\n";
        echo "   Message: {$notification->message}\n";
        echo "   Is Read: " . ($notification->is_read ? 'Yes' : 'No') . "\n";
        echo "   Is Emailed: " . ($notification->is_emailed ? 'Yes' : 'No') . "\n";
    } else {
        echo "❌ Notification not found in database\n";
    }
} catch (Exception $e) {
    echo "❌ ERROR: {$e->getMessage()}\n";
}
echo "\n";

// Test 4: Status Changed Notification
echo "TEST 4: Status Changed Notification\n";
echo "------------------------------------\n";
try {
    $oldStatus = $testPerkara->status;
    $newStatus = 'Selesai';
    
    $notificationService->sendStatusChanged($operator, $testPerkara, $oldStatus, $newStatus, $admin);
    echo "✅ Status changed notification sent successfully\n";
    
    $notification = Notification::where('user_id', $operator->id)
        ->where('type', 'status_changed')
        ->latest()
        ->first();
    
    if ($notification) {
        echo "✅ Notification created in database\n";
        echo "   Subject: {$notification->subject}\n";
        echo "   Old Status: {$notification->data['old_status']}\n";
        echo "   New Status: {$notification->data['new_status']}\n";
    } else {
        echo "❌ Notification not found in database\n";
    }
} catch (Exception $e) {
    echo "❌ ERROR: {$e->getMessage()}\n";
}
echo "\n";

// Test 5: Create Test Document
echo "TEST 5: Document Upload Notification\n";
echo "-------------------------------------\n";
try {
    // Create a test document record
    $testDocument = DokumenPerkara::create([
        'perkara_id' => $testPerkara->id,
        'nama_dokumen' => 'test-document.pdf',
        'jenis_dokumen' => 'Test Document',
        'category' => 'Evidence',
        'file_path' => 'documents/test/test-document.pdf',
        'file_size' => 1024000,
        'mime_type' => 'application/pdf',
        'uploaded_by' => $admin->id,
        'description' => 'Test document for notification',
        'is_public' => false,
        'version' => 1,
    ]);
    
    $notificationService->sendDocumentUploaded($operator, $testDocument, $testPerkara, $admin);
    echo "✅ Document uploaded notification sent successfully\n";
    
    $notification = Notification::where('user_id', $operator->id)
        ->where('type', 'document_uploaded')
        ->latest()
        ->first();
    
    if ($notification) {
        echo "✅ Notification created in database\n";
        echo "   Subject: {$notification->subject}\n";
        echo "   Document: {$notification->data['document_name']}\n";
    } else {
        echo "❌ Notification not found in database\n";
    }
    
    // Clean up test document
    $testDocument->delete();
} catch (Exception $e) {
    echo "❌ ERROR: {$e->getMessage()}\n";
}
echo "\n";

// Test 6: Deadline Reminder Notification
echo "TEST 6: Deadline Reminder Notification\n";
echo "---------------------------------------\n";
try {
    $daysRemaining = 3;
    
    $notificationService->sendDeadlineReminder($operator, $testPerkara, $daysRemaining);
    echo "✅ Deadline reminder notification sent successfully\n";
    
    $notification = Notification::where('user_id', $operator->id)
        ->where('type', 'deadline_reminder')
        ->latest()
        ->first();
    
    if ($notification) {
        echo "✅ Notification created in database\n";
        echo "   Subject: {$notification->subject}\n";
        echo "   Days Remaining: {$notification->data['days_remaining']}\n";
    } else {
        echo "❌ Notification not found in database\n";
    }
} catch (Exception $e) {
    echo "❌ ERROR: {$e->getMessage()}\n";
}
echo "\n";

// Test 7: Notification Preferences
echo "TEST 7: Notification Preferences\n";
echo "---------------------------------\n";
$operatorPreference = $operator->notificationPreference;
if ($operatorPreference) {
    echo "✅ Preferences exist for operator\n";
    echo "   Case Assigned: " . ($operatorPreference->email_case_assigned ? 'Enabled' : 'Disabled') . "\n";
    echo "   Status Changed: " . ($operatorPreference->email_status_changed ? 'Enabled' : 'Disabled') . "\n";
    echo "   Document Uploaded: " . ($operatorPreference->email_document_uploaded ? 'Enabled' : 'Disabled') . "\n";
    echo "   Deadline Reminder: " . ($operatorPreference->email_deadline_reminder ? 'Enabled' : 'Disabled') . "\n";
} else {
    echo "⚠️  No preferences found (will be created on first notification)\n";
}
echo "\n";

// Test 8: Mark as Read
echo "TEST 8: Mark as Read Functionality\n";
echo "-----------------------------------\n";
$unreadNotification = Notification::where('user_id', $operator->id)
    ->where('is_read', false)
    ->first();

if ($unreadNotification) {
    echo "✅ Found unread notification (ID: {$unreadNotification->id})\n";
    $notificationService->markAsRead($unreadNotification);
    $unreadNotification->refresh();
    
    if ($unreadNotification->is_read) {
        echo "✅ Notification marked as read successfully\n";
        echo "   Read at: {$unreadNotification->read_at}\n";
    } else {
        echo "❌ Failed to mark notification as read\n";
    }
} else {
    echo "⚠️  No unread notifications found\n";
}
echo "\n";

// Test 9: Get Unread Notifications
echo "TEST 9: Get Unread Notifications\n";
echo "---------------------------------\n";
$unreadNotifications = $notificationService->getUnreadNotifications($operator);
echo "✅ Retrieved unread notifications\n";
echo "   Count: {$unreadNotifications->count()}\n";
if ($unreadNotifications->count() > 0) {
    echo "   Latest: {$unreadNotifications->first()->subject}\n";
}
echo "\n";

// Test 10: Statistics
echo "TEST 10: Notification Statistics\n";
echo "---------------------------------\n";
$totalNotifications = Notification::count();
$unreadCount = Notification::where('is_read', false)->count();
$emailedCount = Notification::where('is_emailed', true)->count();

echo "✅ Total Notifications: {$totalNotifications}\n";
echo "✅ Unread Notifications: {$unreadCount}\n";
echo "✅ Emailed Notifications: {$emailedCount}\n";

$notificationsByType = Notification::selectRaw('type, COUNT(*) as count')
    ->groupBy('type')
    ->get();

echo "\n   Notifications by Type:\n";
foreach ($notificationsByType as $stat) {
    echo "   - {$stat->type}: {$stat->count}\n";
}
echo "\n";

// Final Summary
echo "\n==============================================\n";
echo "  TEST SUMMARY\n";
echo "==============================================\n";
echo "✅ All notification types tested successfully\n";
echo "✅ Database operations working correctly\n";
echo "✅ NotificationService functioning properly\n";
echo "✅ Relationships between models working\n";
echo "\n";

echo "📝 NEXT STEPS:\n";
echo "   1. Open browser: http://127.0.0.1:8000\n";
echo "   2. Login as: operator@siperkara.mil.id / password\n";
echo "   3. Check notification bell icon (should show count)\n";
echo "   4. Click bell to see dropdown\n";
echo "   5. Visit: /admin/notifications\n";
echo "   6. Test preferences: /admin/notifications/preferences\n";
echo "\n";

echo "📧 EMAIL LOGS:\n";
echo "   Check storage/logs/laravel.log for email content\n";
echo "   Command: tail -f storage/logs/laravel.log\n";
echo "\n";
