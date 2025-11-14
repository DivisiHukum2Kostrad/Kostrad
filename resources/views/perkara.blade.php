{{-- perkara.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Perkara Publik - Divisi 2 Kostrad</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }
        .table-hover:hover {
            background-color: rgba(34, 197, 94, 0.05);
        }

        /* ============================================
           🔴 GANTI BACKGROUND IMAGE DI SINI (OPTIONAL)
           ============================================
           Ganti URL dengan path file lokal Anda
           Contoh: url('/images/tni-kostrad-bg.jpg')
           ============================================ */
        .header-bg {
            background-image: url('https://images.unsplash.com/photo-1556742044-3c52d6e88c62?q=80&w=2070');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .header-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(30, 58, 32, 0.93) 0%, rgba(45, 80, 22, 0.91) 100%);
        }

        .header-content {
            position: relative;
            z-index: 10;
        }

        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            height: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #166534;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #14532d;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-green-900 via-green-800 to-green-900 shadow-lg fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-4">
                    <!-- ============================================
                         🔴 GANTI LOGO DI SINI (OPTIONAL)
                         ============================================
                         Ganti dengan: <img src="/images/logo-kostrad.png" class="w-14 h-14" alt="Logo">
                         ============================================ -->
                    <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-md">
                        <svg class="w-8 h-8 text-green-800" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-white font-bold text-lg">SIPERKARA DIV-2</h1>
                        <p class="text-green-200 text-xs">Divisi 2 Kostrad</p>
                    </div>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="index.html" class="text-white hover:text-green-300 font-medium transition duration-300">Beranda</a>
                    <a href="#tentang" class="text-white hover:text-green-300 font-medium transition duration-300">Tentang Sistem</a>
                    <a href="#" class="text-green-300 font-bold border-b-2 border-green-300">Data Perkara Publik</a>
                    <a href="#kontak" class="text-white hover:text-green-300 font-medium transition duration-300">Kontak</a>
                </div>
                <button class="md:hidden text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Header Section -->
    <section class="pt-32 pb-16 header-bg">
        <div class="header-content max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-block mb-4">
                    <span class="bg-green-700 text-green-100 px-5 py-2 rounded-full text-xs font-semibold tracking-wide uppercase">
                        Data Publik
                    </span>
                </div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4">
                    Data Perkara Publik
                </h1>
                <p class="text-lg text-green-100 max-w-2xl mx-auto">
                    Informasi perkara yang telah diselesaikan dan dapat diakses oleh masyarakat umum
                </p>
            </div>
        </div>
    </section>

    <!-- Filter & Search Section -->
    <section class="py-8 bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <!-- Search Bar -->
                <div class="w-full md:w-1/2">
                    <div class="relative">
                        <input
                            type="text"
                            placeholder="Cari nomor perkara, jenis perkara..."
                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none transition duration-300"
                        >
                        <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Filters -->
                <div class="flex flex-wrap gap-3 w-full md:w-auto">
                    <select class="px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none transition duration-300 bg-white">
                        <option>Semua Status</option>
                        <option>Selesai</option>
                        <option>Dalam Proses</option>
                    </select>

                    <select class="px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none transition duration-300 bg-white">
                        <option>Semua Tahun</option>
                        <option>2024</option>
                        <option>2023</option>
                        <option>2022</option>
                    </select>

                    <button class="px-6 py-3 bg-green-800 text-white rounded-lg font-semibold hover:bg-green-900 transition duration-300 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filter
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Table Section -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Table Header Info -->
            <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Daftar Perkara</h2>
                    <p class="text-gray-600 mt-1">Menampilkan 1-15 dari 248 data perkara</p>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-green-800 to-green-900 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Nomor Perkara</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Jenis Perkara</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Tanggal Masuk</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Tanggal Selesai</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Dokumentasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <!-- Row 1 -->
                            <tr class="table-hover transition duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">1</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">PERK/DIV2/2024/001</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">Pelanggaran Disiplin Militer</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                        Disiplin
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">10 Jan 2024</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">15 Jan 2024</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Selesai
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button class="text-red-600 hover:text-red-700 font-semibold text-sm flex items-center gap-1 transition duration-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Download PDF
                                    </button>
                                </td>
                            </tr>

                            <!-- Row 2 -->
                            <tr class="table-hover transition duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">2</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">PERK/DIV2/2024/002</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">Pelanggaran Administratif</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Administrasi
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">18 Jan 2024</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">22 Feb 2024</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Selesai
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button class="text-red-600 hover:text-red-700 font-semibold text-sm flex items-center gap-1 transition duration-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Download PDF
                                    </button>
                                </td>
                            </tr>

                            <!-- Row 3 -->
                            <tr class="table-hover transition duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">3</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">PERK/DIV2/2024/003</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">Ketidakhadiran Tanpa Izin</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                        Disiplin
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">05 Mar 2024</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">10 Mar 2024</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Selesai
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button class="text-red-600 hover:text-red-700 font-semibold text-sm flex items-center gap-1 transition duration-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Download PDF
                                    </button>
                                </td>
                            </tr>

                            <!-- Row 4 -->
                            <tr class="table-hover transition duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">4</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">PERK/DIV2/2024/004</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">Kehilangan Aset Negara</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Pidana
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">12 Mar 2024</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">-</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Proses
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-gray-400 text-sm">Belum Tersedia</span>
                                </td>
                            </tr>

                            <!-- Row 5 -->
                            <tr class="table-hover transition duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">5</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">PERK/DIV2/2024/005</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">Penyalahgunaan Wewenang</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Pidana
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">20 Mar 2024</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">28 Apr 2024</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Selesai
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button class="text-red-600 hover:text-red-700 font-semibold text-sm flex items-center gap-1 transition duration-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Download PDF
                                    </button>
                                </td>
                            </tr>

                            <!-- Row 6 -->
                            <tr class="table-hover transition duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">6</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">PERK/DIV2/2024/006</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">Pelanggaran Tata Tertib</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                        Disiplin
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">02 Apr 2024</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">08 Apr 2024</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Selesai
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button class="text-red-600 hover:text-red-700 font-semibold text-sm flex items-center gap-1 transition duration-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Download PDF
                                    </button>
                                </td>
                            </tr>

                            <!-- Row 7 -->
                            <tr class="table-hover transition duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">7</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">PERK/DIV2/2024/007</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">Kesalahan Prosedur Operasional</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Administrasi
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">15 Apr 2024</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">25 Mei 2024</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Selesai
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button class="text-red-600 hover:text-red-700 font-semibold text-sm flex items-center gap-1 transition duration-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Download PDF
                                    </button>
                                </td>
                            </tr>

                            <!-- Row 8 -->
                            <tr class="table-hover transition duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">8</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">PERK/DIV2/2024/008</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">Pemalsuan Dokumen</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Pidana
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">10 Mei 2024</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">-</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Proses
