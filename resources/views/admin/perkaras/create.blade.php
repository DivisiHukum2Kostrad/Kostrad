{{-- resources/views/admin/perkaras/create.blade.php --}}
@extends('admin.layout')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-900">Tambah Perkara Baru</h2>
        <p class="text-gray-600 mt-1">Isi form di bawah untuk menambahkan perkara</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <form method="POST" action="{{ route('admin.perkaras.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- SECTION: INFORMASI DASAR --}}
            <div class="mb-8 pb-8 border-b-2 border-gray-200">
                <h3 class="text-xl font-bold text-gray-800 mb-4"> Informasi Dasar</h3>

                <!-- Nomor Perkara -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Perkara <span class="text-red-500">*</span></label>
                    <input type="text" name="nomor_perkara" value="{{ old('nomor_perkara', $nomor_perkara) }}" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('nomor_perkara') border-red-500 @enderror" required>
                    @error('nomor_perkara')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Perkara -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Perkara</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama singkat untuk identifikasi perkara" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('nama') border-red-500 @enderror">
                    @error('nama')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Pendaftaran -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pendaftaran <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_pendaftaran" value="{{ old('tanggal_pendaftaran') }}" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('tanggal_pendaftaran') border-red-500 @enderror" required>
                    @error('tanggal_pendaftaran')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Klasifikasi Perkara -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Klasifikasi Perkara</label>
                    <input type="text" name="klasifikasi_perkara" value="{{ old('klasifikasi_perkara') }}" placeholder="Contoh: Perdagangan Orang (Human Trafficking)" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('klasifikasi_perkara') border-red-500 @enderror">
                    @error('klasifikasi_perkara')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Perkara -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Perkara <span class="text-red-500">*</span></label>
                    <input type="text" name="jenis_perkara" value="{{ old('jenis_perkara') }}" placeholder="Contoh: Pelanggaran Disiplin Militer" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('jenis_perkara') border-red-500 @enderror" required>
                    @error('jenis_perkara')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                    <select name="kategori_id" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('kategori_id') border-red-500 @enderror" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority & Tanggal Perkara -->
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Prioritas <span class="text-red-500">*</span></label>
                        <select name="priority" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('priority') border-red-500 @enderror" required>
                            <option value="Low" {{ old('priority') === 'Low' ? 'selected' : '' }}>Rendah</option>
                            <option value="Medium" {{ old('priority', 'Medium') === 'Medium' ? 'selected' : '' }}>Sedang</option>
                            <option value="High" {{ old('priority') === 'High' ? 'selected' : '' }}>Tinggi</option>
                            <option value="Urgent" {{ old('priority') === 'Urgent' ? 'selected' : '' }}>Mendesak</option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Perkara</label>
                        <input type="date" name="tanggal_perkara" value="{{ old('tanggal_perkara') }}" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('tanggal_perkara') border-red-500 @enderror">
                        @error('tanggal_perkara')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- SECTION: PARA PIHAK --}}
            <div class="mb-8 pb-8 border-b-2 border-gray-200">
                <h3 class="text-xl font-bold text-gray-800 mb-4"> Para Pihak</h3>

                <!-- Oditur -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Oditur (Jaksa Militer)</label>
                    <div id="oditur-container">
                        <div class="flex gap-2 mb-2">
                            <input type="text" name="oditur[]" value="{{ old('oditur.0') }}" placeholder="Nama Oditur" class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none">
                            <button type="button" onclick="addOditur()" class="px-4 py-3 bg-green-800 text-white rounded-lg hover:bg-green-900 transition duration-300">
                                + Tambah
                            </button>
                        </div>
                    </div>
                    @error('oditur')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Contoh: ESJ Wahju Widjajati, S.H., M.H.</p>
                </div>

                <!-- Terdakwa -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Terdakwa</label>
                    <div id="terdakwa-container">
                        <div class="flex gap-2 mb-2">
                            <input type="text" name="terdakwa[]" value="{{ old('terdakwa.0') }}" placeholder="Nama Terdakwa" class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none">
                            <button type="button" onclick="addTerdakwa()" class="px-4 py-3 bg-green-800 text-white rounded-lg hover:bg-green-900 transition duration-300">
                                + Tambah
                            </button>
                        </div>
                    </div>
                    @error('terdakwa')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Contoh: Kolonel (Mar) Sunardi S.E, M.M alias KRMP Kolonel (Mar) Sunardi S.E. M.M</p>
                </div>

                <!-- Ditugaskan Kepada -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ditugaskan Kepada</label>
                    <input type="text" name="assigned_to" value="{{ old('assigned_to') }}" placeholder="Nama personel yang ditugaskan" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('assigned_to') border-red-500 @enderror">
                    @error('assigned_to')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- SECTION: PASAL DAKWAAN --}}
            <div class="mb-8 pb-8 border-b-2 border-gray-200">
                <h3 class="text-xl font-bold text-gray-800 mb-4"> Pasal Dakwaan</h3>

                <!-- Pasal Dakwaan -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pasal Dakwaan</label>
                    <textarea name="pasal_dakwaan" rows="6" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('pasal_dakwaan') border-red-500 @enderror" placeholder="Pertama: Pasal 4 Jo Pasal 10 Undang-Undang Nomor 21 Tahun 2007...">{{ old('pasal_dakwaan') }}</textarea>
                    @error('pasal_dakwaan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Tulis pasal lengkap dengan nomor dan dasar hukumnya</p>
                </div>
            </div>

            {{-- SECTION: INFORMASI SURAT --}}
            <div class="mb-8 pb-8 border-b-2 border-gray-200">
                <h3 class="text-xl font-bold text-gray-800 mb-4"> Informasi Surat & Dokumen</h3>

                <!-- Surat Pelimpahan -->
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Surat Pelimpahan</label>
                        <input type="text" name="nomor_surat_pelimpahan" value="{{ old('nomor_surat_pelimpahan') }}" placeholder="R/55/XI/2025" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('nomor_surat_pelimpahan') border-red-500 @enderror">
                        @error('nomor_surat_pelimpahan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Surat Pelimpahan</label>
                        <input type="date" name="tanggal_surat_pelimpahan" value="{{ old('tanggal_surat_pelimpahan') }}" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('tanggal_surat_pelimpahan') border-red-500 @enderror">
                        @error('tanggal_surat_pelimpahan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Surat Dakwaan -->
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Surat Dakwaan</label>
                        <input type="text" name="nomor_surat_dakwaan" value="{{ old('nomor_surat_dakwaan') }}" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('nomor_surat_dakwaan') border-red-500 @enderror">
                        @error('nomor_surat_dakwaan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Surat Dakwaan</label>
                        <input type="date" name="tanggal_surat_dakwaan" value="{{ old('tanggal_surat_dakwaan') }}" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('tanggal_surat_dakwaan') border-red-500 @enderror">
                        @error('tanggal_surat_dakwaan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Skeppera -->
                <div class="grid md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Skeppera</label>
                        <input type="text" name="nomor_skeppera" value="{{ old('nomor_skeppera') }}" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('nomor_skeppera') border-red-500 @enderror">
                        @error('nomor_skeppera')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Skeppera</label>
                        <input type="date" name="tanggal_skeppera" value="{{ old('tanggal_skeppera') }}" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('tanggal_skeppera') border-red-500 @enderror">
                        @error('tanggal_skeppera')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pejabat Skeppera</label>
                        <input type="text" name="pejabat_skeppera" value="{{ old('pejabat_skeppera') }}" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('pejabat_skeppera') border-red-500 @enderror">
                        @error('pejabat_skeppera')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Penyidik Militer -->
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor BAP Penyidik</label>
                        <input type="text" name="nomor_bap_penyidik" value="{{ old('nomor_bap_penyidik') }}" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('nomor_bap_penyidik') border-red-500 @enderror">
                        @error('nomor_bap_penyidik')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal BAP Penyidik</label>
                        <input type="date" name="tanggal_bap_penyidik" value="{{ old('tanggal_bap_penyidik') }}" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('tanggal_bap_penyidik') border-red-500 @enderror">
                        @error('tanggal_bap_penyidik')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- SECTION: TEMPAT & WAKTU KEJADIAN --}}
            <div class="mb-8 pb-8 border-b-2 border-gray-200">
                <h3 class="text-xl font-bold text-gray-800 mb-4"> Tempat & Waktu Kejadian</h3>

                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Kejadian</label>
                        <input type="date" name="tanggal_kejadian" value="{{ old('tanggal_kejadian') }}" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('tanggal_kejadian') border-red-500 @enderror">
                        @error('tanggal_kejadian')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tempat Kejadian</label>
                        <input type="text" name="tempat_kejadian" value="{{ old('tempat_kejadian') }}" placeholder="Lokasi kejadian" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('tempat_kejadian') border-red-500 @enderror">
                        @error('tempat_kejadian')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- SECTION: STATUS & TANGGAL --}}
            <div class="mb-8 pb-8 border-b-2 border-gray-200">
                <h3 class="text-xl font-bold text-gray-800 mb-4">📅 Status & Tanggal Proses</h3>

                <!-- Tanggal -->
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Masuk <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk') }}" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('tanggal_masuk') border-red-500 @enderror" required>
                        @error('tanggal_masuk')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('tanggal_selesai') border-red-500 @enderror">
                        @error('tanggal_selesai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika masih dalam proses</p>
                    </div>
                </div>

                <!-- Deadline & Estimasi -->
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Deadline</label>
                        <input type="date" name="deadline" value="{{ old('deadline') }}" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('deadline') border-red-500 @enderror">
                        @error('deadline')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Estimasi Hari</label>
                        <input type="number" name="estimated_days" value="{{ old('estimated_days') }}" min="1" placeholder="Jumlah hari" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('estimated_days') border-red-500 @enderror">
                        @error('estimated_days')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status Perkara <span class="text-red-500">*</span></label>
                    <select name="status" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('status') border-red-500 @enderror" required>
                        <option value="Proses" {{ old('status') === 'Proses' ? 'selected' : '' }}>Proses</option>
                        <option value="Selesai" {{ old('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Progress -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Progress (%)</label>
                    <div class="flex items-center gap-4">
                        <input type="range" name="progress" value="{{ old('progress', 0) }}" min="0" max="100" step="5" class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer" oninput="this.nextElementSibling.value = this.value">
                        <output class="text-lg font-bold text-green-800 w-16 text-center">{{ old('progress', 0) }}</output>
                    </div>
                    @error('progress')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" placeholder="Deskripsi singkat perkara" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Keterangan -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
                    <textarea name="keterangan" rows="4" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('keterangan') border-red-500 @enderror" placeholder="Catatan tambahan...">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Catatan Internal -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Internal</label>
                    <textarea name="internal_notes" rows="3" placeholder="Catatan internal yang tidak dipublikasikan" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('internal_notes') border-red-500 @enderror">{{ old('internal_notes') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Catatan ini hanya dapat dilihat oleh admin</p>
                    @error('internal_notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tags -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tags</label>
                    <input type="text" name="tags" value="{{ old('tags') }}" placeholder="Pisahkan dengan koma, contoh: Mendesak, Prioritas Tinggi, Tahap Akhir" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('tags') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Tag akan dipisahkan otomatis menggunakan koma</p>
                    @error('tags')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Upload File -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Dokumen (PDF, Max 10MB)</label>
                    <input type="file" name="file_dokumentasi" accept=".pdf" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('file_dokumentasi') border-red-500 @enderror">
                    @error('file_dokumentasi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Public Checkbox -->
                <div class="mb-8">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }} class="w-4 h-4 text-green-800 border-gray-300 rounded focus:ring-green-800">
                        <span class="ml-2 text-sm text-gray-700 font-semibold">✅ Publikasikan (Data bisa dilihat publik)</span>
                    </label>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
                <button type="submit" class="bg-green-800 hover:bg-green-900 text-white px-8 py-3 rounded-lg font-semibold transition duration-300 shadow-lg">
                     Simpan Perkara
                </button>
                <a href="{{ route('admin.perkaras.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-8 py-3 rounded-lg font-semibold transition duration-300">
                    ❌ Batal
                </a>
            </div>
        </form>
    </div>
</div>

{{-- JavaScript untuk Dynamic Input --}}
<script>
function addOditur() {
    const container = document.getElementById('oditur-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2 mb-2';
    div.innerHTML = `
        <input type="text" name="oditur[]" placeholder="Nama Oditur" class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none">
        <button type="button" onclick="removeInput(this)" class="px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-300">
            - Hapus
        </button>
    `;
    container.appendChild(div);
}

function addTerdakwa() {
    const container = document.getElementById('terdakwa-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2 mb-2';
    div.innerHTML = `
        <input type="text" name="terdakwa[]" placeholder="Nama Terdakwa" class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none">
        <button type="button" onclick="removeInput(this)" class="px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-300">
            - Hapus
        </button>
    `;
    container.appendChild(div);
}

function removeInput(button) {
    button.parentElement.remove();
}
</script>
@endsection
