{{-- resources/views/admin/perkaras/create.blade.php --}}
@extends('admin.layout')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-900">Tambah Perkara Baru</h2>
        <p class="text-gray-600 mt-1">Isi form di bawah untuk menambahkan perkara</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <form method="POST" action="{{ route('admin.perkaras.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Nomor Perkara -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Perkara</label>
                <input type="text" name="nomor_perkara" value="{{ old('nomor_perkara', $nomor_perkara) }}" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('nomor_perkara') border-red-500 @enderror" required>
                @error('nomor_perkara')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jenis Perkara -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Perkara</label>
                <input type="text" name="jenis_perkara" value="{{ old('jenis_perkara') }}" placeholder="Contoh: Pelanggaran Disiplin Militer" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('jenis_perkara') border-red-500 @enderror" required>
                @error('jenis_perkara')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kategori -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
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

            <!-- Tanggal -->
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk') }}" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('tanggal_masuk') border-red-500 @enderror" required>
                    @error('tanggal_masuk')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Selesai (Opsional)</label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('tanggal_selesai') border-red-500 @enderror">
                    @error('tanggal_selesai')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('status') border-red-500 @enderror" required>
                    <option value="Proses" {{ old('status') === 'Proses' ? 'selected' : '' }}>Proses</option>
                    <option value="Selesai" {{ old('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Keterangan -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan (Opsional)</label>
                <textarea name="keterangan" rows="4" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('keterangan') border-red-500 @enderror">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Upload File -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Dokumen (PDF, Max 5MB)</label>
                <input type="file" name="file_dokumentasi" accept=".pdf" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none @error('file_dokumentasi') border-red-500 @enderror">
                @error('file_dokumentasi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Public Checkbox -->
            <div class="mb-8">
                <label class="flex items-center">
                    <input type="checkbox" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }} class="w-4 h-4 text-green-800 border-gray-300 rounded focus:ring-green-800">
                    <span class="ml-2 text-sm text-gray-700 font-semibold">Publikasikan (Data bisa dilihat publik)</span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
                <button type="submit" class="bg-green-800 hover:bg-green-900 text-white px-8 py-3 rounded-lg font-semibold transition duration-300">
                    Simpan Perkara
                </button>
                <a href="{{ route('admin.perkaras.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-8 py-3 rounded-lg font-semibold transition duration-300">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
