cat > resources/views/layouts/admin.blade.php << 'EOF'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VirtuGym Admin - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #f3f4f6; }
        .sidebar { background: linear-gradient(135deg, #1e1b4b, #4c1d95); }
        .sidebar-item:hover { background: rgba(255,255,255,0.1); }
    </style>
</head>
<body>
    <div class="flex min-h-screen">
        <aside class="sidebar w-64 text-white">
            <div class="p-6">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                        <span class="text-purple-600 text-xl">🏋️</span>
                    </div>
                    <span class="text-xl font-bold">VirtuGym Admin</span>
                </div>
                <nav class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-item block px-4 py-2 rounded-lg">📊 Dashboard</a>
                    <a href="{{ route('admin.users') }}" class="sidebar-item block px-4 py-2 rounded-lg">👥 Users</a>
                    <a href="{{ route('admin.trainers') }}" class="sidebar-item block px-4 py-2 rounded-lg">🏋️ Trainers</a>
                    <a href="{{ route('admin.bookings') }}" class="sidebar-item block px-4 py-2 rounded-lg">📅 Bookings</a>
                </nav>
            </div>
        </aside>
        
        <main class="flex-1">
            <nav class="bg-white shadow px-6 py-3 flex justify-between items-center">
                <h1 class="text-xl font-bold">@yield('title')</h1>
                <div class="flex items-center space-x-4">
                    <span>{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-red-600">Logout</button>
                    </form>
                </div>
            </nav>
            <div class="p-6">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
EOF