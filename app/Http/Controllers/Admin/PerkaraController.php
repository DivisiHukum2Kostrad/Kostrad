<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perkara;
use App\Models\Kategori;
use App\Models\Personel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PerkaraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Perkara::with('kategori');

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

        // Filter by kategori
        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->where('kategori_id', $request->kategori);
        }

        $perkaras = $query->latest()->paginate(15);
        $kategoris = Kategori::all();

        return view('admin.perkaras.index', compact('perkaras', 'kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::all();
        $personels = Personel::all();
        $nomor_perkara = Perkara::generateNomorPerkara();

        return view('admin.perkaras.create', compact('kategoris', 'personels', 'nomor_perkara'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_perkara' => 'required|unique:perkaras,nomor_perkara',
            'jenis_perkara' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'tanggal_masuk' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_masuk',
            'status' => 'required|in:Proses,Selesai',
            'keterangan' => 'nullable|string',
            'is_public' => 'boolean',
            'file_dokumentasi' => 'nullable|file|mimes:pdf|max:5120', // Max 5MB
        ], [
            'nomor_perkara.required' => 'Nomor perkara wajib diisi',
            'nomor_perkara.unique' => 'Nomor perkara sudah ada',
            'jenis_perkara.required' => 'Jenis perkara wajib diisi',
            'kategori_id.required' => 'Kategori wajib dipilih',
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi',
            'status.required' => 'Status wajib dipilih',
            'file_dokumentasi.mimes' => 'File harus berformat PDF',
            'file_dokumentasi.max' => 'File maksimal 5MB',
        ]);

        // Handle file upload
        if ($request->hasFile('file_dokumentasi')) {
            $file = $request->file('file_dokumentasi');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('perkaras', $filename, 'public');
            $validated['file_dokumentasi'] = $path;
        }

        $validated['is_public'] = $request->has('is_public');

        $perkara = Perkara::create($validated);

        // Attach personels if provided
        if ($request->filled('personels')) {
            foreach ($request->personels as $personelId => $peran) {
                if ($peran) {
                    $perkara->personels()->attach($personelId, ['peran' => $peran]);
                }
            }
        }

        return redirect()->route('admin.perkaras.index')
            ->with('success', 'Perkara berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Perkara $perkara)
    {
        $perkara->load(['kategori', 'personels', 'dokumens', 'riwayats.user']);

        return view('admin.perkaras.show', compact('perkara'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Perkara $perkara)
    {
        $kategoris = Kategori::all();
        $personels = Personel::all();
        $perkara->load('personels');

        return view('admin.perkaras.edit', compact('perkara', 'kategoris', 'personels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Perkara $perkara)
    {
        $validated = $request->validate([
            'nomor_perkara' => 'required|unique:perkaras,nomor_perkara,' . $perkara->id,
            'jenis_perkara' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'tanggal_masuk' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_masuk',
            'status' => 'required|in:Proses,Selesai',
            'keterangan' => 'nullable|string',
            'is_public' => 'boolean',
            'file_dokumentasi' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        // Handle file upload
        if ($request->hasFile('file_dokumentasi')) {
            // Delete old file
            if ($perkara->file_dokumentasi) {
                Storage::disk('public')->delete($perkara->file_dokumentasi);
            }

            $file = $request->file('file_dokumentasi');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('perkaras', $filename, 'public');
            $validated['file_dokumentasi'] = $path;
        }

        $validated['is_public'] = $request->has('is_public');

        $perkara->update($validated);

        // Sync personels
        if ($request->filled('personels')) {
            $sync = [];
            foreach ($request->personels as $personelId => $peran) {
                if ($peran) {
                    $sync[$personelId] = ['peran' => $peran];
                }
            }
            $perkara->personels()->sync($sync);
        } else {
            $perkara->personels()->detach();
        }

        return redirect()->route('admin.perkaras.index')
            ->with('success', 'Perkara berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Perkara $perkara)
    {
        // Delete file if exists
        if ($perkara->file_dokumentasi) {
            Storage::disk('public')->delete($perkara->file_dokumentasi);
        }

        $perkara->delete();

        return redirect()->route('admin.perkaras.index')
            ->with('success', 'Perkara berhasil dihapus!');
    }
}
