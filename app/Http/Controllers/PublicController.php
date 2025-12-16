<?php

namespace App\Http\Controllers;

use App\Models\Perkara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    public function landing()
    {
        $preview_perkaras = Perkara::with('kategori')
                                   ->where('is_public', true)
                                   ->latest()
                                   ->take(3)
                                   ->get();

        return view('landing', compact('preview_perkaras'));
    }

    public function perkara(Request $request)
    {
        $query = Perkara::with('kategori');

        // Advanced Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_perkara', 'like', "%{$search}%")
                  ->orWhere('jenis_perkara', 'like', "%{$search}%")
                  ->orWhere('klasifikasi_perkara', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('oditur', 'like', "%{$search}%")
                  ->orWhere('terdakwa', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by klasifikasi
        if ($request->filled('klasifikasi') && $request->klasifikasi !== 'all') {
            $query->where('klasifikasi_perkara', $request->klasifikasi);
        }

        // ✅ Filter by year - COMPATIBLE MySQL & SQLite
        if ($request->filled('year') && $request->year !== 'all') {
            if (DB::connection()->getDriverName() === 'sqlite') {
                // SQLite menggunakan strftime
                $query->whereRaw("strftime('%Y', tanggal_pendaftaran) = ?", [$request->year]);
            } else {
                // MySQL/MariaDB menggunakan YEAR()
                $query->whereYear('tanggal_pendaftaran', $request->year);
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');

        $allowedSorts = ['created_at', 'tanggal_pendaftaran', 'nomor_perkara', 'status'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDir);
        } else {
            $query->latest();
        }

        $perkaras = $query->paginate(15)->withQueryString();
        $total_perkaras = Perkara::count();

        // Get unique klasifikasi
        $klasifikasiList = Perkara::whereNotNull('klasifikasi_perkara')
            ->distinct()
            ->pluck('klasifikasi_perkara')
            ->sort();

        // ✅ Get available years - COMPATIBLE MySQL & SQLite
        // Cara ini bekerja di semua database karena menggunakan PHP untuk extract tahun
        $availableYears = Perkara::whereNotNull('tanggal_pendaftaran')
            ->get()
            ->map(function($perkara) {
                return $perkara->tanggal_pendaftaran->format('Y');
            })
            ->unique()
            ->sort()
            ->reverse()
            ->values();

        return view('perkara', compact('perkaras', 'total_perkaras', 'klasifikasiList', 'availableYears'));
    }
}
