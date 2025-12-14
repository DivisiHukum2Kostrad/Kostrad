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
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-green-900 via-green-800 to-green-900 shadow-lg fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-18 py-3">
                <div class="flex items-center space-x-4">
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
                    <a href="{{ route('landing') }}"
                        class="text-white hover:text-green-300 font-medium transition duration-300">Beranda</a>
                    <a href="#" class="text-green-300 font-bold border-b-2 border-green-300">Data Perkara
                        Publik</a>
                </div>
            </div>
        </div>
    </nav>

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
            <form method="GET" action="{{ route('perkara.public') }}"
                class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="w-full md:w-1/2">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nomor perkara, jenis perkara, terdakwa..."
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none">
                </div>
                <div class="flex flex-wrap gap-3 w-full md:w-auto">
                    <button type="submit"
                        class="px-6 py-3 bg-green-800 text-white rounded-lg font-semibold hover:bg-green-900 transition duration-300">
                        Cari
                    </button>

                </div>
            </form>
        </div>
    </section>

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
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Nomor Perkara</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Tanggal Register</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Klasifikasi Perkara</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Para Pihak</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Status Perkara</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Lama Proses</th>
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
                                        <div class="text-sm font-bold text-gray-900">{{ $perkara->nomor_perkara }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $perkara->tanggal_pendaftaran ? $perkara->tanggal_pendaftaran->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $perkara->klasifikasi_perkara ?: '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="mb-2">
                                            <span class="text-xs font-semibold text-gray-700">Oditur:</span>
                                            @if($perkara->oditur && is_array($perkara->oditur))
                                                @foreach($perkara->oditur as $oditur)
                                                    <div class="text-sm text-gray-900">{{ $oditur }}</div>
                                                @endforeach
                                            @else
                                                <span class="text-sm text-gray-400">-</span>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="text-xs font-semibold text-gray-700">Terdakwa:</span>
                                            @if($perkara->terdakwa && is_array($perkara->terdakwa))
                                                @foreach($perkara->terdakwa as $terdakwa)
                                                    <div class="text-sm text-gray-900">{{ $terdakwa }}</div>
                                                @endforeach
                                            @else
                                                <span class="text-sm text-gray-400">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $perkara->status_badge }}">
                                            {{ $perkara->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        @php
                                            if ($perkara->tanggal_selesai) {
                                                $lamaProses = $perkara->tanggal_masuk->diffInDays($perkara->tanggal_selesai);
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
