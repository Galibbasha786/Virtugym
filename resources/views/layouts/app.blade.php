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
        *{font-family:'Inter',sans-serif;box-sizing:border-box;}

        body {
            background: #08081a;
            min-height: 100vh;
            color: #fff;
            overflow-x: hidden;
        }

        /* Background layers */
        #stars{position:fixed;inset:0;z-index:0;pointer-events:none;}
        .orb{position:fixed;border-radius:50%;filter:blur(90px);pointer-events:none;z-index:0;}
        .o1{width:500px;height:500px;background:rgba(139,92,246,.1);top:-200px;left:-150px;animation:od 22s ease-in-out infinite;}
        .o2{width:400px;height:400px;background:rgba(236,72,153,.08);bottom:-100px;right:-100px;animation:od 28s ease-in-out infinite reverse;}
        .o3{width:250px;height:250px;background:rgba(59,130,246,.06);top:50%;right:15%;animation:od 18s ease-in-out infinite 4s;}
        @keyframes od{0%,100%{transform:translate(0,0);}33%{transform:translate(30px,-40px);}66%{transform:translate(-20px,25px);}}

        /* Navbar */
        .nav-dark {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 50;
            background: rgba(8,8,26,.9);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(139,92,246,.2);
        }
        .nav-inner {
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 1.25rem;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .logo-pill {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .logo-badge {
            width: 38px;
            height: 38px;
            border-radius: 11px;
            background: linear-gradient(135deg,#8b5cf6,#ec4899);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 16px rgba(139,92,246,.45);
            flex-shrink: 0;
        }
        .brand-name {
            font-size: .95rem;
            font-weight: 800;
            background: linear-gradient(135deg,#c4b5fd,#f9a8d4);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: .05em;
        }
        .brand-sub {
            font-size: .58rem;
            color: rgba(255,255,255,.28);
            letter-spacing: .12em;
        }

        /* User avatar */
        .user-avatar {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #8b5cf6, #ec4899);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 4px 12px rgba(139,92,246,.35);
        }
        .user-avatar:hover {
            transform: scale(1.07);
            box-shadow: 0 6px 20px rgba(139,92,246,.55);
        }

        /* Dropdown */
        .dropdown-menu {
            background: rgba(16,16,40,.97);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,.5), 0 0 0 1px rgba(139,92,246,.25);
            min-width: 200px;
        }
        .dropdown-menu a, .dropdown-menu button {
            transition: all 0.2s ease;
            display: block;
            width: 100%;
        }
        .dropdown-menu a:hover, .dropdown-menu button:hover {
            background: rgba(139,92,246,.15);
            color: #c4b5fd;
            padding-left: 20px;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 64px;
            height: calc(100vh - 64px);
            width: 240px;
            background: rgba(255,255,255,.03);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(139,92,246,.15);
            overflow-y: auto;
            z-index: 40;
        }
        .sidebar::-webkit-scrollbar{width:4px;}
        .sidebar::-webkit-scrollbar-thumb{background:linear-gradient(#8b5cf6,#ec4899);border-radius:4px;}

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            color: rgba(255,255,255,.45);
            font-size: .88rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.4,0,0.2,1);
            position: relative;
            margin-bottom: 2px;
        }
        .sidebar-item:hover {
            background: rgba(139,92,246,.12);
            color: #c4b5fd;
            transform: translateX(4px);
        }
        .sidebar-item.active {
            background: linear-gradient(135deg, rgba(139,92,246,.25), rgba(236,72,153,.15));
            color: #c4b5fd;
            border: 1px solid rgba(139,92,246,.3);
            box-shadow: 0 4px 16px rgba(139,92,246,.2);
        }
        .sidebar-item .s-icon {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
            flex-shrink: 0;
        }

        /* Sidebar divider */
        .s-divider {
            height: 1px;
            background: rgba(139,92,246,.12);
            margin: 12px 16px;
        }

        /* Streak widget */
        .streak-widget {
            background: linear-gradient(135deg, rgba(139,92,246,.2), rgba(236,72,153,.15));
            border: 1px solid rgba(139,92,246,.28);
            border-radius: 16px;
            padding: 16px;
            text-align: center;
        }
        .streak-widget .s-num {
            font-size: 1.8rem;
            font-weight: 900;
            background: linear-gradient(135deg, #c4b5fd, #f9a8d4);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .streak-widget .s-lbl {
            font-size: .73rem;
            color: rgba(255,255,255,.38);
            font-weight: 500;
            margin-top: 2px;
        }

        /* Main content */
        .main-content {
            margin-left: 240px;
            padding: 88px 1.5rem 2rem;
            min-height: 100vh;
            position: relative;
            z-index: 10;
        }

        /* Alert banners */
        .alert-success {
            background: rgba(16,185,129,.12);
            border: 1px solid rgba(16,185,129,.3);
            border-left: 4px solid #10b981;
            border-radius: 12px;
            padding: 14px 18px;
            color: #6ee7b7;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.5rem;
        }
        .alert-error {
            background: rgba(239,68,68,.12);
            border: 1px solid rgba(239,68,68,.3);
            border-left: 4px solid #ef4444;
            border-radius: 12px;
            padding: 14px 18px;
            color: #fca5a5;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.5rem;
        }

        /* Animations */
        .fade-in-up {
            animation: fadeInUp 0.55s cubic-bezier(.23,1,.32,1) forwards;
            opacity: 0;
        }
        .delay-1 { animation-delay: 0.08s; }
        .delay-2 { animation-delay: 0.16s; }
        .delay-3 { animation-delay: 0.24s; }
        .delay-4 { animation-delay: 0.32s; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Unread badge */
        #unreadBadge {
            background: #ef4444;
            color: white;
            font-size: .65rem;
            padding: 1px 6px;
            border-radius: 50px;
            font-weight: 700;
        }

        /* Mobile */
        @media (max-width: 900px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .hamburger { display: flex !important; }
        }
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 4px;
        }
        .hamburger span {
            width: 22px;
            height: 2px;
            background: rgba(196,181,253,.7);
            border-radius: 2px;
            transition: all .3s;
        }

        /* User info in nav */
        .nav-user-info p { line-height: 1.2; }
        .nav-user-name { font-size: .85rem; font-weight: 600; color: #e2d9f3; }
        .nav-user-email { font-size: .72rem; color: rgba(255,255,255,.28); }
    </style>
</head>
<body>
    <canvas id="stars"></canvas>
    <div class="orb o1"></div>
    <div class="orb o2"></div>
    <div class="orb o3"></div>

    <!-- NAVBAR -->
    <nav class="nav-dark">
        <div class="nav-inner">
            <!-- Logo -->
            <a href="{{ route('dashboard') }}" class="logo-pill">
                <div class="logo-badge">
                    <img src="/images/logo.png" alt="VG" style="width:24px;height:24px;border-radius:50%;object-fit:cover;" onerror="this.style.display='none';this.parentElement.innerHTML='<span style=\'font-size:.75rem;font-weight:900;color:#fff;\'>VG</span>';">
                </div>
                <div>
                    <div class="brand-name">VIRTU GYM</div>
                    <div class="brand-sub">VIRTUAL TRAINER</div>
                </div>
            </a>

            <div style="display:flex;align-items:center;gap:1rem;">
                <!-- User info -->
                <div class="nav-user-info hidden sm:block text-right">
                    <p class="nav-user-name">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="nav-user-email">{{ Auth::user()->email ?? '' }}</p>
                </div>

                <!-- Avatar + dropdown -->
                <div class="relative" id="userDropdown">
                    <button onclick="toggleDropdown()" class="focus:outline-none">
                        <div class="user-avatar">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</div>
                    </button>
                    <div id="dropdownMenu" class="dropdown-menu absolute right-0 mt-3 py-2 z-50 hidden">
                        <div style="padding:10px 16px 8px;border-bottom:1px solid rgba(139,92,246,.15);margin-bottom:4px;">
                            <p style="font-size:.82rem;font-weight:700;color:#c4b5fd;">{{ Auth::user()->name ?? 'User' }}</p>
                            <p style="font-size:.72rem;color:rgba(255,255,255,.3);">{{ Auth::user()->email ?? '' }}</p>
                        </div>
                        <a href="{{ route('dashboard') }}" style="padding:9px 16px;color:rgba(255,255,255,.55);font-size:.83rem;">📊 Dashboard</a>
                        <a href="{{ route('profile.edit') }}" style="padding:9px 16px;color:rgba(255,255,255,.55);font-size:.83rem;">⚙️ Edit Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" style="padding:9px 16px;color:#f87171;font-size:.83rem;text-align:left;background:none;border:none;cursor:pointer;width:100%;">
                                🚪 Sign Out
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Hamburger (mobile) -->
                <div class="hamburger" id="hamburger" onclick="toggleSidebar()">
                    <span></span><span></span><span></span>
                </div>
            </div>
        </div>
    </nav>

    <!-- SIDEBAR -->
    <aside id="sidebar" class="sidebar">
        <div style="padding:1.2rem 1rem;">
            <p style="font-size:.65rem;color:rgba(255,255,255,.2);font-weight:700;letter-spacing:.12em;padding:0 8px;margin-bottom:.6rem;">MAIN</p>
            <nav style="display:flex;flex-direction:column;">
                @if(Auth::user()->role == 'admin')
                <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="s-icon">📊</span><span>Dashboard</span>
                </a>
                <a href="{{ route('admin.users') }}" class="sidebar-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                    <span class="s-icon">👥</span><span>Users</span>
                </a>
                <a href="{{ route('admin.trainers') }}" class="sidebar-item {{ request()->routeIs('admin.trainers') ? 'active' : '' }}">
                    <span class="s-icon">🏋️</span><span>Trainers</span>
                </a>
                <a href="{{ route('admin.bookings') }}" class="sidebar-item {{ request()->routeIs('admin.bookings') ? 'active' : '' }}">
                    <span class="s-icon">📅</span><span>Bookings</span>
                </a>
                <a href="{{ route('admin.withdrawals') }}" class="sidebar-item {{ request()->routeIs('admin.withdrawals') ? 'active' : '' }}">
                    <span class="s-icon">💰</span><span>Withdrawals</span>
                </a>
                @else
                <a href="{{ route('dashboard') }}" class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="s-icon">📊</span><span>Dashboard</span>
                </a>
                <a href="{{ route('analytics.index') }}" class="sidebar-item {{ request()->routeIs('analytics.*') ? 'active' : '' }}">
                    <span class="s-icon">📈</span><span>Analytics</span>
                </a>
                <a href="{{ route('workouts.index') }}" class="sidebar-item {{ request()->routeIs('workouts.*') ? 'active' : '' }}">
                    <span class="s-icon">🏋️</span><span>Workouts</span>
                </a>
                <a href="{{ route('exercises.index') }}" class="sidebar-item {{ request()->routeIs('exercises.*') ? 'active' : '' }}">
                    <span class="s-icon">💪</span><span>Exercises</span>
                </a>
                <a href="{{ route('progress.index') }}" class="sidebar-item {{ request()->routeIs('progress.*') ? 'active' : '' }}">
                    <span class="s-icon">🎯</span><span>Progress</span>
                </a>
                <a href="{{ route('chat.index') }}" class="sidebar-item {{ request()->routeIs('chat.*') ? 'active' : '' }}">
                    <span class="s-icon">💬</span><span>Messages</span>
                    <span id="unreadBadge" class="hidden" style="margin-left:auto;"></span>
                </a>

                <!-- AI COACH SIDEBAR LINK -->
                <a href="{{ route('ai.dashboard') }}" class="sidebar-item {{ request()->routeIs('ai.*') ? 'active' : '' }}">
                    <span class="s-icon">🤖</span>
                    <span>AI Coach</span>
                </a>

                @if(Auth::user()->role == 'trainer')
                <a href="{{ route('trainer.availability.index') }}" class="sidebar-item {{ request()->routeIs('trainer.availability.*') ? 'active' : '' }}">
                    <span class="s-icon">⏰</span><span>Availability</span>
                </a>
                <a href="{{ route('bookings.index') }}" class="sidebar-item {{ request()->routeIs('bookings.*') ? 'active' : '' }}">
                    <span class="s-icon">📅</span><span>Bookings</span>
                </a>
                <a href="{{ route('trainer.withdrawals') }}" class="sidebar-item {{ request()->routeIs('trainer.withdrawals') ? 'active' : '' }}">
                    <span class="s-icon">💰</span><span>Withdrawals</span>
                </a>
                @endif

                @if(Auth::user()->role == 'trainee')
                <a href="{{ route('bookings.index') }}" class="sidebar-item {{ request()->routeIs('bookings.*') ? 'active' : '' }}">
                    <span class="s-icon">📅</span><span>My Sessions</span>
                </a>
                @endif
                @endif
            </nav>

            <div class="s-divider" style="margin-top:1rem;"></div>

            <!-- Streak Widget -->
            <div class="streak-widget">
                <div style="font-size:1.5rem;margin-bottom:4px;">🔥</div>
                <div class="s-num" id="streakCount">0</div>
                <div class="s-lbl">Day Streak</div>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content" id="mainContent">
        @if(session('success'))
            <div class="alert-success fade-in-up">
                <span style="font-size:1.2rem;">✅</span>
                <p style="font-weight:500;">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="alert-error fade-in-up">
                <span style="font-size:1.2rem;">❌</span>
                <p style="font-weight:500;">{{ session('error') }}</p>
            </div>
        @endif

        <div class="fade-in-up">
            @yield('content')
        </div>
    </main>

    <script>
    // Starfield
    (function(){
        const c=document.getElementById('stars'),ctx=c.getContext('2d');let W,H,S=[];
        function resize(){W=c.width=innerWidth;H=c.height=innerHeight;}
        function init(){S=Array.from({length:160},()=>({x:Math.random()*W,y:Math.random()*H,r:Math.random()*1.1+.2,a:Math.random(),da:(Math.random()-.5)*.005}));}
        function draw(){ctx.clearRect(0,0,W,H);S.forEach(s=>{s.a=Math.max(.05,Math.min(1,s.a+s.da));if(s.a<=.05||s.a>=1)s.da*=-1;ctx.beginPath();ctx.arc(s.x,s.y,s.r,0,Math.PI*2);ctx.fillStyle=`rgba(196,181,253,${s.a})`;ctx.fill();});requestAnimationFrame(draw);}
        window.addEventListener('resize',()=>{resize();init();});resize();init();draw();
    })();

    // Dropdown
    function toggleDropdown() {
        document.getElementById('dropdownMenu').classList.toggle('hidden');
    }
    document.addEventListener('click', function(e) {
        const d = document.getElementById('userDropdown');
        const m = document.getElementById('dropdownMenu');
        if (d && m && !d.contains(e.target)) m.classList.add('hidden');
    });

    // Mobile sidebar
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('mobile-open');
    }

    // Auto-dismiss alerts
    setTimeout(function() {
        document.querySelectorAll('.alert-success, .alert-error').forEach(function(el) {
            el.style.transition = 'opacity .4s';
            el.style.opacity = '0';
            setTimeout(()=>el.remove(), 400);
        });
    }, 5000);
    </script>
</body>
</html>