# 🔗 Public-Admin Data Integration Guide

**Complete integration between public pages and admin panel for SiPerkara Div-2**

---

## 📊 Overview

The SiPerkara system has two main interfaces:

1. **Public Pages** - Accessible without login
2. **Admin Panel** - Requires authentication

Both interfaces are now **fully integrated** with real-time database synchronization.

---

## 🌐 Public Pages (No Login Required)

### 1. Landing Page (`/`)

**Controller:** `PublicController@landing`  
**View:** `resources/views/landing.blade.php`

**Real-Time Data Displayed:**

```php
// Statistics Box (from database)
- Total Perkara: {{ $total_perkaras }}
- Perkara Selesai: {{ $perkaras_selesai }}
- Perkara Proses: {{ $perkaras_proses }}
- Data Publik: {{ $perkaras_publik }}

// Preview Table (latest 3 public cases)
@foreach($preview_perkaras as $perkara)
    - Nomor Perkara
    - Jenis Perkara
    - Kategori (with color badge)
    - Tanggal Pendaftaran
    - Status Badge
@endforeach
```

**Features:**

-   ✅ Real-time statistics from database
-   ✅ Latest 3 public cases preview
-   ✅ Category badges with actual colors
-   ✅ Status badges (Selesai/Proses)
-   ✅ Link to full public case listing

**CTA Buttons:**

-   "Lihat Data Perkara" → Links to `/perkara`
-   "Lihat Semua Data" → Links to `/perkara`

---

### 2. Public Case Listing (`/perkara`)

**Controller:** `PublicController@perkara`  
**View:** `resources/views/perkara.blade.php`

**Real-Time Data Displayed:**

```php
// Filters (dynamic from database)
- Search: nomor_perkara, jenis_perkara, terdakwa, oditur, keterangan
- Status Filter: Selesai, Proses, All
- Klasifikasi Filter: (dynamic list from database)
- Year Filter: (available years from database)

// Table Data (paginated)
@foreach($perkaras as $perkara)
    - Number (pagination index)
    - Nomor Perkara
    - Tanggal Pendaftaran
    - Klasifikasi Perkara
    - Para Pihak (Oditur & Terdakwa arrays)
    - Status Badge
    - Lama Proses (calculated in days)
    - Detail Link
@endforeach

// Total Count
Total: {{ $total_perkaras }} data perkara
```

**Features:**

-   ✅ Advanced search with multiple fields
-   ✅ Status filtering (Selesai/Proses)
-   ✅ Klasifikasi filtering (dynamic from DB)
-   ✅ Year filtering (available years from DB)
-   ✅ Sorting options
-   ✅ Pagination (15 per page)
-   ✅ Process duration calculation
-   ✅ Oditur & Terdakwa array handling

**Filters Applied:**

-   Only shows cases with `is_public = true`
-   Paginated with query string preservation

---

### 3. Public Case Detail (`/perkara/public/{id}`)

**Controller:** `PerkaraController@showPublic`  
**View:** `resources/views/perkara/show.blade.php`

**Real-Time Data Displayed:**

```php
// Case Details (public cases only)
- Full case information
- Category with color badge
- Oditur & Terdakwa lists
- Timeline/history
- Status information
```

**Features:**

-   ✅ Only shows public cases (`is_public = true`)
-   ✅ Full case details
-   ✅ Category information
-   ✅ Related parties (Oditur & Terdakwa)

---

## 🔐 Admin Panel (Login Required)

### 1. Admin Dashboard (`/admin/dashboard`)

**Controller:** `DashboardController@index`  
**View:** `resources/views/admin/dashboard.blade.php`

**Real-Time Data Displayed:**

```php
// Statistics Cards
- Total Perkara
- Perkara Selesai
- Perkara Proses
- Perkara Bulan Ini
- Total Personel
- Total Kategori
- Completion Rate (%)
- Avg Completion Days

// Charts (Chart.js)
1. Perkara per Kategori (Pie Chart)
   - Dynamic from database
   - Category colors applied

2. Monthly Trend (Line Chart)
   - Last 6 months
   - Masuk vs Selesai

3. Status Distribution (Bar Chart)
   - Proses vs Selesai

// Recent Activities
- Latest 5 cases with details

// Top Categories
- Top 5 categories by case count

// Yearly Comparison
- Current year vs previous year
```

**Features:**

-   ✅ Real-time statistics
-   ✅ Interactive Chart.js visualizations
-   ✅ Monthly trend analysis
-   ✅ Yearly comparison
-   ✅ Recent activities feed
-   ✅ Dark mode support

---

### 2. Admin Case Management (`/admin/perkaras`)

**Controller:** `PerkaraController@index`  
**View:** `resources/views/admin/perkaras/index.blade.php`

**Real-Time Data Displayed:**

```php
// Advanced Filters
- Search (9 fields)
- Status (Proses/Selesai)
- Category (dynamic dropdown)
- Priority (Urgent/High/Medium/Low)
- Deadline Status
- Assigned To (users)
- Visibility (Public/Private)
- Date Range

// Table Data (paginated)
@foreach($perkaras as $perkara)
    - Nomor Perkara + Nama
    - Jenis Perkara + Assigned To
    - Priority Badge
    - Deadline Badge + Date
    - Progress Bar (%)
    - Status Badge
    - Actions (View/Edit/Delete)
@endforeach
```

**Features:**

-   ✅ Advanced filtering (9 filters)
-   ✅ Sorting (4 options)
-   ✅ Pagination (15 per page)
-   ✅ Export to Excel/PDF
-   ✅ Progress bars
-   ✅ Priority badges
-   ✅ Deadline warnings
-   ✅ Dark mode support
-   ✅ CRUD operations

---

### 3. Admin Case Edit (`/admin/perkaras/{id}/edit`)

**Controller:** `PerkaraController@edit`  
**View:** `resources/views/admin/perkaras/edit.blade.php`

**Data Handling:**

```php
// Dynamic Arrays
- Oditur[] (with add/remove buttons)
- Terdakwa[] (with add/remove buttons)

// Proper JSON Handling
@php
    // Handle both JSON string and array
    $oditurData = $perkara->oditur ?? [];
    if (is_string($oditurData)) {
        $oditurData = json_decode($oditurData, true) ?: [];
    }
    $oditurList = old('oditur', $oditurData);
@endphp
```

**Features:**

-   ✅ Fixed foreach() error with JSON handling
-   ✅ Dynamic field addition/removal
-   ✅ Proper array handling for oditur/terdakwa
-   ✅ All fields populated from database
-   ✅ Category dropdown (from database)
-   ✅ Personnel selection (from database)

---

## 🔄 Data Flow

### Public → Database

```
Public Pages (Read-Only)
    ↓
Query: where('is_public', true)
    ↓
Display filtered data
```

### Admin → Database → Public

```
Admin Creates/Updates Case
    ↓
Save to Database (with is_public flag)
    ↓
If is_public = true
    ↓
Automatically appears on Public Pages
```

### Real-Time Synchronization

```
Admin Action              Public Impact
──────────────────────────────────────────
Create case (public)   → Appears on landing & listing
Update case (public)   → Updates instantly
Set is_public = false  → Removed from public view
Set is_public = true   → Appears on public view
Complete case          → Updates statistics
```

---

## 🗄️ Database Integration Points

### 1. Perkaras Table

```sql
Key Fields for Public Display:
- id (primary)
- nomor_perkara
- jenis_perkara
- nama (case name)
- kategori_id (FK)
- oditur (JSON array)
- terdakwa (JSON array)
- tanggal_pendaftaran
- tanggal_masuk
- tanggal_selesai
- klasifikasi_perkara
- status (Proses/Selesai)
- is_public (boolean) ← KEY FILTER
- priority (Urgent/High/Medium/Low)
- progress (0-100%)
- deadline (date)
```

### 2. Kategoris Table

```sql
Fields Used:
- id (primary)
- nama (category name)
- kode (category code)
- color (hex color for badges)
```

### 3. Relationships

```php
// Perkara Model
public function kategori() {
    return $this->belongsTo(Kategori::class);
}

// Kategori Model
public function perkaras() {
    return $this->hasMany(Perkara::class);
}
```

---

## ✅ Data Validation & Security

### Public Pages Security

```php
// Only show public cases
Perkara::where('is_public', true)->get();

// Cannot access private cases
if (!$perkara->is_public) {
    abort(404); // Not found
}
```

### Admin Panel Security

```php
// Middleware: auth
Route::group(['middleware' => 'auth'], function() {
    // Full CRUD access
    // Can toggle is_public
    // Can see all cases (public + private)
});
```

---

## 🎨 UI/UX Integration

### Consistent Styling

**Category Badges:**

```blade
<!-- Public Page -->
<span class="px-2 py-1 text-xs font-semibold rounded"
      style="background-color: {{ $perkara->kategori->color }}33;
             color: {{ $perkara->kategori->color }}">
    {{ $perkara->kategori->nama }}
</span>

<!-- Admin Page -->
<!-- Same styling maintained -->
```

**Status Badges:**

```blade
<!-- Uses status_badge accessor from Perkara model -->
<span class="{{ $perkara->status_badge }}">
    {{ $perkara->status }}
</span>
```

### Dark Mode Support

**Admin Panel:**

-   ✅ Dashboard (full dark mode)
-   ✅ Case listing (full dark mode)
-   ✅ Case edit form (full dark mode)
-   ✅ User management (full dark mode)
-   ✅ Activity logs (full dark mode)
-   ✅ Dark mode toggle with localStorage
-   ✅ Keyboard shortcut (Ctrl+/)

**Public Pages:**

-   Static green theme (no dark mode needed)
-   Optimized for readability

---

## 📊 Statistics Synchronization

### Landing Page Statistics

```php
PublicController@landing:
- $total_perkaras = Perkara::count()
- $perkaras_selesai = Perkara::where('status', 'Selesai')->count()
- $perkaras_proses = Perkara::where('status', 'Proses')->count()
- $perkaras_publik = Perkara::where('is_public', true)->count()
```

### Dashboard Statistics

```php
DashboardController@index:
- total_perkara = Perkara::count()
- perkara_selesai = Perkara::selesai()->count()
- perkara_proses = Perkara::proses()->count()
- perkara_bulan_ini = (current month count)
- completion_rate = (calculated percentage)
- avg_completion_days = (calculated average)
```

### Real-Time Updates

All statistics are calculated on each page load:

-   ✅ No caching of statistics
-   ✅ Always shows current data
-   ✅ Admin changes reflect immediately on public pages

---

## 🔧 Technical Details

### JSON Field Handling

**Problem:** Oditur and Terdakwa stored as JSON strings  
**Solution:** Proper decoding in blade templates

```blade
@php
    // Handle both JSON string and array
    $oditurData = $perkara->oditur ?? [];
    if (is_string($oditurData)) {
        $oditurData = json_decode($oditurData, true) ?: [];
    }
    $oditurList = old('oditur', $oditurData);
    if (empty($oditurList)) $oditurList = [''];
@endphp

@foreach($oditurList as $index => $oditur)
    <!-- Display/edit field -->
@endforeach
```

### Model Casts

```php
// Perkara Model
protected $casts = [
    'oditur' => 'array',
    'terdakwa' => 'array',
    'tags' => 'array',
    'is_public' => 'boolean',
    'progress' => 'integer',
    // ... dates, etc.
];
```

### Accessors for Display

```php
// Status badge styling
public function getStatusBadgeAttribute() {
    return $this->status === 'Selesai'
        ? 'bg-green-100 text-green-800'
        : 'bg-yellow-100 text-yellow-800';
}

// Priority badge styling
public function getPriorityBadgeAttribute() {
    // Returns HTML with color styling
}

// Deadline badge with warning colors
public function getDeadlineBadgeAttribute() {
    // Returns HTML with overdue/upcoming styling
}
```

---

## 🚀 Testing Integration

### Test Accounts

**Admin Access:**

```
Email: admin@kostrad.mil.id
Password: password
```

**Operator Access:**

```
Email: operator@kostrad.mil.id
Password: password
```

### Test Workflow

1. **Login as Admin**

    - Go to `/admin/dashboard`
    - View statistics (should show real counts)

2. **Create Public Case**

    - Go to `/admin/perkaras/create`
    - Fill form, set `is_public = true`
    - Save

3. **Verify Public Display**

    - Logout
    - Go to `/` (landing page)
    - Should see new case in preview table
    - Go to `/perkara` (public listing)
    - Should see new case in full list

4. **Toggle Visibility**
    - Login as admin
    - Edit case, set `is_public = false`
    - Save
    - Logout and check public pages
    - Case should disappear

---

## 📝 Routes Reference

### Public Routes

```php
GET  /                          # Landing page with statistics
GET  /perkara                   # Public case listing
GET  /perkara/public/{id}       # Public case detail
```

### Admin Routes (Auth Required)

```php
GET    /admin/dashboard         # Analytics dashboard
GET    /admin/perkaras          # Case listing (all cases)
GET    /admin/perkaras/create   # Create form
POST   /admin/perkaras          # Store new case
GET    /admin/perkaras/{id}     # Case detail
GET    /admin/perkaras/{id}/edit # Edit form
PUT    /admin/perkaras/{id}     # Update case
DELETE /admin/perkaras/{id}     # Delete case
GET    /admin/perkaras/export/excel # Export to Excel
GET    /admin/perkaras/export/pdf   # Export to PDF
```

---

## ✨ Features Summary

### Public Pages

-   ✅ Real-time statistics display
-   ✅ Latest case previews (3 cases)
-   ✅ Advanced search & filtering
-   ✅ Pagination (15 per page)
-   ✅ Category badges with colors
-   ✅ Status badges
-   ✅ Process duration calculation
-   ✅ Year filtering
-   ✅ Responsive design
-   ✅ Professional military theme

### Admin Panel

-   ✅ Comprehensive dashboard with Chart.js
-   ✅ Advanced filtering (9 filters)
-   ✅ CRUD operations
-   ✅ Export to Excel/PDF
-   ✅ Dark mode support
-   ✅ Progress tracking
-   ✅ Priority management
-   ✅ Deadline warnings
-   ✅ Activity logging
-   ✅ User management
-   ✅ RBAC (Admin/Operator roles)

---

## 🔄 Data Synchronization Status

| Feature          | Public Page    | Admin Panel    | Status    |
| ---------------- | -------------- | -------------- | --------- |
| Case Statistics  | ✅ Real-time   | ✅ Real-time   | ✅ Synced |
| Case Listings    | ✅ Public only | ✅ All cases   | ✅ Synced |
| Category Badges  | ✅ With colors | ✅ With colors | ✅ Synced |
| Status Badges    | ✅ Yes         | ✅ Yes         | ✅ Synced |
| Priority Display | ❌ N/A         | ✅ Yes         | ✅ OK     |
| Progress Bars    | ❌ N/A         | ✅ Yes         | ✅ OK     |
| Search/Filter    | ✅ Basic       | ✅ Advanced    | ✅ OK     |
| Pagination       | ✅ Yes         | ✅ Yes         | ✅ OK     |
| JSON Arrays      | ✅ Displayed   | ✅ Editable    | ✅ Fixed  |
| Dark Mode        | ❌ N/A         | ✅ Yes         | ✅ OK     |

---

## 🎯 Key Integration Points

1. **PublicController** → Fetches public data for landing and listing pages
2. **DashboardController** → Fetches all data with statistics for admin
3. **PerkaraController** → Handles CRUD with proper JSON array handling
4. **Perkara Model** → Provides accessors for badges and status
5. **Kategori Model** → Provides category colors for badges
6. **Blade Templates** → Proper JSON decoding for oditur/terdakwa arrays

---

## ✅ Integration Complete

All public pages and admin panels are now fully integrated with real-time database synchronization. Changes made in the admin panel are immediately reflected on public pages (for public cases).

**Last Updated:** December 17, 2025
