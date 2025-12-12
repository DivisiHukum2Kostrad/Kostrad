# Role-Based Access Control (RBAC) - Testing Guide

## 🎯 Overview

This guide explains how to test the newly implemented Role-Based Access Control (RBAC) system in SIPERKARA DIV-2.

## 📋 Features Implemented

### 1. **User Management Interface**

-   ✅ User listing with search and filters
-   ✅ Create new users with role assignment
-   ✅ Edit existing users
-   ✅ Delete users (with self-deletion prevention)
-   ✅ View user details and activity logs

### 2. **Role-Based Permissions**

Two roles are supported:

#### **Admin** (Full Access)

-   ✅ View Cases
-   ✅ Manage Cases
-   ✅ Delete Cases
-   ✅ Manage Documents
-   ✅ Manage History
-   ✅ Manage Categories
-   ✅ Manage Personnel
-   ✅ View Statistics
-   ✅ Export Data
-   ✅ **Manage Users** (Admin only)
-   ✅ **View Activity Logs** (Admin only)

#### **Operator** (Limited Access)

-   ✅ View Cases
-   ✅ Manage Cases
-   ✅ Manage Documents
-   ✅ Manage History
-   ✅ View Statistics
-   ✅ Export Data
-   ❌ Cannot manage users
-   ❌ Cannot view activity logs
-   ❌ Cannot delete cases

### 3. **Middleware Protection**

Routes are protected with `role:admin` middleware:

```php
// Admin-only routes
- GET/POST admin/users/*
- GET admin/activity-logs/*
```

### 4. **UI Enhancements**

-   ✅ Role badges displayed in navigation
-   ✅ Conditional menu items based on permissions
-   ✅ Permission indicators on user profile
-   ✅ Visual role representation (Admin = Red badge, Operator = Blue badge)

## 🧪 Testing Instructions

### Test 1: Admin Access

**Login Credentials:**

-   Email: `admin@siperkara.mil.id`
-   Password: `password`

**Expected Behavior:**

1. ✅ Can see "User" menu in navigation
2. ✅ Can see "Log Aktivitas" menu in navigation
3. ✅ Can access `/admin/users` - User management page
4. ✅ Can create new users
5. ✅ Can edit any user
6. ✅ Can delete users (except self)
7. ✅ Can view activity logs
8. ✅ Role badge shows "Admin" in red

**Steps:**

```bash
# 1. Open browser and go to: http://127.0.0.1:8000/admin/dashboard
# 2. Login with admin credentials
# 3. Check navigation - should see "User" and "Log Aktivitas" menus
# 4. Click "User" menu
# 5. Try creating a new user
# 6. Try editing an existing user
# 7. Try viewing user details
```

### Test 2: Operator Access

**Login Credentials:**

-   Email: `operator@siperkara.mil.id`
-   Password: `password`

**Expected Behavior:**

1. ❌ Cannot see "User" menu in navigation (hidden)
2. ❌ Cannot see "Log Aktivitas" menu in navigation (hidden)
3. ❌ Cannot access `/admin/users` directly (should get 403 or redirect)
4. ❌ Cannot access `/admin/activity-logs` directly (should get 403 or redirect)
5. ✅ Can still access dashboard
6. ✅ Can manage perkara (cases)
7. ✅ Can manage personel
8. ✅ Can export data
9. ✅ Role badge shows "Operator" in blue

**Steps:**

```bash
# 1. Logout from admin
# 2. Login with operator credentials
# 3. Check navigation - should NOT see "User" and "Log Aktivitas" menus
# 4. Try accessing: http://127.0.0.1:8000/admin/users (should be blocked)
# 5. Try accessing: http://127.0.0.1:8000/admin/activity-logs (should be blocked)
# 6. Verify can still access perkara management
```

### Test 3: User Management Operations

**As Admin:**

#### Create User

1. Click "User" menu → "Tambah User"
2. Fill form:
    - Name: `Test User`
    - Email: `test@siperkara.mil.id`
    - Role: `Operator`
    - Password: `password123`
    - Confirm Password: `password123`
3. Click "Simpan User"
4. ✅ Should redirect to user list with success message
5. ✅ New user should appear in the list

#### Edit User

1. Click edit icon on any user
2. Change name or role
3. Leave password fields blank (should not change password)
4. Click "Simpan Perubahan"
5. ✅ Should redirect with success message
6. ✅ Changes should be reflected

#### Delete User

1. Click delete icon on a user (not yourself)
2. Confirm deletion
3. ✅ User should be removed
4. Try deleting yourself
5. ✅ Should show error message preventing self-deletion

#### View User Details

1. Click eye icon on any user
2. ✅ Should show user profile with:
    - Avatar
    - Role badge
    - Email and join date
    - Permission checklist
    - Recent activity logs
3. ✅ Permission indicators should match role

### Test 4: Search and Filters

1. Go to User management page
2. Test search by name: Enter partial name
3. Test search by email: Enter partial email
4. Test role filter: Select "Admin" or "Operator"
5. Test combined filters
6. Click "Reset" button
7. ✅ All filters should work correctly

### Test 5: Permission Checks in Blade Templates

Check navigation visibility:

-   Admin login: Should see all menu items
-   Operator login: Should NOT see "User" and "Log Aktivitas"

Check buttons/actions:

-   User index page: "Tambah User" button only visible to admin
-   User detail page: Edit/Delete buttons only visible to admin
-   Activity log routes: Only accessible to admin

## 📁 Files Modified/Created

### Controllers

-   ✅ `app/Http/Controllers/Admin/UserController.php` - Full CRUD implementation

### Models

-   ✅ `app/Models/User.php` - Added `hasPermission()` and `getRoleBadgeAttribute()` methods

### Middleware

-   ✅ `app/Http/Middleware/CheckRole.php` - Already existed, verified functionality

### Views

-   ✅ `resources/views/admin/users/index.blade.php` - User listing with filters
-   ✅ `resources/views/admin/users/create.blade.php` - Create user form
-   ✅ `resources/views/admin/users/edit.blade.php` - Edit user form
-   ✅ `resources/views/admin/users/show.blade.php` - User detail page
-   ✅ `resources/views/admin/layout.blade.php` - Updated navigation with permission checks

### Routes

-   ✅ `routes/web.php` - Added user routes with `role:admin` middleware

## 🔒 Security Features

1. **Self-Deletion Prevention**: Users cannot delete their own account
2. **Route Protection**: Middleware ensures only admins can access user management
3. **Password Hashing**: All passwords are bcrypt hashed
4. **Optional Password Update**: Editing user doesn't require password unless changing it
5. **Email Uniqueness**: Duplicate emails are prevented
6. **Role Validation**: Only `admin` and `operator` roles are accepted

## 📊 Statistics Cards

User management page shows:

-   Total Users count
-   Admin count
-   Operator count

## 🎨 UI/UX Features

-   Role badges with color coding (Admin = Red, Operator = Blue)
-   FontAwesome icons for visual clarity
-   Responsive design with Tailwind CSS
-   Success/error messages with animations
-   Confirmation dialogs for deletions
-   Breadcrumb navigation
-   Activity timeline on user detail page

## 🚀 Next Steps

After confirming RBAC works correctly, continue with:

-   ✅ Feature #4: Role-Based Access Control (COMPLETED)
-   ⏳ Feature #5: Advanced Document Management
-   ⏳ Feature #6: Email Notifications System
-   ⏳ Feature #7: Enhanced Case Features (Priority, Deadlines)
-   ⏳ Feature #8: RESTful API with Documentation
-   ⏳ Feature #9: UI/UX Improvements (Dark Mode, Real-time)

## ⚠️ Important Notes

-   The `can()` method name was changed to `hasPermission()` to avoid conflict with Laravel's built-in `can()` method for policies
-   Middleware alias `role` is already registered in `bootstrap/app.php`
-   Activity logs automatically track user actions through the `LogsActivity` trait
-   User deletion is soft-deleted if the User model uses `SoftDeletes` trait

## 📞 Support

If you encounter any issues:

1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify middleware is registered
3. Clear cache: `php artisan cache:clear`
4. Clear config: `php artisan config:clear`
