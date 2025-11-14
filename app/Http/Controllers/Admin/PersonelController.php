<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Personel;
use Illuminate\Http\Request;

class PersonelController extends Controller
{
    public function index(Request $request)
    {
        $query = Personel::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nrp', 'like', "%{$search}%")
                  ->orWhere('pangkat', 'like', "%{$search}%");
            });
        }

        $personels = $query->latest()->paginate(15);

        return view('admin.personels.index', compact('personels'));
    }

    public function create()
    {
        return view('admin.personels.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nrp' => 'required|unique:personels,nrp',
            'nama' => 'required|string|max:255',
            'pangkat' => 'nullable|string|max:100',
            'jabatan' => 'nullable|string|max:255',
            'kesatuan' => 'nullable|string|max:255',
        ], [
            'nrp.required' => 'NRP wajib diisi',
            'nrp.unique' => 'NRP sudah terdaftar',
            'nama.required' => 'Nama wajib diisi',
        ]);

        Personel::create($validated);

        return redirect()->route('admin.personels.index')
            ->with('success', 'Personel berhasil ditambahkan!');
    }

    public function edit(Personel $personel)
    {
        return view('admin.personels.edit', compact('personel'));
    }

    public function update(Request $request, Personel $personel)
    {
        $validated = $request->validate([
            'nrp' => 'required|unique:personels,nrp,' . $personel->id,
            'nama' => 'required|string|max:255',
            'pangkat' => 'nullable|string|max:100',
            'jabatan' => 'nullable|string|max:255',
            'kesatuan' => 'nullable|string|max:255',
        ]);

        $personel->update($validated);

        return redirect()->route('admin.personels.index')
            ->with('success', 'Personel berhasil diupdate!');
    }

    public function destroy(Personel $personel)
    {
        $personel->delete();

        return redirect()->route('admin.personels.index')
            ->with('success', 'Personel berhasil dihapus!');
    }
}
