<?php

namespace App\Http\Controllers;

use App\Models\Perkara;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function landing()
    {
        // Data perkara untuk preview di landing page (3 data terakhir)
        $preview_perkaras = Perkara::with('kategori')
                                   ->public()
                                   ->selesai()
                                   ->latest()
                                   ->take(3)
                                   ->get();

        return view('landing', compact('preview_perkaras'));
    }

    public function perkara(Request $request)
    {
        $query = Perkara::with('kategori')
                        ->public()
                        ->selesai();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_perkara', 'like', "%{$search}%")
                  ->orWhere('jenis_perkara', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by year
        if ($request->filled('year') && $request->year !== 'all') {
            $query->whereYear('tanggal_masuk', $request->year);
        }

        $perkaras = $query->paginate(15);
        $total_perkaras = Perkara::public()->selesai()->count();

        return view('perkara', compact('perkaras', 'total_perkaras'));
    }
}
