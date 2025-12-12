@extends('admin.layout')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center text-sm text-gray-600 mb-4">
                <a href="{{ route('admin.perkaras.index') }}" class="hover:text-blue-600 transition">
                    <i class="fas fa-briefcase mr-2"></i>Perkara
                </a>
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <a href="{{ route('admin.perkaras.show', $perkara) }}" class="hover:text-blue-600 transition">
                    {{ $perkara->nomor_perkara }}
                </a>
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <span class="text-gray-800 font-semibold">Upload Dokumen</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Upload Dokumen Baru</h1>
            <p class="text-gray-600 mt-1">Upload dokumen untuk perkara: <strong>{{ $perkara->nomor_perkara }}</strong></p>
        </div>

        <!-- Upload Form -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <form method="POST" action="{{ route('admin.documents.store', $perkara) }}" enctype="multipart/form-data">
                @csrf

                <!-- File Upload Area with Drag & Drop -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        File Dokumen <span class="text-red-500">*</span>
                    </label>

                    <div id="dropzone"
                        class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition cursor-pointer bg-gray-50">
                        <div class="mb-4">
                            <i class="fas fa-cloud-upload-alt text-6xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-600 mb-2">Drag & drop file di sini atau klik untuk memilih</p>
                        <p class="text-sm text-gray-500">Maksimal 10MB per file. Bisa upload multiple files.</p>
                        <input type="file" name="files[]" id="fileInput" multiple
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip,.rar" class="hidden" required>
                    </div>

                    @error('files.*')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- File List Preview -->
                    <div id="fileList" class="mt-4 space-y-2"></div>
                </div>

                <!-- Jenis Dokumen -->
                <div class="mb-6">
                    <label for="jenis_dokumen" class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Dokumen <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis_dokumen" id="jenis_dokumen" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('jenis_dokumen') border-red-500 @enderror">
                        <option value="">Pilih Jenis Dokumen</option>
                        <option value="BAP" {{ old('jenis_dokumen') == 'BAP' ? 'selected' : '' }}>BAP (Berita Acara
                            Pemeriksaan)</option>
                        <option value="Putusan" {{ old('jenis_dokumen') == 'Putusan' ? 'selected' : '' }}>Putusan</option>
                        <option value="Surat Dakwaan" {{ old('jenis_dokumen') == 'Surat Dakwaan' ? 'selected' : '' }}>Surat
                            Dakwaan</option>
                        <option value="Surat Tuntutan" {{ old('jenis_dokumen') == 'Surat Tuntutan' ? 'selected' : '' }}>
                            Surat Tuntutan</option>
                        <option value="Surat Pembelaan" {{ old('jenis_dokumen') == 'Surat Pembelaan' ? 'selected' : '' }}>
                            Surat Pembelaan</option>
                        <option value="Bukti" {{ old('jenis_dokumen') == 'Bukti' ? 'selected' : '' }}>Bukti</option>
                        <option value="Laporan" {{ old('jenis_dokumen') == 'Laporan' ? 'selected' : '' }}>Laporan</option>
                        <option value="Surat Kuasa" {{ old('jenis_dokumen') == 'Surat Kuasa' ? 'selected' : '' }}>Surat
                            Kuasa</option>
                        <option value="Lainnya" {{ old('jenis_dokumen') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('jenis_dokumen')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div class="mb-6">
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="category" id="category" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category') border-red-500 @enderror">
                        <option value="">Pilih Kategori</option>
                        <option value="evidence" {{ old('category') == 'evidence' ? 'selected' : '' }}>Bukti</option>
                        <option value="legal" {{ old('category') == 'legal' ? 'selected' : '' }}>Hukum</option>
                        <option value="administrative" {{ old('category') == 'administrative' ? 'selected' : '' }}>
                            Administrasi</option>
                        <option value="correspondence" {{ old('category') == 'correspondence' ? 'selected' : '' }}>Surat
                            Menyurat</option>
                        <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('category')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi (Opsional)
                    </label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                        placeholder="Tambahkan catatan atau deskripsi dokumen...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Is Public -->
                <div class="mb-8">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_public" value="1"
                            class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            {{ old('is_public') ? 'checked' : '' }}>
                        <span class="ml-3 text-sm font-medium text-gray-700">
                            Dokumen dapat diakses publik
                            <span class="block text-xs text-gray-500 mt-1">Centang jika dokumen ini tidak mengandung
                                informasi rahasia</span>
                        </span>
                    </label>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4">
                    <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg transition duration-150">
                        <i class="fas fa-upload mr-2"></i>Upload Dokumen
                    </button>
                    <a href="{{ route('admin.perkaras.show', $perkara) }}"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold text-center transition duration-150">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                </div>
            </form>
        </div>

        <!-- Info Box -->
        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-600 text-xl mr-3 mt-1"></i>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Informasi Upload:</p>
                    <ul class="list-disc ml-4 space-y-1">
                        <li>Maksimal ukuran file: 10MB per file</li>
                        <li>Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, ZIP, RAR</li>
                        <li>Anda dapat mengupload multiple files sekaligus</li>
                        <li>File akan disimpan dengan nama yang unik untuk menghindari duplikasi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Drag & Drop -->
    <script>
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('fileInput');
        const fileList = document.getElementById('fileList');

        // Click to select files
        dropzone.addEventListener('click', () => fileInput.click());

        // Drag and drop handlers
        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('border-blue-500', 'bg-blue-50');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('border-blue-500', 'bg-blue-50');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-blue-500', 'bg-blue-50');
            fileInput.files = e.dataTransfer.files;
            displayFiles();
        });

        // Display selected files
        fileInput.addEventListener('change', displayFiles);

        function displayFiles() {
            fileList.innerHTML = '';
            const files = fileInput.files;

            if (files.length === 0) return;

            Array.from(files).forEach((file, index) => {
                const fileSize = formatFileSize(file.size);
                const fileItem = document.createElement('div');
                fileItem.className =
                    'flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-lg';
                fileItem.innerHTML = `
                    <div class="flex items-center gap-3">
                        <i class="fas fa-file text-blue-600 text-xl"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-800">${file.name}</p>
                            <p class="text-xs text-gray-500">${fileSize}</p>
                        </div>
                    </div>
                    <button type="button" onclick="removeFile(${index})" class="text-red-600 hover:text-red-800">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                fileList.appendChild(fileItem);
            });
        }

        function formatFileSize(bytes) {
            if (bytes >= 1048576) {
                return (bytes / 1048576).toFixed(2) + ' MB';
            } else if (bytes >= 1024) {
                return (bytes / 1024).toFixed(2) + ' KB';
            }
            return bytes + ' bytes';
        }

        function removeFile(index) {
            const dt = new DataTransfer();
            const files = fileInput.files;

            for (let i = 0; i < files.length; i++) {
                if (i !== index) dt.items.add(files[i]);
            }

            fileInput.files = dt.files;
            displayFiles();
        }
    </script>
@endsection
