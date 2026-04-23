<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VirtuGym - @yield('title', 'Dashboard')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/style.css">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
        }
        
        /* Glass Navigation */
        .nav-glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        /* Sidebar Styles */
        .sidebar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .sidebar-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 12px;
        }
        
        .sidebar-item:hover {
            background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
            color: white;
            transform: translateX(5px);
            box-shadow: 0 10px 20px -10px rgba(139, 92, 246, 0.3);
        }
        
        .sidebar-item.active {
            background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
            color: white;
            box-shadow: 0 5px 15px -5px rgba(139, 92, 246, 0.4);
        }
        
        /* Main Content Card */
        .main-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 28px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        /* Button Gradient */
        .btn-gradient {
            background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
            transition: all 0.3s ease;
        }
        
        .btn-gradient:hover {
            background: linear-gradient(135deg, #7c3aed 0%, #db2777 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(139, 92, 246, 0.4);
        }
        
        /* Stat Card */
        .stat-card {
            background: white;
            transition: all 0.3s ease;
            border-radius: 20px;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 30px -15px rgba(0, 0, 0, 0.2);
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #8b5cf6, #ec4899);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #7c3aed, #db2777);
        }
        
        /* User Avatar */
        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #8b5cf6, #ec4899);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        
        .user-avatar:hover {
            transform: scale(1.05);
        }
        
        /* Dropdown Menu */
        .dropdown-menu {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            min-width: 200px;
        }
        
        .dropdown-menu a, .dropdown-menu button {
            transition: all 0.2s ease;
            display: block;
        }
        
        .dropdown-menu a:hover, .dropdown-menu button:hover {
            background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
            color: white;
            padding-left: 20px;
        }
        
        /* Mobile menu button */
        .mobile-menu-btn {
            display: none;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                position: fixed;
                z-index: 100;
                width: 280px;
            }
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            .mobile-menu-btn {
                display: block;
            }
            .ml-64 {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <nav class="nav-glass fixed top-0 w-full z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Mobile Menu Button -->
                <button onclick="toggleMobileSidebar()" class="mobile-menu-btn mr-3 text-gray-700 hover:text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                
                <!-- Logo Section -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full flex items-center justify-center shadow-lg">
                        <img src="{{ asset('images/logo.png') }}" alt="VirtuGym" class="h-8 w-8 rounded-full object-cover">
                    </div>
                    <div>
                        <span class="text-lg font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">VIRTU GYM</span>
                        <span class="text-xs text-gray-500 block leading-tight">VIRTUAL GYM</span>
                    </div>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('dashboard') }}" class="sidebar-item px-4 py-2 text-gray-700 hover:text-white font-medium flex items-center space-x-2 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span>📊</span>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('workouts.index') }}" class="sidebar-item px-4 py-2 text-gray-700 hover:text-white font-medium flex items-center space-x-2 {{ request()->routeIs('workouts.*') ? 'active' : '' }}">
                        <span>💪</span>
                        <span>Workouts</span>
                    </a>
                    <a href="{{ route('exercises.index') }}" class="sidebar-item px-4 py-2 text-gray-700 hover:text-white font-medium flex items-center space-x-2 {{ request()->routeIs('exercises.*') ? 'active' : '' }}">
                        <span>📚</span>
                        <span>Exercises</span>
                    </a>
                    <a href="{{ route('progress.index') }}" class="sidebar-item px-4 py-2 text-gray-700 hover:text-white font-medium flex items-center space-x-2 {{ request()->routeIs('progress.*') ? 'active' : '' }}">
                        <span>📈</span>
                        <span>Progress</span>
                    </a>
                </div>
                
                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name ?? 'User' }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->email ?? '' }}</p>
                    </div>
                    
                    <!-- Dropdown with JavaScript -->
                    <div class="relative" id="userDropdown">
                        <button onclick="toggleDropdown()" class="focus:outline-none">
                            <div class="user-avatar">
                                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                            </div>
                        </button>
                        
                        <div id="dropdownMenu" class="dropdown-menu absolute right-0 mt-2 py-2 z-50 hidden">
                            
                            <a href="{{ route('progress.index') }}" class="block px-4 py-2 text-gray-700">
                                📊 My Progress
                            </a>
                            <hr class="my-1 border-gray-200">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-600">
                                    🚪 Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content with Sidebar Layout -->
    <div class="flex pt-16 min-h-screen">
        <!-- Left Sidebar -->
        <aside id="sidebar" class="sidebar fixed left-0 top-16 h-full w-64 overflow-y-auto z-40 shadow-xl">
            <div class="p-4">
                <div class="mb-6 p-3 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl">
                    <div class="flex items-center space-x-2 mb-2">
                        <span class="text-2xl">🎯</span>
                        <span class="text-sm font-semibold text-gray-700">Today's Goal</span>
                    </div>
                    <p class="text-xs text-gray-600">Complete your workout and stay consistent!</p>
                </div>
                
                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 text-gray-700 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span class="text-xl">📊</span>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    <a href="{{ route('workouts.index') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 text-gray-700 {{ request()->routeIs('workouts.*') ? 'active' : '' }}">
                        <span class="text-xl">💪</span>
                        <span class="font-medium">My Workouts</span>
                    </a>
                    <a href="{{ route('workouts.create') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 text-gray-700">
                        <span class="text-xl">➕</span>
                        <span class="font-medium">Create Workout</span>
                    </a>
                    <a href="{{ route('exercises.index') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 text-gray-700 {{ request()->routeIs('exercises.*') ? 'active' : '' }}">
                        <span class="text-xl">📚</span>
                        <span class="font-medium">Exercise Library</span>
                    </a>
                    <a href="{{ route('progress.index') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 text-gray-700 {{ request()->routeIs('progress.*') ? 'active' : '' }}">
                        <span class="text-xl">📈</span>
                        <span class="font-medium">Progress Tracking</span>
                    </a>
                </nav>
                
                <hr class="my-4 border-gray-200">
                
                <div class="mt-4 p-3 bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl text-white">
                    <div class="text-center">
                        <div class="text-2xl mb-1">🔥</div>
                        <p class="text-xs font-semibold">Workout Streak</p>
                        <p class="text-2xl font-bold" id="streakCount">0 days</p>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Main Content Area -->
        <main id="mainContent" class="flex-1 p-6" style="margin-left: 16rem;">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-6 fade-in-up">
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">✅</span>
                            <p class="font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-6 fade-in-up">
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">❌</span>
                            <p class="font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Page Content -->
            <div class="fade-in-up">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // Dropdown toggle function
        function toggleDropdown() {
            const menu = document.getElementById('dropdownMenu');
            if (menu) {
                menu.classList.toggle('hidden');
            }
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const menu = document.getElementById('dropdownMenu');
            
            if (dropdown && menu && !dropdown.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
        
        // Mobile sidebar toggle
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.classList.toggle('mobile-open');
            }
        }
        
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            let alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
            alerts.forEach(function(alert) {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 300);
            });
        }, 5000);
        
        // Update streak count if exists
        @if(isset($streak))
            document.getElementById('streakCount').innerText = '{{ $streak }} days';
        @endif
        
        // Handle responsive sidebar
        function handleResponsive() {
            const mainContent = document.getElementById('mainContent');
            const sidebar = document.getElementById('sidebar');
            
            if (window.innerWidth <= 768) {
                if (mainContent) mainContent.style.marginLeft = '0';
                if (sidebar) sidebar.classList.remove('mobile-open');
            } else {
                if (mainContent) mainContent.style.marginLeft = '16rem';
                if (sidebar) sidebar.classList.remove('mobile-open');
            }
        }
        
        window.addEventListener('resize', handleResponsive);
        handleResponsive();
    </script>
</body>
</html>