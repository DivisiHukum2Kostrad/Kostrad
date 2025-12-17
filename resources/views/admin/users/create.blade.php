@extends('admin.layout')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center text-sm text-gray-600 mb-4">
                <a href="{{ route('admin.users.index') }}" class="hover:text-blue-600 transition">
                    <i class="fas fa-users mr-2"></i>Manajemen User
                </a>
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <span class="text-gray-800 font-semibold">Tambah User Baru</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Tambah User Baru</h1>
            <p class="text-gray-600 mt-1">Buat akun user baru dengan role tertentu</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <!-- Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                        placeholder="Masukkan nama lengkap">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                        placeholder="contoh@email.com">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div class="mb-6">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select id="role" name="role" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('role') border-red-500 @enderror">
                        <option value="">Pilih Role</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin - Akses Penuh</option>
                        <option value="operator" {{ old('role') == 'operator' ? 'selected' : '' }}>Operator - Akses Terbatas
                        </option>
                    </select>
                    @error('role')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Role Info -->
                    <div class="mt-3 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-sm text-green-800 font-semibold mb-2">
                            <i class="fas fa-info-circle mr-1"></i>Perbedaan Role:
                        </p>
                        <ul class="text-sm text-green-700 space-y-1 ml-5 list-disc">
                            <li><strong>Admin:</strong> Dapat mengelola user, melihat semua perkara, menghapus data</li>
                            <li><strong>Operator:</strong> Dapat mengelola perkara dan dokumen (tidak dapat mengelola user)
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                        placeholder="Minimal 8 karakter">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div class="mb-8">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Ulangi password">
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4">
                    <button type="submit"
                        class="flex-1 bg-green-800 hover:bg-green-900 text-white px-6 py-3 rounded-lg font-semibold shadow-lg transition duration-150">
                        <i class="fas fa-save mr-2"></i>Simpan User
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold text-center transition duration-150">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                </div>
            </form>
        </div>

        <!-- Security Note -->
        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <div class="flex items-start">
                <i class="fas fa-shield-alt text-yellow-600 text-xl mr-3 mt-1"></i>
                <div class="text-sm text-yellow-800">
                    <p class="font-semibold mb-1">Catatan Keamanan:</p>
                    <ul class="list-disc ml-4 space-y-1">
                        <li>Password minimal 8 karakter</li>
                        <li>Gunakan kombinasi huruf, angka, dan simbol untuk keamanan lebih baik</li>
                        <li>User baru akan menerima kredensial login yang telah Anda buat</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
