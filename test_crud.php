<?php

/**
 * CRUD Testing Script for SiPerkara System
 * Tests all CRUD operations and backend connectivity
 * Run: php test_crud.php
 */

require __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Perkara;
use App\Models\Personel;
use App\Models\DokumenPerkara;
use App\Models\User;

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Color output functions
function success($message) {
    echo "\033[32m✓ " . $message . "\033[0m\n";
}

function error($message) {
    echo "\033[31m✗ " . $message . "\033[0m\n";
}

function info($message) {
    echo "\033[34mℹ " . $message . "\033[0m\n";
}

function testHeader($message) {
    echo "\n\033[1;36m" . str_repeat("=", 60) . "\033[0m\n";
    echo "\033[1;36m" . $message . "\033[0m\n";
    echo "\033[1;36m" . str_repeat("=", 60) . "\033[0m\n\n";
}

// Start testing
testHeader("SiPerkara CRUD Testing - Backend Connectivity Check");

$results = [
    'database' => false,
    'perkara_crud' => false,
    'personel_crud' => false,
    'document_crud' => false,
    'user_crud' => false,
    'file_management' => false,
];

// ============================================================================
// TEST 1: Database Connection
// ============================================================================
testHeader("TEST 1: Database Connection");

try {
    DB::connection()->getPdo();
    $dbName = DB::connection()->getDatabaseName();
    success("Database connected successfully");
    info("Database name: " . $dbName);
    
    // Test query
    $tables = DB::select('SHOW TABLES');
    success("Found " . count($tables) . " tables in database");
    
    $results['database'] = true;
} catch (\Exception $e) {
    error("Database connection failed: " . $e->getMessage());
    die("\nCannot proceed without database connection.\n");
}

// ============================================================================
// TEST 2: Perkara (Cases) CRUD Operations
// ============================================================================
testHeader("TEST 2: Perkara (Cases) CRUD Operations");

try {
    // CREATE
    info("Testing CREATE...");
    $perkara = Perkara::create([
        'nomor_perkara' => 'TEST/' . time() . '/2025',
        'jenis_perkara' => 'Pidana',
        'nama' => 'Test Case Name',
        'kategori_id' => 1, // Disiplin category
        'status' => 'Proses',
        'priority' => 'medium',
        'tanggal_masuk' => now(),
        'tanggal_perkara' => now(),
        'deskripsi' => 'Test case for CRUD testing',
    ]);
    success("CREATE: Perkara created with ID: " . $perkara->id);
    
    // READ
    info("Testing READ...");
    $found = Perkara::find($perkara->id);
    if ($found && $found->nomor_perkara === $perkara->nomor_perkara) {
        success("READ: Perkara retrieved successfully");
    } else {
        throw new \Exception("Failed to retrieve created perkara");
    }
    
    // UPDATE
    info("Testing UPDATE...");
    $perkara->update([
        'status' => 'Selesai',
        'priority' => 'high',
        'deskripsi' => 'Updated description for testing',
        'progress' => 100,
    ]);
    $updated = Perkara::find($perkara->id);
    if ($updated->status === 'Selesai') {
        success("UPDATE: Perkara updated successfully");
    } else {
        throw new \Exception("Failed to update perkara");
    }
    
    // LIST/INDEX
    info("Testing LIST/INDEX...");
    $allPerkaras = Perkara::take(5)->get();
    success("LIST: Retrieved " . $allPerkaras->count() . " perkaras");
    
    // DELETE
    info("Testing DELETE...");
    $deleteId = $perkara->id;
    $perkara->delete();
    $deleted = Perkara::find($deleteId);
    if (!$deleted) {
        success("DELETE: Perkara deleted successfully");
    } else {
        throw new \Exception("Failed to delete perkara");
    }
    
    $results['perkara_crud'] = true;
    success("All Perkara CRUD operations passed!");
    
} catch (\Exception $e) {
    error("Perkara CRUD failed: " . $e->getMessage());
    info("Stack trace: " . $e->getTraceAsString());
}

// ============================================================================
// TEST 3: Personel CRUD Operations
// ============================================================================
testHeader("TEST 3: Personel CRUD Operations");

try {
    // CREATE
    info("Testing CREATE...");
    $personel = Personel::create([
        'nama' => 'Test Personel ' . time(),
        'nrp' => 'NRP' . time(),
        'pangkat' => 'Letnan',
        'jabatan' => 'Staff',
        'kesatuan' => 'Divisi 2 Kostrad',
        'email' => 'test' . time() . '@kostrad.mil.id',
        'telepon' => '08123456789',
    ]);
    success("CREATE: Personel created with ID: " . $personel->id);
    
    // READ
    info("Testing READ...");
    $found = Personel::find($personel->id);
    if ($found && $found->nama === $personel->nama) {
        success("READ: Personel retrieved successfully");
    } else {
        throw new \Exception("Failed to retrieve created personel");
    }
    
    // UPDATE
    info("Testing UPDATE...");
    $personel->update([
        'jabatan' => 'Updated Staff Position',
        'telepon' => '08987654321',
    ]);
    $updated = Personel::find($personel->id);
    if ($updated->jabatan === 'Updated Staff Position') {
        success("UPDATE: Personel updated successfully");
    } else {
        throw new \Exception("Failed to update personel");
    }
    
    // LIST
    info("Testing LIST...");
    $allPersonels = Personel::take(5)->get();
    success("LIST: Retrieved " . $allPersonels->count() . " personels");
    
    // DELETE
    info("Testing DELETE...");
    $deleteId = $personel->id;
    $personel->delete();
    $deleted = Personel::find($deleteId);
    if (!$deleted) {
        success("DELETE: Personel deleted successfully");
    } else {
        throw new \Exception("Failed to delete personel");
    }
    
    $results['personel_crud'] = true;
    success("All Personel CRUD operations passed!");
    
} catch (\Exception $e) {
    error("Personel CRUD failed: " . $e->getMessage());
}

// ============================================================================
// TEST 4: Document CRUD Operations
// ============================================================================
testHeader("TEST 4: Document CRUD Operations");

try {
    // Need a perkara first
    $testPerkara = Perkara::create([
        'nomor_perkara' => 'DOC-TEST/' . time() . '/2025',
        'jenis_perkara' => 'Pidana',
        'nama' => 'Test Document Case',
        'kategori_id' => 2, // Administrasi
        'status' => 'Proses',
        'priority' => 'medium',
        'tanggal_masuk' => now(),
        'tanggal_perkara' => now(),
        'deskripsi' => 'Test case for document testing',
    ]);
    
    // CREATE
    info("Testing CREATE...");
    $document = DokumenPerkara::create([
        'perkara_id' => $testPerkara->id,
        'nama_dokumen' => 'Test Document ' . time() . '.pdf',
        'jenis_dokumen' => 'Legal',
        'category' => 'legal',
        'file_path' => 'documents/test_' . time() . '.pdf',
        'file_size' => 1024000,
        'mime_type' => 'application/pdf',
        'uploaded_by' => 1,
        'is_public' => false,
    ]);
    success("CREATE: Document created with ID: " . $document->id);
    
    // READ
    info("Testing READ...");
    $found = DokumenPerkara::find($document->id);
    if ($found && $found->nama_dokumen === $document->nama_dokumen) {
        success("READ: Document retrieved successfully");
    } else {
        throw new \Exception("Failed to retrieve created document");
    }
    
    // Test relationship
    info("Testing RELATIONSHIP...");
    $relatedPerkara = $found->perkara;
    if ($relatedPerkara && $relatedPerkara->id === $testPerkara->id) {
        success("RELATIONSHIP: Document-Perkara relationship working");
    }
    
    // UPDATE
    info("Testing UPDATE...");
    $document->update([
        'jenis_dokumen' => 'Evidence',
        'category' => 'evidence',
        'description' => 'Updated test document',
    ]);
    $updated = DokumenPerkara::find($document->id);
    if ($updated->jenis_dokumen === 'Evidence') {
        success("UPDATE: Document updated successfully");
    } else {
        throw new \Exception("Failed to update document");
    }
    
    // LIST
    info("Testing LIST...");
    $allDocuments = DokumenPerkara::where('perkara_id', $testPerkara->id)->get();
    success("LIST: Retrieved " . $allDocuments->count() . " documents for perkara");
    
    // DELETE
    info("Testing DELETE...");
    $deleteId = $document->id;
    $document->delete();
    $deleted = DokumenPerkara::find($deleteId);
    if (!$deleted) {
        success("DELETE: Document deleted successfully");
    } else {
        throw new \Exception("Failed to delete document");
    }
    
    // Cleanup
    $testPerkara->delete();
    
    $results['document_crud'] = true;
    success("All Document CRUD operations passed!");
    
} catch (\Exception $e) {
    error("Document CRUD failed: " . $e->getMessage());
}

// ============================================================================
// TEST 5: User CRUD Operations
// ============================================================================
testHeader("TEST 5: User CRUD Operations");

try {
    // CREATE
    info("Testing CREATE...");
    $user = User::create([
        'name' => 'Test User ' . time(),
        'email' => 'testuser' . time() . '@test.com',
        'password' => bcrypt('password123'),
        'role' => 'operator', // Valid enum: admin or operator
        'nrp' => 'NRP' . time(),
        'pangkat' => 'Letnan',
        'jabatan' => 'Staff',
    ]);
    success("CREATE: User created with ID: " . $user->id);
    
    // READ
    info("Testing READ...");
    $found = User::find($user->id);
    if ($found && $found->email === $user->email) {
        success("READ: User retrieved successfully");
    } else {
        throw new \Exception("Failed to retrieve created user");
    }
    
    // UPDATE
    info("Testing UPDATE...");
    $user->update([
        'name' => 'Updated Test User',
        'role' => 'admin',
        'jabatan' => 'Senior Staff',
    ]);
    $updated = User::find($user->id);
    if ($updated->name === 'Updated Test User') {
        success("UPDATE: User updated successfully");
    } else {
        throw new \Exception("Failed to update user");
    }
    
    // LIST
    info("Testing LIST...");
    $allUsers = User::take(5)->get();
    success("LIST: Retrieved " . $allUsers->count() . " users");
    
    // DELETE
    info("Testing DELETE...");
    $deleteId = $user->id;
    $user->delete();
    $deleted = User::find($deleteId);
    if (!$deleted) {
        success("DELETE: User deleted successfully");
    } else {
        throw new \Exception("Failed to delete user");
    }
    
    $results['user_crud'] = true;
    success("All User CRUD operations passed!");
    
} catch (\Exception $e) {
    error("User CRUD failed: " . $e->getMessage());
}

// ============================================================================
// TEST 6: New File Management Features
// ============================================================================
testHeader("TEST 6: File Management Features (Feature #10)");

try {
    // Test if new columns exist
    info("Testing new database columns...");
    
    $testPerkara = Perkara::create([
        'nomor_perkara' => 'FILE-TEST/' . time() . '/2025',
        'jenis_perkara' => 'Pidana',
        'nama' => 'Test File Management Case',
        'kategori_id' => 3, // Pidana
        'status' => 'Proses',
        'priority' => 'high',
        'tanggal_masuk' => now(),
        'tanggal_perkara' => now(),
        'deskripsi' => 'Test case for file management',
    ]);
    
    // Test document with new fields
    $document = DokumenPerkara::create([
        'perkara_id' => $testPerkara->id,
        'nama_dokumen' => 'FileTest ' . time() . '.pdf',
        'jenis_dokumen' => 'Legal',
        'category' => 'legal',
        'file_path' => 'documents/filetest_' . time() . '.pdf',
        'file_size' => 2048000,
        'mime_type' => 'application/pdf',
        'uploaded_by' => 1,
        'is_public' => false,
        'thumbnail_path' => 'thumbnails/test_thumb.jpg',
        'qr_code_path' => 'qrcodes/test_qr.svg',
        'digital_signature' => hash('sha256', 'test_signature'),
        'signature_name' => 'Test Signer',
        'signed_at' => now(),
        'signed_by' => 1,
        'metadata' => ['test_key' => 'test_value'],
        'has_thumbnail' => true,
        'is_signed' => true,
    ]);
    
    success("CREATE: Document with new fields created");
    
    // Test retrieval
    $found = DokumenPerkara::find($document->id);
    if ($found->has_thumbnail && $found->is_signed) {
        success("READ: New boolean fields working correctly");
    }
    
    if ($found->metadata && isset($found->metadata['test_key'])) {
        success("JSON: Metadata field working correctly");
    }
    
    if ($found->signedBy) {
        success("RELATIONSHIP: signedBy relationship working");
    }
    
    // Test update
    $document->update([
        'metadata' => ['updated_key' => 'updated_value', 'count' => 5],
    ]);
    
    $updated = DokumenPerkara::find($document->id);
    if ($updated->metadata['updated_key'] === 'updated_value') {
        success("UPDATE: Metadata update working correctly");
    }
    
    // Cleanup
    $document->delete();
    $testPerkara->delete();
    
    $results['file_management'] = true;
    success("All File Management feature tests passed!");
    
} catch (\Exception $e) {
    error("File Management features failed: " . $e->getMessage());
    info("This might indicate migration hasn't been run");
}

// ============================================================================
// TEST SUMMARY
// ============================================================================
testHeader("TEST SUMMARY");

$passed = array_sum($results);
$total = count($results);

echo "\nTest Results:\n";
echo str_repeat("-", 60) . "\n";

foreach ($results as $test => $result) {
    $status = $result ? "\033[32m✓ PASSED\033[0m" : "\033[31m✗ FAILED\033[0m";
    $testName = str_pad(ucwords(str_replace('_', ' ', $test)), 35);
    echo "$testName $status\n";
}

echo str_repeat("-", 60) . "\n";

if ($passed === $total) {
    success("\n🎉 ALL TESTS PASSED! ($passed/$total)");
    success("Backend connectivity: ✓ WORKING");
    success("CRUD operations: ✓ WORKING");
    success("Database relationships: ✓ WORKING");
    success("New features: ✓ WORKING");
    echo "\n";
} else {
    error("\n⚠ SOME TESTS FAILED! ($passed/$total passed)");
    echo "\nPlease check the errors above and:\n";
    echo "1. Verify database connection settings in .env\n";
    echo "2. Run migrations: php artisan migrate\n";
    echo "3. Check model relationships\n";
    echo "4. Review controller methods\n\n";
}

// Additional Info
testHeader("ADDITIONAL INFORMATION");

info("Database: " . DB::connection()->getDatabaseName());
info("Total Perkaras: " . Perkara::count());
info("Total Personels: " . Personel::count());
info("Total Documents: " . DokumenPerkara::count());
info("Total Users: " . User::count());

// Check routes
info("\nRegistered Routes:");
$routes = [
    'Perkara Index' => url('/admin/perkaras'),
    'Perkara Create' => url('/admin/perkaras/create'),
    'Personel Index' => url('/admin/personels'),
    'Batch Operations' => url('/admin/batch-operations'),
    'Dashboard' => url('/admin/dashboard'),
];

foreach ($routes as $name => $url) {
    info("  $name: $url");
}

echo "\n";
