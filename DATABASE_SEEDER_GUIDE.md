# Database Seeder Documentation

## Overview

Complete database seeding system for SIPERKARA DIV-2 Kostrad with realistic dummy data.

## Seeder Files Created

### 1. **PersonelSeeder.php**

-   Creates 15 military personnel
-   Includes various ranks from Kolonel to Prada
-   Assigned to different units (Yonif, Yonkav, Yonbekang, Yonarmed)

### 2. **CompletePerkaraSeeder.php**

-   Creates 10 comprehensive case records
-   Mix of completed (3) and in-progress (7) cases
-   Covers 3 categories: Disiplin, Administrasi, Pidana
-   Includes all case details: dates, parties involved, legal articles, documents
-   Automatically links cases with personnel

### 3. **DokumenPerkaraSeeder.php**

-   Creates 2-4 documents per case (for 7 cases)
-   Document types: Surat Dakwaan, BAP, Surat Pelimpahan, etc.
-   Includes verification status

### 4. **RiwayatPerkaraSeeder.php**

-   Creates 3-6 history entries per case (for 8 cases)
-   Tracks status changes through case lifecycle
-   Timestamps reflect realistic progression

### 5. **NotificationSeeder.php**

-   Creates 5-10 notifications per user
-   Types: case_assigned, status_changed, deadline_reminder, etc.
-   60% marked as read with timestamps

### 6. **NotificationPreferenceSeeder.php**

-   Creates notification preferences for each user
-   Randomized email/push notification settings

### 7. **ActivityLogSeeder.php**

-   Creates activity logs for case operations (created, updated, deleted, viewed)
-   Includes login/logout activities (5-15 per user)
-   Complete with IP addresses, user agents, metadata

## Usage

### Full Seeding

Run all seeders in correct order:

```bash
php artisan migrate:fresh --seed
```

### Individual Seeders

Run specific seeder:

```bash
php artisan db:seed --class=PersonelSeeder
php artisan db:seed --class=CompletePerkaraSeeder
php artisan db:seed --class=DokumenPerkaraSeeder
php artisan db:seed --class=RiwayatPerkaraSeeder
php artisan db:seed --class=NotificationSeeder
php artisan db:seed --class=NotificationPreferenceSeeder
php artisan db:seed --class=ActivityLogSeeder
```

## Seeding Order (IMPORTANT!)

The seeders must run in this order due to dependencies:

1. **KategoriSeeder** - Base categories (Disiplin, Administrasi, Pidana)
2. **UserSeeder** - System users (admin, operator)
3. **PersonelSeeder** - Military personnel
4. **CompletePerkaraSeeder** - Case records (requires kategoris, users, personels)
5. **DokumenPerkaraSeeder** - Case documents (requires perkaras)
6. **RiwayatPerkaraSeeder** - Case history (requires perkaras, users)
7. **NotificationPreferenceSeeder** - User notification settings (requires users)
8. **NotificationSeeder** - Notification records (requires users, perkaras)
9. **ActivityLogSeeder** - Activity logs (requires users, perkaras)

## Data Summary

### Total Records Created:

-   **Kategoris**: 3 items
-   **Users**: 2 items (admin & operator)
-   **Personels**: 15 items
-   **Perkaras**: 10 items
-   **Dokumen Perkaras**: ~25 items (2-4 per case × 7 cases)
-   **Riwayat Perkaras**: ~35 items (3-6 per case × 8 cases)
-   **Notification Preferences**: 2 items (1 per user)
-   **Notifications**: ~15 items per user = ~30 total
-   **Activity Logs**: ~100+ items (case logs + auth logs)

**GRAND TOTAL: ~200+ records across 9 tables**

## Default Login Credentials

### Administrator

-   **Email**: admin@siperkara.mil.id
-   **Password**: password
-   **Role**: admin
-   **NRP**: 1234567890
-   **Pangkat**: Mayor
-   **Jabatan**: Kepala Seksi Hukum

### Operator

-   **Email**: operator@siperkara.mil.id
-   **Password**: password
-   **Role**: operator
-   **NRP**: 0987654321
-   **Pangkat**: Kapten
-   **Jabatan**: Staff Seksi Hukum

## Case Data Highlights

### Case Types Distribution:

-   **Pelanggaran Disiplin**: 4 cases (40%)
-   **Tindak Pidana**: 3 cases (30%)
-   **Administrasi**: 3 cases (30%)

### Status Distribution:

-   **Selesai**: 3 cases (30%)
-   **Proses**: 7 cases (70%)

### Priority Distribution:

-   **Tinggi**: 4 cases
-   **Sedang**: 3 cases
-   **Rendah**: 3 cases

### Classification:

-   **Berat**: 4 cases
-   **Sedang**: 3 cases
-   **Ringan**: 3 cases

## Sample Case Scenarios

1. **PKR/001** - Terlambat Apel (Completed)
2. **PKR/002** - Pencurian Senjata (In Progress, High Priority)
3. **PKR/003** - Keterlambatan Laporan Keuangan (In Progress)
4. **PKR/004** - AWOL (In Progress)
5. **PKR/005** - Penganiayaan (Completed, Serious Crime)
6. **PKR/006** - Kehilangan Dokumen Rahasia (Urgent)
7. **PKR/007** - Penyalahgunaan Fasilitas (Minor)
8. **PKR/008** - Desersi (DPO Status)
9. **PKR/009** - Kesalahan Pencatatan Aset (Completed)
10. **PKR/010** - Tidak Memakai Atribut Lengkap (Minor)

## Testing Dashboard Analytics

After seeding, the dashboard will display:

-   **Total Perkara**: 10 cases
-   **Selesai**: 3 cases (30% completion rate)
-   **Dalam Proses**: 7 cases
-   **Bulan Ini**: 5 cases
-   **Average Completion**: ~30 days
-   **Monthly trends** with realistic data
-   **Category distribution** pie chart
-   **Recent cases** list
-   **Top 5 categories** with counts

## Notes

-   All dates are relative to current date using Carbon
-   Personnel are randomly attached to cases with roles (Saksi, Terdakwa, Oditur, Penyidik)
-   Some cases marked as public, others confidential
-   Documents include verification status
-   Activity logs include both case operations and auth events
-   Notifications have realistic read/unread ratios (60% read)

## Security Warning

⚠️ **IMPORTANT**: The default passwords are "password" for demonstration purposes only.
**CHANGE THESE IMMEDIATELY** in production environments!

## Refreshing Data

To completely reset and reseed the database:

```bash
php artisan migrate:fresh --seed
```

This will:

1. Drop all tables
2. Run all migrations
3. Execute all seeders in correct order

## Troubleshooting

### Error: "Class not found"

Make sure to run:

```bash
composer dump-autoload
```

### Error: "Foreign key constraint fails"

Ensure seeders run in the correct order as specified in DatabaseSeeder.php

### Error: "SQLSTATE[23000]: Integrity constraint violation"

Reset the database completely:

```bash
php artisan migrate:fresh --seed
```

## Related Files

-   `database/seeders/DatabaseSeeder.php` - Main seeder orchestrator
-   `database/seeders/KategoriSeeder.php` - Category seeder (existing)
-   `database/seeders/UserSeeder.php` - User seeder (existing)
-   `database/seeders/PerkaraSeeder.php` - Legacy perkara seeder (replaced by CompletePerkaraSeeder)

## Version

-   **Created**: December 17, 2025
-   **Laravel Version**: 12.36.1
-   **Database**: MySQL 8.x
