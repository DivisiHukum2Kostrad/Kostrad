@extends('layouts.admin')

@section('title', 'Pengaturan Notifikasi')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <a href="{{ route('admin.notifications.index') }}"
                    class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Notifikasi
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Pengaturan Notifikasi</h1>
                <p class="text-sm text-gray-600 mt-1">Atur preferensi notifikasi email Anda</p>
            </div>

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            <!-- Preferences Form -->
            <form action="{{ route('admin.notifications.updatePreferences') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-lg shadow-sm">
                    <!-- Info Banner -->
                    <div class="bg-blue-50 border-b border-blue-100 p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 text-xl mt-0.5 mr-3"></i>
                            <div>
                                <h3 class="text-sm font-semibold text-blue-900">Tentang Notifikasi Email</h3>
                                <p class="text-sm text-blue-800 mt-1">
                                    Anda akan tetap menerima notifikasi di dalam aplikasi terlepas dari pengaturan ini.
                                    Pengaturan ini hanya mengatur apakah Anda ingin menerima notifikasi melalui email.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Preference Options -->
                    <div class="p-6 space-y-6">
                        <!-- Case Assigned -->
                        <div class="flex items-start justify-between py-4 border-b border-gray-100">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                                        <i class="fas fa-clipboard-check"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900">Perkara Ditugaskan</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Terima notifikasi email saat Anda ditugaskan untuk menangani perkara baru
                                    </p>
                                </div>
                            </div>
                            <div class="ml-4">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="email_case_assigned" value="1"
                                        {{ $preference->email_case_assigned ? 'checked' : '' }} class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Status Changed -->
                        <div class="flex items-start justify-between py-4 border-b border-gray-100">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600">
                                        <i class="fas fa-sync-alt"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900">Status Perkara Berubah</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Terima notifikasi email saat status perkara yang Anda tangani berubah
                                    </p>
                                </div>
                            </div>
                            <div class="ml-4">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="email_status_changed" value="1"
                                        {{ $preference->email_status_changed ? 'checked' : '' }} class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600">
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Document Uploaded -->
                        <div class="flex items-start justify-between py-4 border-b border-gray-100">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center text-green-600">
                                        <i class="fas fa-file-upload"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900">Dokumen Diunggah</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Terima notifikasi email saat dokumen baru diunggah ke perkara yang Anda tangani
                                    </p>
                                </div>
                            </div>
                            <div class="ml-4">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="email_document_uploaded" value="1"
                                        {{ $preference->email_document_uploaded ? 'checked' : '' }} class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600">
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Deadline Reminder -->
                        <div class="flex items-start justify-between py-4 border-b border-gray-100">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center text-red-600">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900">Pengingat Deadline</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Terima notifikasi email sebagai pengingat deadline perkara yang mendekati batas
                                        waktu
                                    </p>
                                </div>
                            </div>
                            <div class="ml-4">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="email_deadline_reminder" value="1"
                                        {{ $preference->email_deadline_reminder ? 'checked' : '' }} class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600">
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Daily Summary -->
                        <div class="flex items-start justify-between py-4">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center text-yellow-600">
                                        <i class="fas fa-calendar-day"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900">Ringkasan Harian</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Terima ringkasan harian semua notifikasi Anda (dikirim setiap pagi)
                                    </p>
                                    <span
                                        class="inline-block mt-2 px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded">
                                        Segera Hadir
                                    </span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="email_daily_summary" value="1"
                                        {{ $preference->email_daily_summary ? 'checked' : '' }} class="sr-only peer"
                                        disabled>
                                    <div
                                        class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all opacity-50 cursor-not-allowed">
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                        <a href="{{ route('admin.notifications.index') }}"
                            class="px-6 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
