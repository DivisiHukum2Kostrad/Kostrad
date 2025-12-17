@extends('admin.layout')

@section('title', 'Notifikasi')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notifikasi</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Kelola semua notifikasi Anda</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.notifications.preferences') }}"
                            class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                            <i class="fas fa-cog mr-2"></i>Pengaturan
                        </a>
                        @if ($notifications->where('is_read', false)->count() > 0)
                            <form action="{{ route('admin.notifications.markAllAsRead') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 dark:bg-blue-700 text-white rounded-lg hover:bg-blue-700 dark:hover:bg-blue-600 transition">
                                    <i class="fas fa-check-double mr-2"></i>Tandai Semua Dibaca
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div
                    class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-400 px-4 py-3 rounded-lg mb-6">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            <!-- Notifications List -->
            <div class="space-y-3">
                @forelse($notifications as $notification)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-md transition {{ !$notification->is_read ? 'border-l-4 border-blue-500' : '' }}">
                        <div class="p-5">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-4 flex-1">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-12 h-12 rounded-full {{ $notification->color_class }} flex items-center justify-center text-xl {{ $notification->icon_color }}">
                                            {!! $notification->icon !!}
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                                {{ $notification->subject }}
                                            </h3>
                                            @if (!$notification->is_read)
                                                <span
                                                    class="px-2 py-1 text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-full">
                                                    Baru
                                                </span>
                                            @endif
                                        </div>
                                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $notification->message }}
                                        </p>
                                        <div class="mt-2 flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                                            <span>
                                                <i class="far fa-clock mr-1"></i>
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                            @if ($notification->is_emailed)
                                                <span>
                                                    <i class="far fa-envelope mr-1"></i>
                                                    Email dikirim
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Action Button -->
                                        @if (isset($notification->data['perkara_id']))
                                            <a href="{{ route('admin.perkaras.show', $notification->data['perkara_id']) }}"
                                                class="inline-block mt-3 text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                                                Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 ml-4">
                                    @if (!$notification->is_read)
                                        <form action="{{ route('admin.notifications.markAsRead', $notification->id) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg"
                                                title="Tandai dibaca">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.notifications.destroy', $notification->id) }}"
                                        method="POST" onsubmit="return confirm('Hapus notifikasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
                        <i class="fas fa-bell-slash text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Tidak Ada Notifikasi</h3>
                        <p class="text-gray-600 dark:text-gray-400">Anda belum memiliki notifikasi apapun</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($notifications->hasPages())
                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
