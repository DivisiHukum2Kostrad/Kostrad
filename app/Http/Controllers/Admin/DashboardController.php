<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perkara;
use App\Models\Kategori;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_perkara' => Perkara::count(),
            'perkara_selesai' => Perkara::selesai()->count(),
            'perkara_proses' => Perkara::proses()->count(),
            'perkara_bulan_ini' => Perkara::whereMonth('tanggal_masuk', now()->month)
                                        ->whereYear('tanggal_masuk', now()->year)
                                        ->count(),
        ];

        // Data perkara per kategori
        $perkara_per_kategori = Kategori::withCount('perkaras')->get();

        // Perkara terbaru
        $latest_perkaras = Perkara::with('kategori')
                                  ->latest()
                                  ->take(5)
                                  ->get();

        return view('admin.dashboard', compact('stats', 'perkara_per_kategori', 'latest_perkaras'));
    }
}
