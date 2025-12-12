@extends('admin.layout')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-900">Log Aktivitas</h2>
            <p class="text-gray-600 mt-1">Riwayat semua aktivitas dalam sistem</p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <form method="GET" action="{{ route('admin.activity-logs.index') }}"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari deskripsi..."
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none">
                </div>

                <!-- Log Type Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Aktivitas</label>
                    <select name="log_type"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none">
                        <option value="">Semua Tipe</option>
                        <option value="created" {{ request('log_type') === 'created' ? 'selected' : '' }}>Dibuat</option>
                        <option value="updated" {{ request('log_type') === 'updated' ? 'selected' : '' }}>Diperbarui
                        </option>
                        <option value="deleted" {{ request('log_type') === 'deleted' ? 'selected' : '' }}>Dihapus</option>
                        <option value="status_changed" {{ request('log_type') === 'status_changed' ? 'selected' : '' }}>
                            Status Berubah</option>
                    </select>
                </div>

                <!-- User Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">User</label>
                    <select name="user_id"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none">
                        <option value="">Semua User</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none">
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none">
                </div>

                <!-- Buttons -->
                <div class="lg:col-span-2 flex gap-3">
                    <button type="submit"
                        class="bg-green-800 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-900 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.activity-logs.index') }}"
                        class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-300 transition">Reset</a>
                </div>
            </form>
        </div>

        <!-- Timeline -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Timeline Aktivitas</h3>

            @if ($logs->count() > 0)
                <div class="relative">
                    <!-- Timeline Line -->
                    <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-gray-200"></div>

                    <!-- Timeline Items -->
                    <div class="space-y-6">
                        @foreach ($logs as $log)
                            <div class="relative flex items-start gap-4">
                                <!-- Icon -->
                                <div
                                    class="flex-shrink-0 w-16 h-16 rounded-full {{ $log->color_class }} flex items-center justify-center text-2xl font-bold z-10 ring-4 ring-white">
                                    {{ $log->icon }}
                                </div>

                                <!-- Content -->
                                <div class="flex-1 bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <p class="font-bold text-gray-900">{{ $log->description }}</p>
                                            <p class="text-sm text-gray-600 mt-1">
                                                oleh <span class="font-semibold">{{ $log->user->name ?? 'System' }}</span>
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm text-gray-500">{{ $log->created_at->format('d M Y') }}</p>
                                            <p class="text-xs text-gray-400">{{ $log->created_at->format('H:i') }} WIB</p>
                                        </div>
                                    </div>

                                    @if ($log->loggable)
                                        <div class="mt-2">
                                            <span
                                                class="inline-block px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                                                {{ class_basename($log->loggable_type) }}
                                                @if ($log->loggable_type === 'App\\Models\\Perkara')
                                                    : {{ $log->loggable->nomor_perkara ?? 'N/A' }}
                                                @endif
                                            </span>
                                        </div>
                                    @endif

                                    @if ($log->old_values || $log->new_values)
                                        <div class="mt-3">
                                            <button onclick="toggleDetails({{ $log->id }})"
                                                class="text-sm text-blue-600 hover:text-blue-800 font-semibold">
                                                Lihat Detail →
                                            </button>
                                            <div id="details-{{ $log->id }}"
                                                class="hidden mt-2 p-3 bg-white rounded border border-gray-200">
                                                @if ($log->old_values)
                                                    <div class="mb-2">
                                                        <p class="text-xs font-semibold text-gray-600 mb-1">Data Lama:</p>
                                                        <pre class="text-xs text-gray-700 overflow-x-auto">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                    </div>
                                                @endif
                                                @if ($log->new_values)
                                                    <div>
                                                        <p class="text-xs font-semibold text-gray-600 mb-1">Data Baru:</p>
                                                        <pre class="text-xs text-gray-700 overflow-x-auto">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $logs->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="mt-4 text-gray-500">Belum ada log aktivitas</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        function toggleDetails(id) {
            const element = document.getElementById('details-' + id);
            element.classList.toggle('hidden');
        }
    </script>
@endsection
