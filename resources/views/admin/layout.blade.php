{{-- resources/views/admin/layout.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - SIPERKARA DIV-2</title>
    <script>
        // Prevent flash of unstyled content - Load theme before page renders
        (function() {
            const savedMode = localStorage.getItem('siperkara_dark_mode');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedMode === 'dark' || (!savedMode && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#166534',
                        secondary: '#15803d'
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/enhanced.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Dark mode transitions */
        * {
            transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-green-900 via-green-800 to-green-900 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-18 py-3">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 group">
                        <div
                            class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-md group-hover:shadow-lg transition-shadow">
                            <svg class="w-7 h-7 text-green-800" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-white font-bold text-lg group-hover:text-green-200 transition-colors">
                                DivisiHukum2Kostrad</h1>
                            <p class="text-green-200 text-xs">SIPERKARA DIV-2</p>
                        </div>
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('admin.dashboard') }}"
                        class="text-white hover:text-green-300 font-medium transition">Dashboard</a>
                    <a href="{{ route('admin.perkaras.index') }}"
                        class="text-white hover:text-green-300 font-medium transition">Perkara</a>
                    <a href="{{ route('admin.personels.index') }}"
                        class="text-white hover:text-green-300 font-medium transition">Personel</a>
                    @if (auth()->user()->hasPermission('manage_users'))
                        <a href="{{ route('admin.users.index') }}"
                            class="text-white hover:text-green-300 font-medium transition">User</a>
                    @endif
                    @if (auth()->user()->hasPermission('view_logs'))
                        <a href="{{ route('admin.activity-logs.index') }}"
                            class="text-white hover:text-green-300 font-medium transition">Log Aktivitas</a>
                    @endif
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Dark Mode Toggle -->
                    <button data-dark-mode-toggle class="p-2 text-white hover:bg-green-700 rounded-lg transition"
                        title="Toggle dark mode (Ctrl+/)">
                        <svg data-theme-icon="sun" class="h-5 w-5" style="display: none;" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg data-theme-icon="moon" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <!-- Notification Bell -->
                    <div class="relative" x-data="{ open: false, count: {{ auth()->user()->unread_notifications_count }} }">
                        <button @click="open = !open"
                            class="relative p-2 text-white hover:bg-green-700 rounded-lg transition">
                            <i class="fas fa-bell text-xl"></i>
                            <span x-show="count > 0"
                                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold"
                                x-text="count > 99 ? '99+' : count"></span>
                        </button>

                        <!-- Dropdown -->
                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95"
                            class="absolute right-0 mt-2 w-96 bg-white dark:bg-gray-800 rounded-lg shadow-xl z-50 max-h-96 overflow-y-auto"
                            style="display: none;">

                            <!-- Header -->
                            <div
                                class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                <h3 class="font-semibold text-gray-900 dark:text-white">Notifikasi</h3>
                                <a href="{{ route('admin.notifications.index') }}"
                                    class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                    Lihat Semua
                                </a>
                            </div>

                            <!-- Notifications List -->
                            <div id="notification-list">
                                <!-- Notifications will be loaded here via JS -->
                                <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-spinner fa-spin text-2xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-right hidden md:block">
                        <p class="text-white font-semibold text-sm">{{ auth()->user()->name }}</p>
                        <div class="mt-1">{!! auth()->user()->role_badge !!}</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition duration-300">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    @yield('content')

    <!-- Notification Script -->
    <script>
        // Load notifications when bell is clicked
        document.addEventListener('alpine:initialized', () => {
            let notificationsLoaded = false;

            document.querySelector('[x-data]').addEventListener('click', function(e) {
                if (e.target.closest('button') && !notificationsLoaded) {
                    loadNotifications();
                    notificationsLoaded = true;
                }
            });
        });

        function loadNotifications() {
            fetch('{{ route('admin.notifications.unread') }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('notification-list');

                    if (data.notifications.length === 0) {
                        container.innerHTML = `
                        <div class="p-8 text-center text-gray-500">
                            <i class="fas fa-bell-slash text-3xl mb-2"></i>
                            <p class="text-sm">Tidak ada notifikasi baru</p>
                        </div>
                    `;
                        return;
                    }

                    container.innerHTML = data.notifications.map(notification => `
                    <div class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full ${getColorClass(notification.type)} flex items-center justify-center">
                                    ${getIcon(notification.type)}
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900">${notification.subject}</p>
                                <p class="text-xs text-gray-600 mt-0.5">${notification.message}</p>
                                <p class="text-xs text-gray-500 mt-1">${timeAgo(notification.created_at)}</p>
                                ${notification.data && notification.data.perkara_id ? `
                                                <a href="/admin/perkara/${notification.data.perkara_id}"
                                                   class="text-xs text-blue-600 hover:text-blue-800 mt-1 inline-block">
                                                    Lihat Detail <i class="fas fa-arrow-right"></i>
                                                </a>
                                            ` : ''}
                            </div>
                            <button onclick="markAsRead(${notification.id})"
                                    class="flex-shrink-0 text-blue-600 hover:text-blue-800">
                                <i class="fas fa-check text-sm"></i>
                            </button>
                        </div>
                    </div>
                `).join('');
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    document.getElementById('notification-list').innerHTML = `
                    <div class="p-4 text-center text-red-500">
                        <i class="fas fa-exclamation-circle text-2xl mb-2"></i>
                        <p class="text-sm">Gagal memuat notifikasi</p>
                    </div>
                `;
                });
        }

        function markAsRead(notificationId) {
            fetch(`/admin/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadNotifications();
                        // Update counter
                        const countElement = document.querySelector('[x-data] span[x-text]');
                        if (countElement) {
                            const currentCount = parseInt(countElement.textContent) || 0;
                            const newCount = Math.max(0, currentCount - 1);
                            countElement.setAttribute('x-data', `{ count: ${newCount} }`);
                            if (newCount === 0) {
                                countElement.style.display = 'none';
                            }
                        }
                    }
                });
        }

        function getIcon(type) {
            const icons = {
                'case_assigned': '<i class="fas fa-clipboard-check text-white"></i>',
                'status_changed': '<i class="fas fa-sync-alt text-white"></i>',
                'document_uploaded': '<i class="fas fa-file-upload text-white"></i>',
                'deadline_reminder': '<i class="fas fa-clock text-white"></i>'
            };
            return icons[type] || '<i class="fas fa-bell text-white"></i>';
        }

        function getColorClass(type) {
            const colors = {
                'case_assigned': 'bg-blue-500',
                'status_changed': 'bg-purple-500',
                'document_uploaded': 'bg-green-500',
                'deadline_reminder': 'bg-red-500'
            };
            return colors[type] || 'bg-gray-500';
        }

        function timeAgo(datetime) {
            const now = new Date();
            const past = new Date(datetime);
            const seconds = Math.floor((now - past) / 1000);

            if (seconds < 60) return 'Baru saja';
            if (seconds < 3600) return Math.floor(seconds / 60) + ' menit lalu';
            if (seconds < 86400) return Math.floor(seconds / 3600) + ' jam lalu';
            if (seconds < 604800) return Math.floor(seconds / 86400) + ' hari lalu';

            return past.toLocaleDateString('id-ID');
        }
    </script>

    <!-- UI/UX Enhancement Scripts -->
    <script src="{{ asset('js/darkmode.js') }}"></script>
    <script src="{{ asset('js/toast.js') }}"></script>
    <script src="{{ asset('js/loading.js') }}"></script>
    <script src="{{ asset('js/shortcuts.js') }}"></script>
    <script src="{{ asset('js/dragdrop.js') }}"></script>

    <!-- Show success/error messages as toasts -->
    @if (session('success'))
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                window.showSuccess('{{ session('success') }}', 5000);
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                window.showError('{{ session('error') }}', 5000);
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                @foreach ($errors->all() as $error)
                    window.showError('{{ $error }}', 5000);
                @endforeach
            });
        </script>
    @endif

    @stack('scripts')
</body>

</html>
