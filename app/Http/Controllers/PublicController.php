<?php

namespace App\Http\Controllers;

use App\Models\Perkara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    public function landing()
    {
        // Get latest 3 public cases
        $preview_perkaras = Perkara::with('kategori')
                                   ->where('is_public', true)
                                   ->latest()
                                   ->take(3)
                                   ->get();

        // Basic statistics for landing page (PUBLIC ONLY) — similar to Admin Dashboard but scoped to public cases
        $total_perkaras = Perkara::where('is_public', true)->count();
        $perkaras_selesai = Perkara::where('is_public', true)->where('status', 'Selesai')->count();
        $perkaras_proses = Perkara::where('is_public', true)->where('status', 'Proses')->count();

        // Cases this month (public only)
        $perkaras_bulan_ini = Perkara::where('is_public', true)
            ->whereMonth('tanggal_masuk', now()->month)
            ->whereYear('tanggal_masuk', now()->year)
            ->count();

        // Completion rate (public only)
        $completion_rate = $total_perkaras > 0
            ? round(($perkaras_selesai / $total_perkaras) * 100, 1)
            : 0;

        // Average completion time for public completed cases (in days)
        $completedCases = Perkara::where('is_public', true)
            ->where('status', 'Selesai')
            ->whereNotNull('tanggal_selesai')
            ->get();

        if ($completedCases->count() > 0) {
            $totalDays = $completedCases->sum(function($perkara) {
                return \Carbon\Carbon::parse($perkara->tanggal_masuk)->diffInDays(\Carbon\Carbon::parse($perkara->tanggal_selesai));
            });
            $avg_completion_days = round($totalDays / $completedCases->count(), 1);
        } else {
            $avg_completion_days = 0;
        }

        return view('landing', compact(
            'preview_perkaras',
            'total_perkaras',
            'perkaras_selesai',
            'perkaras_proses',
            'perkaras_bulan_ini',
            'completion_rate',
            'avg_completion_days'
        ));
    }

    public function perkara(Request $request)
    {
        // ✅ ONLY SHOW PUBLIC CASES
        $query = Perkara::with('kategori')->where('is_public', true);

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

        // Filter by kategori
        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->where('kategori_id', $request->kategori);
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

        // ✅ Count only PUBLIC cases
        $total_perkaras = Perkara::where('is_public', true)->count();

        // Get all categories
        $kategoris = \App\Models\Kategori::all();

        // Get unique klasifikasi (PUBLIC ONLY)
        $klasifikasiList = Perkara::where('is_public', true)
            ->whereNotNull('klasifikasi_perkara')
            ->distinct()
            ->pluck('klasifikasi_perkara')
            ->sort();

        // ✅ Get available years - PUBLIC ONLY
        $availableYears = Perkara::where('is_public', true)
            ->whereNotNull('tanggal_pendaftaran')
            ->get()
            ->map(function($perkara) {
                return $perkara->tanggal_pendaftaran->format('Y');
            })
            ->unique()
            ->sort()
            ->reverse()
            ->values();

        return view('perkara', compact('perkaras', 'total_perkaras', 'klasifikasiList', 'availableYears', 'kategoris'));
    }
}
