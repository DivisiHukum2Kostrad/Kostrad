{{-- resources/views/admin/layout.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - SIPERKARA DIV-2</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-green-900 via-green-800 to-green-900 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-800" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-white font-bold text-lg">SIPERKARA DIV-2</h1>
                        <p class="text-green-200 text-xs">Admin Panel</p>
                    </div>
                </div>

                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('admin.dashboard') }}" class="text-white hover:text-green-300 font-medium transition">Dashboard</a>
                    <a href="{{ route('admin.perkaras.index') }}" class="text-white hover:text-green-300 font-medium transition">Perkara</a>
                    <a href="{{ route('admin.personels.index') }}" class="text-white hover:text-green-300 font-medium transition">Personel</a>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="text-right hidden md:block">
                        <p class="text-white font-semibold text-sm">{{ auth()->user()->name }}</p>
                        <p class="text-green-200 text-xs">{{ auth()->user()->role }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition duration-300">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    @yield('content')
</body>
</html>
