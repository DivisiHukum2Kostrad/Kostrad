@extends('admin.layout')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3"></i>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <div class="flex items-center text-sm text-gray-600 mb-2">
                    <a href="{{ route('admin.perkaras.index') }}" class="hover:text-blue-600 transition">
                        <i class="fas fa-briefcase mr-2"></i>Perkara
                    </a>
                    <i class="fas fa-chevron-right mx-2 text-xs"></i>
                    <span class="text-gray-800 font-semibold">Detail Perkara</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $perkara->nomor_perkara }}</h1>
                <p class="text-gray-600 mt-1">{{ $perkara->judul_perkara }}</p>
            </div>
            <div class="flex gap-3">
                @if (auth()->user()->hasPermission('manage_cases'))
                    <a href="{{ route('admin.perkaras.edit', $perkara) }}"
                        class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-150">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                @endif
                <a href="{{ route('admin.perkaras.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition duration-150">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Priority & Progress Card -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div>
                                <label class="text-xs text-gray-500 uppercase tracking-wider block">Prioritas</label>
                                <div class="mt-2">{!! $perkara->priority_badge !!}</div>
                            </div>
                            @if ($perkara->deadline)
                                <div>
                                    <label class="text-xs text-gray-500 uppercase tracking-wider block">Deadline</label>
                                    <div class="mt-2">{!! $perkara->deadline_badge !!}</div>
                                </div>
                            @endif
                            @if ($perkara->assigned_to)
                                <div>
                                    <label class="text-xs text-gray-500 uppercase tracking-wider block">Ditugaskan</label>
                                    <p class="text-sm font-medium text-gray-800 mt-2">{{ $perkara->assigned_to }}</p>
                                </div>
                            @endif
                        </div>
                        @if ($perkara->tags && count($perkara->tags) > 0)
                            <div class="flex gap-2 flex-wrap">
                                @foreach ($perkara->tags as $tag)
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                        {{ $tag }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    @if ($perkara->progress !== null)
                        <div class="mt-4">
                            <label class="text-xs text-gray-500 uppercase tracking-wider block mb-2">Progress</label>
                            {!! $perkara->progress_badge !!}
                        </div>
                    @endif
                </div>

                <!-- Basic Info Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Perkara</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-500 uppercase tracking-wider">Nomor Perkara</label>
                            <p class="text-sm font-medium text-gray-800 mt-1">{{ $perkara->nomor_perkara }}</p>
                        </div>
                        @if ($perkara->nama)
                            <div>
                                <label class="text-xs text-gray-500 uppercase tracking-wider">Nama Perkara</label>
                                <p class="text-sm font-medium text-gray-800 mt-1">{{ $perkara->nama }}</p>
                            </div>
                        @endif
                        <div>
                            <label class="text-xs text-gray-500 uppercase tracking-wider">Kategori</label>
                            <p class="text-sm font-medium text-gray-800 mt-1">{{ $perkara->kategori->nama_kategori }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 uppercase tracking-wider">Status</label>
                            <p class="text-sm font-medium text-gray-800 mt-1">
                                <span
                                    class="px-3 py-1 rounded text-xs font-semibold
                                    {{ $perkara->status == 'Selesai' ? 'bg-green-100 text-green-800' : ($perkara->status == 'Proses' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ $perkara->status }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 uppercase tracking-wider">Tingkat Urgensi</label>
                            <p class="text-sm font-medium text-gray-800 mt-1">{{ $perkara->tingkat_urgensi }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 uppercase tracking-wider">Jenis Perkara</label>
                            <p class="text-sm font-medium text-gray-800 mt-1">{{ $perkara->jenis_perkara }}</p>
                        </div>
                        @if ($perkara->deskripsi)
                            <div class="md:col-span-2">
                                <label class="text-xs text-gray-500 uppercase tracking-wider">Deskripsi</label>
                                <p class="text-sm text-gray-600 mt-1 whitespace-pre-line">{{ $perkara->deskripsi }}</p>
                            </div>
                        @endif
                        @if ($perkara->tanggal_perkara)
                            <div>
                                <label class="text-xs text-gray-500 uppercase tracking-wider">Tanggal Perkara</label>
                                <p class="text-sm font-medium text-gray-800 mt-1">
                                    {{ $perkara->tanggal_perkara->format('d F Y') }}
                                </p>
                            </div>
                        @endif
                        <div>
                            <label class="text-xs text-gray-500 uppercase tracking-wider">Tanggal Masuk</label>
                            <p class="text-sm font-medium text-gray-800 mt-1">
                                {{ $perkara->tanggal_masuk ? \Carbon\Carbon::parse($perkara->tanggal_masuk)->format('d F Y') : '-' }}
                            </p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 uppercase tracking-wider">Tanggal Selesai</label>
                            <p class="text-sm font-medium text-gray-800 mt-1">
                                {{ $perkara->tanggal_selesai ? \Carbon\Carbon::parse($perkara->tanggal_selesai)->format('d F Y') : '-' }}
                            </p>
                        </div>
                        @if ($perkara->estimated_days)
                            <div>
                                <label class="text-xs text-gray-500 uppercase tracking-wider">Estimasi Hari</label>
                                <p class="text-sm font-medium text-gray-800 mt-1">{{ $perkara->estimated_days }} hari</p>
                            </div>
                        @endif
                        @if ($perkara->keterangan)
                            <div class="md:col-span-2">
                                <label class="text-xs text-gray-500 uppercase tracking-wider">Keterangan</label>
                                <p class="text-sm text-gray-600 mt-1 whitespace-pre-line">{{ $perkara->keterangan }}</p>
                            </div>
                        @endif
                        @if ($perkara->internal_notes && auth()->user()->hasPermission('manage_cases'))
                            <div class="md:col-span-2">
                                <label class="text-xs text-gray-500 uppercase tracking-wider">Catatan Internal</label>
                                <div class="mt-1 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $perkara->internal_notes }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Documents Section -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Dokumen ({{ $perkara->dokumens->count() }})</h2>
                        @if (auth()->user()->hasPermission('manage_documents'))
                            <a href="{{ route('admin.documents.create', $perkara) }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-150">
                                <i class="fas fa-upload mr-2"></i>Upload Dokumen
                            </a>
                        @endif
                    </div>

                    @if ($perkara->dokumens->count() > 0)
                        <div class="space-y-3">
                            @foreach ($perkara->dokumens->sortByDesc('created_at') as $doc)
                                <div
                                    class="flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-lg hover:shadow-md transition">
                                    <div class="flex items-center gap-3 flex-1">
                                        <div class="flex-shrink-0">
                                            <i class="fas {{ $doc->file_icon }} text-2xl"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-800 truncate">
                                                {{ $doc->nama_dokumen }}</p>
                                            <div class="flex gap-2 mt-1 flex-wrap">
                                                {!! $doc->category_badge !!}
                                                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs">
                                                    {{ $doc->jenis_dokumen }}
                                                </span>
                                                <span class="text-xs text-gray-500">
                                                    {{ $doc->formatted_file_size }} • {{ $doc->download_count }} downloads
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex gap-2 ml-4">
                                        <a href="{{ route('admin.documents.show', $doc) }}"
                                            class="text-blue-600 hover:text-blue-800 p-2" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.documents.download', $doc) }}"
                                            class="text-green-600 hover:text-green-800 p-2" title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        @if ($doc->is_previewable)
                                            <a href="{{ route('admin.documents.preview', $doc) }}" target="_blank"
                                                class="text-purple-600 hover:text-purple-800 p-2" title="Preview">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-folder-open text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 mb-4">Belum ada dokumen yang diupload</p>
                            @if (auth()->user()->hasPermission('manage_documents'))
                                <a href="{{ route('admin.documents.create', $perkara) }}"
                                    class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-150">
                                    <i class="fas fa-upload mr-2"></i>Upload Dokumen Pertama
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Timeline/History Section -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Riwayat Perkara ({{ $perkara->riwayats->count() }})
                    </h2>

                    @if ($perkara->riwayats->count() > 0)
                        <div class="space-y-4">
                            @foreach ($perkara->riwayats->sortByDesc('tanggal_kejadian') as $riwayat)
                                <div class="flex gap-4 pb-4 border-b border-gray-200 last:border-0">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-clock text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-800">{{ $riwayat->judul_kejadian }}</p>
                                        <p class="text-sm text-gray-600 mt-1">{{ $riwayat->deskripsi_kejadian }}</p>
                                        <p class="text-xs text-gray-500 mt-2">
                                            {{ \Carbon\Carbon::parse($riwayat->tanggal_kejadian)->format('d F Y') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-8">Belum ada riwayat perkara</p>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Personel Terkait -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Personel Terkait ({{ $perkara->personels->count() }})
                    </h3>

                    @if ($perkara->personels->count() > 0)
                        <div class="space-y-3">
                            @foreach ($perkara->personels as $personel)
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $personel->nama }}</p>
                                        <p class="text-xs text-gray-500">{{ $personel->pangkat }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-4">Belum ada personel terkait</p>
                    @endif
                </div>

                <!-- Statistics -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-md p-6 text-white">
                    <h3 class="text-lg font-bold mb-4">Statistik</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-blue-100">Total Dokumen</span>
                            <span class="text-2xl font-bold">{{ $perkara->dokumens->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-blue-100">Total Riwayat</span>
                            <span class="text-2xl font-bold">{{ $perkara->riwayats->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-blue-100">Personel Terlibat</span>
                            <span class="text-2xl font-bold">{{ $perkara->personels->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Metadata -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Metadata</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <label class="text-xs text-gray-500 uppercase tracking-wider">Dibuat</label>
                            <p class="text-sm text-gray-800 mt-1">{{ $perkara->created_at->format('d F Y, H:i') }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 uppercase tracking-wider">Terakhir Diupdate</label>
                            <p class="text-sm text-gray-800 mt-1">{{ $perkara->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
