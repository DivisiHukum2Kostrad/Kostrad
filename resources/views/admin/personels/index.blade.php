@extends('admin.layout')

@section('title', 'Manajemen Personel')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Manajemen Personel</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola data personel militer</p>
            </div>
            <a href="{{ route('admin.personels.create') }}"
                class="bg-green-800 hover:bg-green-900 text-white px-6 py-3 rounded-lg font-semibold transition duration-300 flex items-center space-x-2">
                <i class="fas fa-plus"></i>
                <span>Tambah Personel</span>
            </a>
        </div>

        @if (session('success'))
            <div
                class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 text-green-700 dark:text-green-400 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Search & Filter -->
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <form method="GET" action="{{ route('admin.personels.index') }}" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari NRP, nama, pangkat..."
                        class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:border-green-800 focus:outline-none dark:bg-gray-700 dark:text-white">
                </div>
                <button type="submit"
                    class="px-6 py-3 bg-green-800 text-white rounded-lg font-semibold hover:bg-green-900 transition duration-300">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
                <a href="{{ route('admin.personels.index') }}"
                    class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition duration-300 text-center">
                    <i class="fas fa-sync mr-2"></i>Reset
                </a>
            </form>
        </div>

        <!-- Personel Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            @if ($personels->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-green-800 to-green-900 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold">NRP</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Nama</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Pangkat</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Jabatan</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Kesatuan</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Jumlah Perkara</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($personels as $personel)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                    <td class="px-6 py-4">
                                        <span
                                            class="font-mono text-sm text-gray-900 dark:text-gray-100">{{ $personel->nrp }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $personel->nama }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-semibold rounded-full">
                                            {{ $personel->pangkat ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $personel->jabatan ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $personel->kesatuan ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-3 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-xs font-semibold rounded-full">
                                            <i class="fas fa-folder mr-1"></i>
                                            {{ $personel->perkaras->count() }} perkara
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('admin.personels.edit', $personel->id) }}"
                                                class="px-4 py-2 bg-green-800 hover:bg-green-900 text-white rounded-lg text-sm font-semibold transition duration-300">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </a>
                                            <form action="{{ route('admin.personels.destroy', $personel->id) }}"
                                                method="POST" class="inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus personel ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-semibold transition duration-300">
                                                    <i class="fas fa-trash mr-1"></i>Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                    {{ $personels->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-users text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                    <p class="text-gray-500 dark:text-gray-400 text-lg">
                        @if (request('search'))
                            Tidak ada personel yang ditemukan dengan pencarian "{{ request('search') }}"
                        @else
                            Belum ada data personel. Klik tombol "Tambah Personel" untuk menambahkan.
                        @endif
                    </p>
                </div>
            @endif
        </div>

        <!-- Statistics -->
        @if ($personels->total() > 0)
            <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Personel</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $personels->total() }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Halaman</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $personels->currentPage() }} /
                            {{ $personels->lastPage() }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
