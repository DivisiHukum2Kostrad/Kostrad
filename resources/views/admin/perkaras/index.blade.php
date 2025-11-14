{{-- resources/views/admin/perkaras/index.blade.php --}}
@extends('admin.layout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Kelola Perkara</h2>
            <p class="text-gray-600 mt-1">Daftar semua perkara yang terdaftar</p>
        </div>
        <a href="{{ route('admin.perkaras.create') }}" class="bg-green-800 hover:bg-green-900 text-white px-6 py-3 rounded-lg font-semibold transition duration-300 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Perkara
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter & Search -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <form method="GET" action="{{ route('admin.perkaras.index') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor/jenis perkara..." class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none">
            </div>
            <select name="status" class="px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none">
                <option value="all">Semua Status</option>
                <option value="Proses" {{ request('status') === 'Proses' ? 'selected' : '' }}>Proses</option>
                <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
            <select name="kategori" class="px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none">
                <option value="all">Semua Kategori</option>
                @foreach($kategoris as $kat)
                    <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-green-800 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-900 transition">
                Filter
            </button>
            <a href="{{ route('admin.perkaras.index') }}" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
                Reset
            </a>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-green-800 to-green-900 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase">Nomor Perkara</th>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase">Jenis Perkara</th>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase">Kategori</th>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase">Tanggal Masuk</th>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($perkaras as $perkara)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900">{{ $perkara->nomor_perkara }}</td>
                            <td class="px-6 py-4">{{ $perkara->jenis_perkara }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $perkara->kategori_badge }}">
                                    {{ $perkara->kategori->nama }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $perkara->tanggal_masuk->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $perkara->status_badge }}">
                                    {{ $perkara->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.perkaras.show', $perkara) }}" class="text-blue-600 hover:text-blue-800">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.perkaras.edit', $perkara) }}" class="text-yellow-600 hover:text-yellow-800">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.perkaras.destroy', $perkara) }}" onsubmit="return confirm('Yakin hapus perkara ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">Tidak ada data perkara</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 bg-gray-50 border-t">
            {{ $perkaras->links() }}
        </div>
    </div>
</div>
@endsection
