{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIPERKARA DIV-2</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg {
            background: linear-gradient(135deg, rgba(30, 58, 32, 0.95) 0%, rgba(45, 80, 22, 0.95) 50%, rgba(26, 77, 46, 0.95) 100%);
        }
    </style>
</head>
<body class="bg-gray-50 gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-xl mx-auto mb-4">
                <svg class="w-12 h-12 text-green-800" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">SIPERKARA DIV-2</h1>
            <p class="text-green-200">Divisi 2 Kostrad - Seksi Hukum</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Login Admin</h2>

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none transition @error('email') border-red-500 @enderror"
                        placeholder="admin@siperkara.mil.id"
                        required
                        autofocus
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-800 focus:outline-none transition @error('password') border-red-500 @enderror"
                        placeholder="••••••••"
                        required
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-green-800 border-gray-300 rounded focus:ring-green-800">
                        <span class="ml-2 text-sm text-gray-700">Ingat Saya</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-green-800 text-white py-3 rounded-lg font-semibold hover:bg-green-900 transition duration-300 shadow-lg hover:shadow-xl"
                >
                    Login
                </button>
            </form>

            <!-- Back to Home -->
            <div class="mt-6 text-center">
                <a href="{{ route('landing') }}" class="text-green-800 hover:text-green-900 text-sm font-medium">
                    ← Kembali ke Beranda
                </a>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-green-200 text-sm mt-6">
            &copy; 2024 Seksi Hukum Divisi 2 Kostrad
        </p>
    </div>
</body>
</html>
