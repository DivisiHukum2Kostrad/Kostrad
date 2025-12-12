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
            <a href="{{ route('admin.perkaras.create') }}"
                class="bg-green-800 hover:bg-green-900 text-white px-6 py-3 rounded-lg font-semibold transition duration-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Perkara
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Advanced Filter & Search -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">Filter & Pencarian</h3>
                <div class="flex gap-2">
                    <a href="{{ route('admin.perkaras.export.excel', request()->query()) }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Excel
                    </a>
                    <a href="{{ route('admin.perkaras.export.pdf', request()->query()) }}"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        PDF
                    </a>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.perkaras.index') }}" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                    <!-- Search -->
                    <div class="lg:col-span-3">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pencarian</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nomor/jenis perkara/keterangan..."
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none">
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                        <select name="status"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none">
                            <option value="all">Semua Status</option>
                            <option value="Proses" {{ request('status') === 'Proses' ? 'selected' : '' }}>Proses</option>
                            <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                        <select name="kategori"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none">
                            <option value="all">Semua Kategori</option>
                            @foreach ($kategoris as $kat)
                                <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Priority Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Prioritas</label>
                        <select name="priority"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none">
                            <option value="all">Semua Prioritas</option>
                            <option value="Urgent" {{ request('priority') === 'Urgent' ? 'selected' : '' }}>Mendesak
                            </option>
                            <option value="High" {{ request('priority') === 'High' ? 'selected' : '' }}>Tinggi</option>
                            <option value="Medium" {{ request('priority') === 'Medium' ? 'selected' : '' }}>Sedang</option>
                            <option value="Low" {{ request('priority') === 'Low' ? 'selected' : '' }}>Rendah</option>
                        </select>
                    </div>

                    <!-- Deadline Status Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status Deadline</label>
                        <select name="deadline_status"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none">
                            <option value="all">Semua</option>
                            <option value="overdue" {{ request('deadline_status') === 'overdue' ? 'selected' : '' }}>
                                Overdue</option>
                            <option value="upcoming" {{ request('deadline_status') === 'upcoming' ? 'selected' : '' }}>
                                Upcoming (7 hari)</option>
                            <option value="no_deadline"
                                {{ request('deadline_status') === 'no_deadline' ? 'selected' : '' }}>Tanpa Deadline
                            </option>
                        </select>
                    </div>

                    <!-- Assigned To Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Ditugaskan Kepada</label>
                        <select name="assigned_to"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none">
                            <option value="all">Semua</option>
                            @foreach ($assignedUsers as $assignee)
                                <option value="{{ $assignee }}"
                                    {{ request('assigned_to') === $assignee ? 'selected' : '' }}>{{ $assignee }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Public Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Visibilitas</label>
                        <select name="is_public"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none">
                            <option value="">Semua</option>
                            <option value="1" {{ request('is_public') === '1' ? 'selected' : '' }}>Publik</option>
                            <option value="0" {{ request('is_public') === '0' ? 'selected' : '' }}>Privat</option>
                        </select>
                    </div>

                    <!-- Date From -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Dari</label>
                        <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none">
                    </div>

                    <!-- Date To -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Sampai</label>
                        <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none">
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="bg-green-800 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-900 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Terapkan Filter
                    </button>
                    <a href="{{ route('admin.perkaras.index') }}"
                        class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-300 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Sorting Options -->
        <div class="bg-white rounded-xl shadow-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-gray-700">Urutkan berdasarkan:</span>
                <div class="flex gap-2">
                    <a href="{{ route('admin.perkaras.index', array_merge(request()->query(), ['sort_by' => 'created_at', 'sort_dir' => 'desc'])) }}"
                        class="px-3 py-1 rounded {{ request('sort_by', 'created_at') === 'created_at' ? 'bg-green-800 text-white' : 'bg-gray-200 text-gray-700' }} text-sm hover:bg-green-700 hover:text-white transition">
                        Terbaru
                    </a>
                    <a href="{{ route('admin.perkaras.index', array_merge(request()->query(), ['sort_by' => 'deadline', 'sort_dir' => 'asc'])) }}"
                        class="px-3 py-1 rounded {{ request('sort_by') === 'deadline' ? 'bg-green-800 text-white' : 'bg-gray-200 text-gray-700' }} text-sm hover:bg-green-700 hover:text-white transition">
                        Deadline
                    </a>
                    <a href="{{ route('admin.perkaras.index', array_merge(request()->query(), ['sort_by' => 'priority', 'sort_dir' => 'asc'])) }}"
                        class="px-3 py-1 rounded {{ request('sort_by') === 'priority' ? 'bg-green-800 text-white' : 'bg-gray-200 text-gray-700' }} text-sm hover:bg-green-700 hover:text-white transition">
                        Prioritas
                    </a>
                    <a href="{{ route('admin.perkaras.index', array_merge(request()->query(), ['sort_by' => 'progress', 'sort_dir' => 'desc'])) }}"
                        class="px-3 py-1 rounded {{ request('sort_by') === 'progress' ? 'bg-green-800 text-white' : 'bg-gray-200 text-gray-700' }} text-sm hover:bg-green-700 hover:text-white transition">
                        Progress
                    </a>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-green-800 to-green-900 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-bold uppercase">Nomor Perkara</th>
                            <th class="px-6 py-4 text-left text-sm font-bold uppercase">Jenis Perkara</th>
                            <th class="px-6 py-4 text-left text-sm font-bold uppercase">Prioritas</th>
                            <th class="px-6 py-4 text-left text-sm font-bold uppercase">Deadline</th>
                            <th class="px-6 py-4 text-left text-sm font-bold uppercase">Progress</th>
                            <th class="px-6 py-4 text-left text-sm font-bold uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-bold uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($perkaras as $perkara)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-gray-900">{{ $perkara->nomor_perkara }}</div>
                                    @if ($perkara->nama)
                                        <div class="text-xs text-gray-500">{{ $perkara->nama }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div>{{ $perkara->jenis_perkara }}</div>
                                    @if ($perkara->assigned_to)
                                        <div class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-user-tag"></i> {{ $perkara->assigned_to }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {!! $perkara->priority_badge !!}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($perkara->deadline)
                                        {!! $perkara->deadline_badge !!}
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $perkara->deadline->format('d M Y') }}
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($perkara->progress !== null)
                                        <div class="flex items-center gap-2">
                                            <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-green-600"
                                                    style="width: {{ $perkara->progress }}%"></div>
                                            </div>
                                            <span
                                                class="text-xs font-semibold text-gray-600">{{ $perkara->progress }}%</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-3 py-1 text-xs font-semibold rounded-full {{ $perkara->status_badge }}">
                                        {{ $perkara->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.perkaras.show', $perkara) }}"
                                            class="text-blue-600 hover:text-blue-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.perkaras.edit', $perkara) }}"
                                            class="text-yellow-600 hover:text-yellow-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('admin.perkaras.destroy', $perkara) }}"
                                            onsubmit="return confirm('Yakin hapus perkara ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
