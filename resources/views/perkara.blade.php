<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Perkara Publik - DivisiHukum2Kostrad</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">
    @include('partials.header')

    <!-- Header Section -->
    <section class="pt-32 pb-16 bg-gradient-to-r from-green-900 via-green-800 to-green-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4">Data Perkara Publik</h1>
                <p class="text-lg text-green-100 max-w-2xl mx-auto">
                    Informasi perkara yang telah diselesaikan dan dapat diakses oleh masyarakat umum
                </p>
            </div>
        </div>
    </section>

    <!-- Filter & Search Section -->
    <section class="py-8 bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('perkara.public') }}" id="searchForm">
                <!-- Search Bar with Clear Button -->
                <div class="mb-4">
                    <div class="relative w-full">
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                            placeholder="Cari nomor perkara, jenis perkara, terdakwa, oditur..."
                            class="w-full px-4 py-3 pr-20 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none">
                        @if (request('search'))
                            <button type="button" onclick="clearSearch()"
                                class="absolute right-14 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        @endif
                        <button type="submit"
                            class="absolute right-2 top-1/2 transform -translate-y-1/2 px-4 py-1.5 bg-green-800 text-white rounded-lg font-semibold hover:bg-green-900 transition duration-300">
                            Cari
                        </button>
                    </div>
                </div>

                <!-- Advanced Filters Bar -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                        <select name="status"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none">
                            <option value="all">Semua Status</option>
                            <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai
                            </option>
                            <option value="Proses" {{ request('status') === 'Proses' ? 'selected' : '' }}>Dalam Proses
                            </option>
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                        <select name="kategori"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none">
                            <option value="all">Semua Kategori</option>
                            @foreach ($kategoris as $kat)
                                <option value="{{ $kat->id }}"
                                    {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Klasifikasi Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Klasifikasi</label>
                        <select name="klasifikasi"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none">
                            <option value="all">Semua Klasifikasi</option>
                            @foreach ($klasifikasiList as $klasifikasi)
                                <option value="{{ $klasifikasi }}"
                                    {{ request('klasifikasi') === $klasifikasi ? 'selected' : '' }}>
                                    {{ $klasifikasi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Year Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun</label>
                        <select name="year"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none">
                            <option value="all">Semua Tahun</option>
                            @foreach ($availableYears as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Filter Actions -->
                <div class="mt-4 flex gap-3">
                    <button type="submit"
                        class="px-6 py-2 bg-green-800 text-white rounded-lg font-semibold hover:bg-green-900 transition duration-300 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Terapkan Filter
                    </button>
                    <a href="{{ route('perkara.public') }}"
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition duration-300 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset Filter
                    </a>
                </div>
            </form>
        </div>
    </section>

    <script>
        function clearSearch() {
            document.getElementById('searchInput').value = '';
            document.getElementById('searchForm').submit();
        }
    </script>

    <!-- Table Section -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Daftar Perkara</h2>
                <p class="text-gray-600 mt-1">Total: {{ $total_perkaras }} data perkara</p>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-green-800 to-green-900 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Nomor
                                    Perkara
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Tanggal
                                    Register</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Klasifikasi
                                    Perkara</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Para Pihak
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Status
                                    Perkara</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Lama Proses
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Link</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($perkaras as $index => $perkara)
                                <tr class="hover:bg-gray-50 transition duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        {{ $perkaras->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $perkara->nomor_perkara }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $perkara->tanggal_pendaftaran ? $perkara->tanggal_pendaftaran->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $perkara->klasifikasi_perkara ?: '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="mb-2">
                                            <span class="text-xs font-semibold text-gray-700">Oditur:</span>
                                            @if ($perkara->oditur && is_array($perkara->oditur))
                                                @foreach ($perkara->oditur as $oditur)
                                                    <div class="text-sm text-gray-900">{{ $oditur }}</div>
                                                @endforeach
                                            @else
                                                <span class="text-sm text-gray-400">-</span>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="text-xs font-semibold text-gray-700">Terdakwa:</span>
                                            @if ($perkara->terdakwa && is_array($perkara->terdakwa))
                                                @foreach ($perkara->terdakwa as $terdakwa)
                                                    <div class="text-sm text-gray-900">{{ $terdakwa }}</div>
                                                @endforeach
                                            @else
                                                <span class="text-sm text-gray-400">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-3 py-1 text-xs font-semibold rounded-full {{ $perkara->status_badge }}">
                                            {{ $perkara->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        @php
                                            if ($perkara->tanggal_selesai) {
                                                $lamaProses = $perkara->tanggal_masuk->diffInDays(
                                                    $perkara->tanggal_selesai,
                                                );
                                            } else {
                                                $lamaProses = $perkara->tanggal_masuk->diffInDays(now());
                                            }
                                        @endphp
                                        {{ $lamaProses }} Hari
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('perkara.public.show', $perkara->id) }}"
                                            class="text-green-800 hover:text-green-900 font-semibold hover:underline">
                                            [detail]
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                        Tidak ada data perkara yang dipublikasikan
                                    </td>
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
    </section>
</body>

</html>
