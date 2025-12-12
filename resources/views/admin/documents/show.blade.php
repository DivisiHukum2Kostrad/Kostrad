@extends('admin.layout')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center text-sm text-gray-600 mb-4">
                <a href="{{ route('admin.perkaras.index') }}" class="hover:text-blue-600 transition">
                    <i class="fas fa-briefcase mr-2"></i>Perkara
                </a>
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <a href="{{ route('admin.perkaras.show', $document->perkara) }}" class="hover:text-blue-600 transition">
                    {{ $document->perkara->nomor_perkara }}
                </a>
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <span class="text-gray-800 font-semibold">Detail Dokumen</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Document Info Card -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <!-- Document Header -->
                    <div class="flex items-start justify-between mb-6 pb-6 border-b">
                        <div class="flex items-start gap-4 flex-1">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <i class="fas {{ $document->file_icon }} text-3xl"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $document->nama_dokumen }}</h1>
                                <div class="flex flex-wrap gap-2 mb-3">
                                    {!! $document->category_badge !!}
                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">
                                        {{ $document->jenis_dokumen }}
                                    </span>
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">
                                        Versi {{ $document->version }}
                                    </span>
                                    @if ($document->is_public)
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">
                                            <i class="fas fa-globe mr-1"></i>Publik
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">
                                            <i class="fas fa-lock mr-1"></i>Privat
                                        </span>
                                    @endif
                                </div>
                                <div class="flex gap-4 text-sm text-gray-600">
                                    <span><i class="fas fa-database mr-1"></i>{{ $document->formatted_file_size }}</span>
                                    <span><i class="fas fa-download mr-1"></i>{{ $document->download_count }}
                                        download</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Document Description -->
                    @if ($document->description)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Deskripsi</h3>
                            <p class="text-gray-600 whitespace-pre-line">{{ $document->description }}</p>
                        </div>
                    @endif

                    <!-- Document Metadata -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="text-xs text-gray-500 uppercase tracking-wider">Diupload Oleh</label>
                            <p class="text-sm font-medium text-gray-800 mt-1">
                                {{ $document->uploader->name ?? 'System' }}
                            </p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 uppercase tracking-wider">Tanggal Upload</label>
                            <p class="text-sm font-medium text-gray-800 mt-1">
                                {{ $document->created_at->format('d F Y, H:i') }}
                            </p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 uppercase tracking-wider">Terakhir Diunduh</label>
                            <p class="text-sm font-medium text-gray-800 mt-1">
                                {{ $document->last_downloaded_at ? $document->last_downloaded_at->format('d F Y, H:i') : 'Belum pernah' }}
                            </p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 uppercase tracking-wider">Tipe MIME</label>
                            <p class="text-sm font-medium text-gray-800 mt-1">{{ $document->mime_type }}</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.documents.download', $document) }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-150">
                            <i class="fas fa-download mr-2"></i>Download
                        </a>

                        @if ($document->is_previewable)
                            <a href="{{ route('admin.documents.preview', $document) }}" target="_blank"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-150">
                                <i class="fas fa-eye mr-2"></i>Preview
                            </a>
                        @endif

                        @if (auth()->user()->hasPermission('manage_documents'))
                            <a href="{{ route('admin.documents.edit', $document) }}"
                                class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-150">
                                <i class="fas fa-edit mr-2"></i>Edit Metadata
                            </a>

                            <button onclick="document.getElementById('uploadVersionModal').classList.remove('hidden')"
                                class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-150">
                                <i class="fas fa-code-branch mr-2"></i>Upload Versi Baru
                            </button>
                        @endif

                        @if (auth()->user()->hasPermission('delete_cases'))
                            <form action="{{ route('admin.documents.destroy', $document) }}" method="POST" class="inline"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-150">
                                    <i class="fas fa-trash mr-2"></i>Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Version History -->
                @if ($document->versions()->count() > 0 || $document->parent)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-history text-blue-600 mr-3"></i>
                            Riwayat Versi
                        </h3>

                        <div class="space-y-3">
                            @foreach ($document->versions as $version)
                                <div
                                    class="flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-file text-blue-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">
                                                Versi {{ $version->version }}
                                                @if ($version->id === $document->id)
                                                    <span
                                                        class="ml-2 px-2 py-1 bg-green-100 text-green-800 rounded text-xs">Current</span>
                                                @endif
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $version->created_at->format('d M Y, H:i') }} oleh
                                                {{ $version->uploader->name ?? 'System' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.documents.show', $version) }}"
                                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            Lihat
                                        </a>
                                        <a href="{{ route('admin.documents.download', $version) }}"
                                            class="text-green-600 hover:text-green-800 text-sm font-medium">
                                            Download
                                        </a>
                                    </div>
                                </div>
                            @endforeach

                            @if ($document->parent)
                                <a href="{{ route('admin.documents.show', $document->parent) }}"
                                    class="block text-center text-blue-600 hover:text-blue-800 text-sm font-medium mt-3">
                                    <i class="fas fa-arrow-left mr-1"></i>Lihat Versi Sebelumnya
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Related Case Info -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Perkara Terkait</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs text-gray-500 uppercase tracking-wider">Nomor Perkara</label>
                            <p class="text-sm font-medium text-gray-800 mt-1">{{ $document->perkara->nomor_perkara }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 uppercase tracking-wider">Judul</label>
                            <p class="text-sm font-medium text-gray-800 mt-1">{{ $document->perkara->judul_perkara }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 uppercase tracking-wider">Status</label>
                            <p class="text-sm font-medium text-gray-800 mt-1">
                                <span
                                    class="px-2 py-1 rounded text-xs font-semibold
                                    {{ $document->perkara->status == 'Selesai'
                                        ? 'bg-green-100 text-green-800'
                                        : ($document->perkara->status == 'Proses'
                                            ? 'bg-yellow-100 text-yellow-800'
                                            : 'bg-blue-100 text-blue-800') }}">
                                    {{ $document->perkara->status }}
                                </span>
                            </p>
                        </div>
                        <a href="{{ route('admin.perkaras.show', $document->perkara) }}"
                            class="block w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-center font-semibold transition duration-150 mt-4">
                            <i class="fas fa-arrow-right mr-2"></i>Lihat Perkara
                        </a>
                    </div>
                </div>

                <!-- File Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi File</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Ekstensi:</span>
                            <span class="font-medium text-gray-800 uppercase">{{ $document->file_extension }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Ukuran:</span>
                            <span class="font-medium text-gray-800">{{ $document->formatted_file_size }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Dapat di-preview:</span>
                            <span class="font-medium {{ $document->is_previewable ? 'text-green-600' : 'text-red-600' }}">
                                {{ $document->is_previewable ? 'Ya' : 'Tidak' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Download:</span>
                            <span class="font-medium text-gray-800">{{ $document->download_count }}x</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Version Modal -->
    <div id="uploadVersionModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Upload Versi Baru</h3>
                <button onclick="document.getElementById('uploadVersionModal').classList.add('hidden')"
                    class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.documents.uploadVersion', $document) }}"
                enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        File Versi Baru <span class="text-red-500">*</span>
                    </label>
                    <input type="file" name="file" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-2 text-xs text-gray-500">Maksimal 10MB. Versi akan bertambah otomatis.</p>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-150">
                        <i class="fas fa-upload mr-2"></i>Upload
                    </button>
                    <button type="button" onclick="document.getElementById('uploadVersionModal').classList.add('hidden')"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-semibold transition duration-150">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
