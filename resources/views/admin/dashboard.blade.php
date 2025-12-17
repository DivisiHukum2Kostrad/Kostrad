{{-- resources/views/admin/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SIPERKARA DIV-2</title>
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
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        * {
            transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
        }

        .chart-container {
            position: relative;
            height: 250px;
            max-height: 250px;
        }

        .recent-cases-container {
            max-height: 350px;
            overflow-y: auto;
        }

        .recent-cases-container::-webkit-scrollbar {
            width: 6px;
        }

        .recent-cases-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .recent-cases-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .recent-cases-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-green-900 via-green-800 to-green-900 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-18 py-3">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('landing') }}" class="flex items-center space-x-3 group">
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
                        class="text-green-300 font-bold border-b-2 border-green-300 pb-1">Dashboard</a>
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
                    <button id="darkModeToggle" class="p-2 text-white hover:bg-green-700 rounded-lg transition"
                        title="Toggle dark mode">
                        <svg id="sunIcon" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg id="moonIcon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <div class="text-right hidden lg:block">
                        <p class="text-white font-semibold text-sm">{{ auth()->user()->name }}</p>
                        <p class="text-green-200 text-xs">{{ auth()->user()->pangkat }} - {{ auth()->user()->jabatan }}
                        </p>
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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Message -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard Analytics</h2>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Sistem Informasi Perkara Divisi 2 Kostrad - Analisis
                Komprehensif</p>
        </div>

        <!-- Enhanced Stats Cards with Additional Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Perkara -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-semibold uppercase">Total Perkara</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total_perkara'] }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Semua kasus</p>
                    </div>
                    <div
                        class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Perkara Selesai -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-semibold uppercase">Selesai</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                            {{ $stats['perkara_selesai'] }}</p>
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1 font-semibold">
                            {{ $stats['completion_rate'] }}%
                            completion
                            rate</p>
                    </div>
                    <div
                        class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Perkara Proses -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-l-4 border-yellow-500 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-semibold uppercase">Dalam Proses</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['perkara_proses'] }}
                        </p>
                        <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1 font-semibold">Rata-rata
                            {{ $stats['avg_completion_days'] }} hari selesai</p>
                    </div>
                    <div
                        class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Perkara Bulan Ini -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-l-4 border-purple-500 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-semibold uppercase">Bulan Ini</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                            {{ $stats['perkara_bulan_ini'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ now()->format('F Y') }}</p>
                    </div>
                    <div
                        class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('admin.perkaras.create') }}"
                class="bg-gradient-to-br from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-xl shadow-lg p-6 transition duration-300 transform hover:scale-105">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-lg">Tambah Perkara</p>
                        <p class="text-green-100 text-sm">Input perkara baru</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.perkaras.index') }}"
                class="bg-gradient-to-br from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl shadow-lg p-6 transition duration-300 transform hover:scale-105">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-lg">Kelola Perkara</p>
                        <p class="text-blue-100 text-sm">Lihat & edit perkara</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.personels.index') }}"
                class="bg-gradient-to-br from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-xl shadow-lg p-6 transition duration-300 transform hover:scale-105">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-lg">Kelola Personel</p>
                        <p class="text-indigo-100 text-sm">Manajemen personel</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Monthly Trend Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Tren Perkara 6 Bulan Terakhir</h3>
                <div class="chart-container">
                    <canvas id="monthlyTrendChart"></canvas>
                </div>
            </div>

            <!-- Category Distribution Pie Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Distribusi per Kategori</h3>
                <div class="chart-container">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Additional Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Status Distribution -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Distribusi Status</h3>
                <div class="chart-container" style="height: 200px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <!-- Yearly Comparison -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Perbandingan Tahunan</h3>
                <div class="chart-container" style="height: 200px;">
                    <canvas id="yearlyChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Cases & Top Categories -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Perkara Terbaru -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Perkara Terbaru</h3>
                <div class="space-y-3 recent-cases-container">
                    @forelse($latest_perkaras as $perkara)
                        <div
                            class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $perkara->nomor_perkara }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $perkara->jenis_perkara }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $perkara->tanggal_masuk->format('d M Y') }}
                                </p>
                            </div>
                            <span
                                class="px-3 py-1 text-xs font-semibold rounded-full {{ $perkara->status == 'Selesai' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400' : 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400' }}">
                                {{ $perkara->status }}
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">Belum ada perkara</p>
                    @endforelse
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('admin.perkaras.index') }}"
                        class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-semibold text-sm">
                        Lihat Semua Perkara →
                    </a>
                </div>
            </div>

            <!-- Top Categories -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Top 5 Kategori</h3>
                <div class="space-y-4">
                    @foreach ($top_categories as $index => $kategori)
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center space-x-2">
                                    <span
                                        class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                        {{ $index + 1 }}
                                    </span>
                                    <span
                                        class="font-semibold text-gray-900 dark:text-white">{{ $kategori->nama }}</span>
                                </div>
                                <span
                                    class="text-gray-600 dark:text-gray-400 font-bold">{{ $kategori->perkaras_count }}
                                    kasus</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-500"
                                    style="width: {{ $stats['total_perkara'] > 0 ? ($kategori->perkaras_count / $stats['total_perkara']) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script>
        // Monthly Trend Chart
        const monthlyCtx = document.getElementById('monthlyTrendChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: @json($monthly_data['labels']),
                datasets: [{
                        label: 'Perkara Masuk',
                        data: @json($monthly_data['masuk']),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Perkara Selesai',
                        data: @json($monthly_data['selesai']),
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Category Distribution Pie Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: @json($perkara_per_kategori->pluck('nama')),
                datasets: [{
                    data: @json($perkara_per_kategori->pluck('perkaras_count')),
                    backgroundColor: [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(251, 191, 36)',
                        'rgb(239, 68, 68)',
                        'rgb(139, 92, 246)',
                        'rgb(236, 72, 153)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Status Distribution Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'bar',
            data: {
                labels: @json($status_distribution->pluck('status')),
                datasets: [{
                    label: 'Jumlah Perkara',
                    data: @json($status_distribution->pluck('total')),
                    backgroundColor: [
                        'rgb(251, 191, 36)',
                        'rgb(16, 185, 129)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Yearly Comparison Chart
        const yearlyCtx = document.getElementById('yearlyChart').getContext('2d');
        new Chart(yearlyCtx, {
            type: 'bar',
            data: {
                labels: [@json($yearly_comparison['last_year_label']), @json($yearly_comparison['current_year_label'])],
                datasets: [{
                    label: 'Jumlah Perkara',
                    data: [@json($yearly_comparison['last_year']), @json($yearly_comparison['current_year'])],
                    backgroundColor: [
                        'rgba(156, 163, 175, 0.8)',
                        'rgba(59, 130, 246, 0.8)'
                    ],
                    borderColor: [
                        'rgb(156, 163, 175)',
                        'rgb(59, 130, 246)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <!-- Dark Mode Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeToggle = document.getElementById('darkModeToggle');
            const sunIcon = document.getElementById('sunIcon');
            const moonIcon = document.getElementById('moonIcon');
            const html = document.documentElement;

            // Function to update icons based on current mode
            function updateIcons() {
                if (html.classList.contains('dark')) {
                    sunIcon.classList.remove('hidden');
                    moonIcon.classList.add('hidden');
                } else {
                    sunIcon.classList.add('hidden');
                    moonIcon.classList.remove('hidden');
                }
            }

            // Initialize icons on page load
            updateIcons();

            // Toggle dark mode
            darkModeToggle.addEventListener('click', function() {
                html.classList.toggle('dark');
                const isDark = html.classList.contains('dark');
                localStorage.setItem('siperkara_dark_mode', isDark ? 'dark' : 'light');
                updateIcons();
            });

            // Keyboard shortcut: Ctrl+/
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey && e.key === '/') {
                    e.preventDefault();
                    darkModeToggle.click();
                }
            });
        });
    </script>
</body>

</html>
