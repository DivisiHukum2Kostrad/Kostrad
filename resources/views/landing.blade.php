{{-- landing.blade.php --}}
<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Perkara - Divisi 2 Kostrad</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, rgba(30, 58, 32, 0.95) 0%, rgba(45, 80, 22, 0.95) 50%, rgba(26, 77, 46, 0.95) 100%);
        }

        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .military-pattern {
            background-image:
                repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255, 255, 255, .03) 10px, rgba(255, 255, 255, .03) 20px);
        }

        /* ============================================
           🔴 GANTI BACKGROUND IMAGE DI SINI
           ============================================
           Ganti URL di bawah dengan path file lokal Anda
           Contoh: url('/images/tni-kostrad-bg.jpg')
           ============================================ */
        .hero-background {
            background-image: url('https://images.unsplash.com/photo-1556742044-3c52d6e88c62?q=80&w=2070');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }

        .hero-background::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(30, 58, 32, 0.92) 0%, rgba(45, 80, 22, 0.90) 50%, rgba(26, 77, 46, 0.92) 100%);
        }

        .hero-content {
            position: relative;
            z-index: 10;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-green-900 via-green-800 to-green-900 shadow-lg fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-18 py-3">
                <div class="flex items-center space-x-4">
                    <!-- ============================================
                         🔴 GANTI LOGO DI SINI (OPTIONAL)
                         ============================================
                         Ganti <div> dengan <img> jika ada logo lokal
                         Contoh: <img src="/images/logo-kostrad.png" class="w-12 h-12" alt="Logo Kostrad">
                         ============================================ -->
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-md">
                        <svg class="w-7 h-7 text-green-800" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-white font-bold text-lg">DivisiHukum2Kostrad</h1>
                        <p class="text-green-200 text-xs">SIPERKARA DIV-2</p>
                    </div>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="#beranda"
                        class="text-white hover:text-green-300 font-medium transition duration-300">Beranda</a>
                    <a href="#tentang"
                        class="text-white hover:text-green-300 font-medium transition duration-300">Tentang Sistem</a>
                    <a href="#data-perkara"
                        class="text-white hover:text-green-300 font-medium transition duration-300">Data Perkara
                        Publik</a>
                    <a href="#kontak"
                        class="text-white hover:text-green-300 font-medium transition duration-300">Kontak</a>
                </div>
                <button class="md:hidden text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Background Image -->
    <!-- ============================================
         🔴 BACKGROUND IMAGE HERO SECTION
         ============================================
         Background image dikontrol oleh class .hero-background
         di bagian <style> di atas (line 28-46)
         ============================================ -->
    <section id="beranda" class="pt-32 pb-20 hero-background military-pattern">
        <div class="hero-content max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-block mb-6">
                    <span
                        class="bg-green-700 text-green-100 px-6 py-2 rounded-full text-sm font-semibold tracking-wide uppercase">
                        Sistem Informasi Resmi TNI AD
                    </span>
                </div>
                <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 leading-tight">
                    Digitalisasi Pencatatan dan<br />Penelusuran Perkara Militer
                </h1>
                <p class="text-xl text-green-100 mb-10 max-w-3xl mx-auto leading-relaxed">
                    Sistem informasi terintegrasi untuk mencatat, mengelola, dan menelusuri data perkara di lingkungan
                    Divisi 2 Kostrad dengan standar keamanan tinggi dan transparansi yang terukur.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#data-perkara"
                        class="bg-white text-green-900 px-8 py-4 rounded-lg font-bold text-lg hover:bg-green-50 transition duration-300 shadow-xl hover:shadow-2xl">
                        Lihat Data Perkara
                    </a>
                    <a href="#tentang"
                        class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-white hover:text-green-900 transition duration-300">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Sistem -->
    <section id="tentang" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Tentang Sistem</h2>
                <div class="w-24 h-1 bg-green-800 mx-auto"></div>
            </div>

            <div class="grid md:grid-cols-2 gap-12 items-center mb-16">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Mengapa Sistem Ini Dibuat?</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Sistem Informasi Pencatatan dan Penelusuran Duduk Perkara dikembangkan untuk menjawab kebutuhan
                        modernisasi administrasi hukum di lingkungan Divisi 2 Kostrad. Dengan sistem digital ini,
                        proses pencatatan dan penelusuran perkara menjadi lebih cepat, akurat, dan dapat
                        dipertanggungjawabkan.
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        Transparansi dan akuntabilitas menjadi prioritas utama dalam mendukung penegakan hukum militer
                        yang profesional dan berintegritas.
                    </p>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 p-8 rounded-2xl shadow-lg">
                    <h4 class="text-xl font-bold text-gray-800 mb-6 text-center">Statistik Data Publik</h4>
                    <p class="text-sm text-gray-600 text-center mb-4">Data perkara yang dapat diakses masyarakat</p>
                    <div class="grid grid-cols-2 gap-6 text-center">
                        <div class="bg-white p-6 rounded-xl shadow">
                            <div class="text-4xl font-bold text-green-800 mb-2">{{ $total_perkaras }}</div>
                            <div class="text-gray-600 text-sm">Total Perkara Publik</div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow">
                            <div class="text-4xl font-bold text-blue-600 mb-2">{{ $perkaras_selesai }}</div>
                            <div class="text-gray-600 text-sm">Selesai</div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow">
                            <div class="text-4xl font-bold text-yellow-600 mb-2">{{ $perkaras_proses }}</div>
                            <div class="text-gray-600 text-sm">Dalam Proses</div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow">
                            <div class="text-4xl font-bold text-purple-600 mb-2">
                                {{ number_format(($perkaras_selesai / max($total_perkaras, 1)) * 100, 1) }}%</div>
                            <div class="text-gray-600 text-sm">Tingkat Penyelesaian</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keunggulan -->
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white border-2 border-gray-200 rounded-2xl p-8 hover-lift">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-green-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Efisiensi Tinggi</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Pengolahan data perkara yang cepat dan otomatis mengurangi waktu administrasi hingga 70%,
                        memungkinkan personel fokus pada analisis dan penyelesaian kasus.
                    </p>
                </div>

                <div class="bg-white border-2 border-gray-200 rounded-2xl p-8 hover-lift">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-green-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Keamanan Maksimal</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Data dienkripsi dengan standar militer dan dilengkapi sistem otorisasi berlapis untuk
                        melindungi informasi sensitif dari akses yang tidak berwenang.
                    </p>
                </div>

                <div class="bg-white border-2 border-gray-200 rounded-2xl p-8 hover-lift">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-green-800" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Transparansi Publik</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Masyarakat dapat mengakses informasi perkara yang telah diselesaikan, meningkatkan
                        kepercayaan publik terhadap proses hukum militer yang akuntabel.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Preview Data Perkara -->
    <section id="data-perkara" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Data Perkara Publik</h2>
                <div class="w-24 h-1 bg-green-800 mx-auto mb-4"></div>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Berikut adalah contoh data perkara yang telah diselesaikan dan dapat diakses oleh publik
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-green-800 to-green-900 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Nomor
                                    Perkara</th>
                                <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Jenis
                                    Perkara</th>
                                <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Kategori
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($preview_perkaras as $perkara)
                                <tr class="hover:bg-gray-50 transition duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900">{{ $perkara->nomor_perkara }}
                                        </div>
                                        @if ($perkara->nama)
                                            <div class="text-xs text-gray-500">{{ $perkara->nama }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $perkara->jenis_perkara }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($perkara->kategori)
                                            <span class="px-2 py-1 text-xs font-semibold rounded"
                                                style="background-color: {{ $perkara->kategori->color }}33; color: {{ $perkara->kategori->color }}">
                                                {{ $perkara->kategori->nama }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600">
                                            {{ $perkara->tanggal_pendaftaran ? $perkara->tanggal_pendaftaran->format('d M Y') : '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $perkara->status_badge }}">
                                            {{ $perkara->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        Belum ada data perkara publik yang tersedia
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-600">Menampilkan 3 dari total perkara yang diselesaikan</p>
                        <a href="{{ route('perkara.public') }}"
                            class="bg-green-800 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-900 transition duration-300">
                            Lihat Semua Data
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kontak -->
    <section id="kontak" class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Hubungi Kami</h2>
                <div class="w-24 h-1 bg-green-800 mx-auto"></div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-8 shadow-lg">
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-green-800 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-2">Alamat</h4>
                            <p class="text-gray-700">Seksi Hukum Divisi 2 Kostrad<br />Song-song, Jl. Raya Singosari,
                                Ardimulyo, Kec. Singosari, Kab. Malang,
                                AD<br />Jawa Timur, Indonesia</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-green-800 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-2">Email</h4>
                            <p class="text-gray-700">hukumdivif2@gmail.com<br />hukumdivif2@gmail.com
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-purple-600 via-pink-600 to-orange-500 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-2">Instagram</h4>
                            <a href="https://instagram.com/hukum_divif2kostrad" target="_blank"
                                class="text-gray-700 hover:text-pink-600 transition duration-300">
                                @hukum_divif2kostrad
                            </a>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-green-800 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-2">Jam Operasional</h4>
                            <p class="text-gray-700">Senin - Jumat: 08.00 - 16.00 WIB<br />Sabtu - Minggu: Tutup</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-green-900 via-green-800 to-green-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8 mb-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">SIPERKARA DIV-2</h3>
                    <p class="text-green-200 text-sm leading-relaxed">
                        Sistem Informasi Pencatatan dan Penelusuran Duduk Perkara Divisi 2 Kostrad.
                        Mendukung transparansi dan akuntabilitas proses hukum militer.
                    </p>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Tautan Cepat</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#beranda"
                                class="text-green-200 hover:text-white transition duration-300">Beranda</a></li>
                        <li><a href="#tentang" class="text-green-200 hover:text-white transition duration-300">Tentang
                                Sistem</a></li>
                        <li><a href="#data-perkara"
                                class="text-green-200 hover:text-white transition duration-300">Data Perkara</a></li>
                        <li><a href="#kontak"
                                class="text-green-200 hover:text-white transition duration-300">Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Informasi Legal</h3>
                    <ul class="space-y-2 text-sm text-green-200">
                        <li>Kebijakan Privasi</li>
                        <li>Syarat dan Ketentuan</li>
                        <li>Pedoman Penggunaan</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-green-700 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-green-200 text-sm mb-4 md:mb-0">
                        &copy; 2024 Seksi Hukum Divisi 2 Kostrad. Hak Cipta Dilindungi.
                    </p>
                    <p class="text-green-200 text-sm">
                        Dikembangkan dengan <span class="text-red-400">♥</span> untuk TNI AD
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling untuk navigasi
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Navbar transparency on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('nav');
            if (window.scrollY > 50) {
                navbar.classList.add('shadow-2xl');
            } else {
                navbar.classList.remove('shadow-2xl');
            }
        });
    </script>
</body>

</html>
