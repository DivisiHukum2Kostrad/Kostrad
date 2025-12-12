@extends('admin.layout')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center text-sm text-gray-600 mb-4">
                <a href="{{ route('admin.users.index') }}" class="hover:text-blue-600 transition">
                    <i class="fas fa-users mr-2"></i>Manajemen User
                </a>
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <span class="text-gray-800 font-semibold">Detail User</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- User Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <!-- Avatar -->
                    <div class="flex flex-col items-center mb-6">
                        <div
                            class="h-24 w-24 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-4xl mb-4">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 text-center">{{ $user->name }}</h2>
                        <div class="mt-2">
                            {!! $user->role_badge !!}
                        </div>
                        @if (auth()->id() === $user->id)
                            <span class="mt-2 text-sm text-blue-600 font-semibold bg-blue-50 px-3 py-1 rounded-full">
                                Akun Anda
                            </span>
                        @endif
                    </div>

                    <!-- User Info -->
                    <div class="space-y-4 border-t pt-4">
                        <div>
                            <label class="text-xs text-gray-500 uppercase tracking-wider">Email</label>
                            <p class="text-sm font-medium text-gray-800 mt-1 break-all">{{ $user->email }}</p>
                        </div>

                        <div>
                            <label class="text-xs text-gray-500 uppercase tracking-wider">Bergabung</label>
                            <p class="text-sm font-medium text-gray-800 mt-1">
                                {{ $user->created_at->format('d F Y') }}
                                <span class="text-xs text-gray-500">({{ $user->created_at->diffForHumans() }})</span>
                            </p>
                        </div>

                        <div>
                            <label class="text-xs text-gray-500 uppercase tracking-wider">Terakhir Diubah</label>
                            <p class="text-sm font-medium text-gray-800 mt-1">
                                {{ $user->updated_at->format('d F Y') }}
                                <span class="text-xs text-gray-500">({{ $user->updated_at->diffForHumans() }})</span>
                            </p>
                        </div>
                    </div>

                    <!-- Permissions -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h3 class="font-semibold text-gray-800 mb-3 text-sm">
                            <i class="fas fa-shield-alt mr-2"></i>Hak Akses
                        </h3>
                        <div class="space-y-2">
                            @php
                                $permissions = [
                                    'view_cases' => 'Lihat Perkara',
                                    'manage_cases' => 'Kelola Perkara',
                                    'delete_cases' => 'Hapus Perkara',
                                    'manage_documents' => 'Kelola Dokumen',
                                    'manage_history' => 'Kelola Riwayat',
                                    'manage_categories' => 'Kelola Kategori',
                                    'manage_personnel' => 'Kelola Personel',
                                    'view_statistics' => 'Lihat Statistik',
                                    'export_data' => 'Ekspor Data',
                                    'manage_users' => 'Kelola User',
                                    'view_logs' => 'Lihat Activity Log',
                                ];
                            @endphp

                            @foreach ($permissions as $permission => $label)
                                @if ($user->hasPermission($permission))
                                    <div class="flex items-center text-xs">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        <span class="text-gray-700">{{ $label }}</span>
                                    </div>
                                @else
                                    <div class="flex items-center text-xs">
                                        <i class="fas fa-times-circle text-gray-300 mr-2"></i>
                                        <span class="text-gray-400">{{ $label }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @if (auth()->user()->hasPermission('manage_users'))
                        <div class="mt-6 space-y-2">
                            <a href="{{ route('admin.users.edit', $user) }}"
                                class="block w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold text-center transition duration-150">
                                <i class="fas fa-edit mr-2"></i>Edit User
                            </a>

                            @if (auth()->id() !== $user->id)
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-150">
                                        <i class="fas fa-trash mr-2"></i>Hapus User
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Activity Timeline -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-history text-blue-600 mr-3"></i>
                        Aktivitas Terbaru
                    </h3>

                    @if ($activityLogs->count() > 0)
                        <div class="space-y-4">
                            @foreach ($activityLogs as $log)
                                <div class="flex gap-4 pb-4 border-b border-gray-200 last:border-0">
                                    <!-- Icon -->
                                    <div class="flex-shrink-0">
                                        <div
                                            class="h-10 w-10 rounded-full {{ $log->color_class }} flex items-center justify-center">
                                            <i class="{{ $log->icon }} text-white"></i>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start mb-1">
                                            <p class="text-sm font-semibold text-gray-800">
                                                {{ ucfirst($log->action) }}
                                                <span class="font-normal text-gray-600">pada</span>
                                                {{ class_basename($log->loggable_type) }}
                                            </p>
                                            <span
                                                class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</span>
                                        </div>

                                        @if ($log->description)
                                            <p class="text-sm text-gray-600 mb-2">{{ $log->description }}</p>
                                        @endif

                                        <!-- Details -->
                                        @if ($log->old_values || $log->new_values)
                                            <details class="text-xs text-gray-500">
                                                <summary class="cursor-pointer hover:text-blue-600 transition">
                                                    Lihat Detail Perubahan
                                                </summary>
                                                <div class="mt-2 p-3 bg-gray-50 rounded border border-gray-200">
                                                    @if ($log->old_values)
                                                        <div class="mb-2">
                                                            <strong>Nilai Lama:</strong>
                                                            <pre class="mt-1 text-xs">{{ json_encode(json_decode($log->old_values), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                        </div>
                                                    @endif

                                                    @if ($log->new_values)
                                                        <div>
                                                            <strong>Nilai Baru:</strong>
                                                            <pre class="mt-1 text-xs">{{ json_encode(json_decode($log->new_values), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                        </div>
                                                    @endif
                                                </div>
                                            </details>
                                        @endif

                                        <!-- Metadata -->
                                        <div class="flex gap-4 mt-2 text-xs text-gray-400">
                                            @if ($log->ip_address)
                                                <span><i
                                                        class="fas fa-network-wired mr-1"></i>{{ $log->ip_address }}</span>
                                            @endif
                                            <span><i
                                                    class="far fa-clock mr-1"></i>{{ $log->created_at->format('d M Y H:i:s') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- View All Button -->
                        <div class="mt-6 text-center">
                            <a href="{{ route('admin.activity-logs.index', ['user_id' => $user->id]) }}"
                                class="text-blue-600 hover:text-blue-800 font-semibold text-sm transition">
                                Lihat Semua Aktivitas <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    @else
                        <div class="py-12 text-center text-gray-500">
                            <i class="fas fa-history text-6xl mb-4 text-gray-300"></i>
                            <p class="text-lg font-semibold">Belum Ada Aktivitas</p>
                            <p class="text-sm mt-2">User ini belum melakukan aktivitas apapun</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
