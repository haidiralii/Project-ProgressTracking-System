<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ProTrack Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --maroon-primary: #CA2626;    
            --maroon-dark: #9B1A1A;        
            --maroon-light: #D64848;         
            --maroon-lighter: #E57B7B;     
            --background-light: #f8f0f0;
            --text-light: #e0e0e0;
            --text-dark: #333333;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background-light);
            color: var(--text-dark);
        }
        .sidebar {
            background: linear-gradient(to bottom, #9B1A1A, #CA2626); /* Gradasi merah */
            color: #f3f4f6;
            border-right: none;
        }
        .nav-item {
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
            border-radius: 0.5rem;
            margin-bottom: 0.25rem;
            z-index: 1;
        }
        .nav-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: all 0.5s ease;
            z-index: -1;
        }
        .nav-item:hover::before {
            left: 0;
        }
        .nav-item.active {
            background-color: var(--maroon-dark);
            color: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .nav-item:hover:not(.active) {
            background-color: rgba(255, 255, 255, 0.05);
            color: var(--text-light);
            transform: translateY(-1px);
        }
        .nav-icon-wrapper {
            background-color: rgba(255, 255, 255, 0.1);
            transition: background-color 0.2s ease;
            border-radius: 0.375rem;
        }
        .nav-item.active .nav-icon-wrapper,
        .nav-item:hover .nav-icon-wrapper {
            background-color: var(--maroon-lighter);
        }
        .project-item {
            transition: all 0.2s ease;
            border-radius: 0.375rem;
        }
        .project-item:hover {
            background-color: rgba(255, 255, 255, 0.03);
            transform: translateX(1px);
        }
        .project-dot {
            background-color: var(--maroon-lighter);
        }
        .header-dashboard {
            background: linear-gradient(to right, #9B1A1A, #CA2626);
            color: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .header-other {
            background-color: #ffffff;
            border-bottom: 1px solid #e0e0e0;
        }
        .text-maroon {
            color: var(--maroon-primary);
        }
        .bg-maroon {
            background-color: var(--maroon-primary);
        }
        .bg-maroon-light {
            background-color: var(--maroon-light);
        }
        .bg-maroon-lighter {
            background-color: var(--maroon-lighter);
        }
        .animate-fade-in-down {
            animation: fadeInDown 0.5s ease-out forwards;
        }
        svg.absolute {
        pointer-events: none;
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen antialiased">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 sidebar flex flex-col shadow-xl overflow-hidden h-screen fixed left-0 top-0 z-50">            
            <!-- SVG blob -->
            <svg class="absolute -bottom-40 -left-40 w-[630px] h-[600px] z-0 opacity-30" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="redGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#FF6B5E;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#FF3C3C;stop-opacity:1" />
                    </linearGradient>
                </defs>
                <path fill="url(#redGradient)" d="M421.5,331Q400,412,326.5,445.5Q253,479,170.5,442.5Q88,406,73,317.5Q58,229,123.5,166.5Q189,104,282.5,89.5Q376,75,413.5,157.5Q451,240,421.5,331Z" />
            </svg>
            <!-- Sidebar Top: Logo & Title -->
            <div class="p-5 flex flex-col">
                <div class="flex items-center gap-4">
                    <!-- Kotak dengan logo di dalamnya -->
                    <div class="relative rounded-lg px-4 py-2 bg-maroon-light/20">
                        <!-- Logo background -->
                        <div class="absolute inset-0 flex items-center justify-center opacity-30">
                            <i class="fas fa-gears text-maroon-light text-7xl"></i>
                        </div>
                        <!-- Teks -->
                        <h1 class="relative text-4xl font-bold tracking-wide text-white">
                            ProTrack
                        </h1>
                    </div>
                </div>
            </div>
            <!-- User Info -->
            <div class="flex items-center space-x-3 ml-6">
                <div class="w-12 h-12 bg-maroon-light rounded-lg flex items-center justify-center shadow-md">
                    <i class="fas fa-user text-white text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold truncate text-white">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-text-light opacity-80 capitalize">{{ Auth::user()->role }}</p>
                </div>
            </div>
            <!-- Sidebar Navigation -->
            <nav class="flex-1 px-4 py-6 overflow-y-auto">
                <ul class="space-y-1">

                    <!-- Dashboard (semua role) -->
                    <li>
                        <a href="{{ route('dashboard') }}" 
                        class="nav-item flex items-center gap-3 px-4 py-2 font-medium {{ request()->is('dashboard') ? 'active' : 'text-text-light' }}">
                            <div class="nav-icon-wrapper w-8 h-8 flex items-center justify-center">
                                <i class="fas fa-home text-sm"></i>
                            </div>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <!-- Projects (semua role) -->
                    <li>
                        <a href="{{ route('projects.index') }}" 
                        class="nav-item flex items-center gap-3 px-4 py-2 font-medium {{ request()->is('projects*') ? 'active' : 'text-text-light' }}">
                            <div class="nav-icon-wrapper w-8 h-8 flex items-center justify-center">
                                <i class="fas fa-folder-open text-sm"></i>
                            </div>
                            <span>Projects</span>
                        </a>
                    </li>

                    <!-- Jobs (semua role) -->
                    <li>
                        <a href="{{ route('jobs.index') }}" 
                        class="nav-item flex items-center gap-3 px-4 py-2 font-medium {{ request()->is('jobs*') ? 'active' : 'text-text-light' }}">
                            <div class="nav-icon-wrapper w-8 h-8 flex items-center justify-center">
                                <i class="fas fa-tasks text-sm"></i>
                            </div>
                            <span>Jobs</span>
                        </a>
                    </li>

                    <!-- Activity (hanya untuk operator dan admin) -->
                    @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'operator']))
                        <li>
                            <a href="{{ route('activities.index') }}" 
                            class="nav-item flex items-center gap-3 px-4 py-2 font-medium {{ request()->is('activities*') ? 'active' : 'text-text-light' }}">
                                <div class="nav-icon-wrapper w-8 h-8 flex items-center justify-center">
                                    <i class="fas fa-history text-sm"></i>
                                </div>
                                <span>Activity</span>
                            </a>
                        </li>
                    @endif

                    <!-- Users (hanya untuk admin) -->
                    @if(Auth::check() && Auth::user()->role === 'admin')
                        <li>
                            <a href="{{ route('users.index') }}" 
                            class="nav-item flex items-center gap-3 px-4 py-2 font-medium {{ request()->is('users*') ? 'active' : 'text-text-light' }}">
                                <div class="nav-icon-wrapper w-8 h-8 flex items-center justify-center">
                                    <i class="fas fa-users text-sm"></i>
                                </div>
                                <span>Users</span>
                            </a>
                        </li>
                    @endif

                    <!-- Reports (admin, director, operator) -->
                    @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'director', 'operator']))
                        <li>
                            <a href="{{ route('reports.index') }}" 
                            class="nav-item flex items-center gap-3 px-4 py-2 font-medium {{ request()->is('reports*') ? 'active' : 'text-text-light' }}">
                                <div class="nav-icon-wrapper w-8 h-8 flex items-center justify-center">
                                    <i class="fas fa-file-alt text-sm"></i>
                                </div>
                                <span>Reports</span>
                            </a>
                        </li>
                    @endif

                </ul>
            </nav>
            <!-- Sidebar Bottom: Logout -->
            <div class="p-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                        class="w-full text-left text-white hover:text-white transition-colors text-sm flex items-center space-x-3 px-3 py-2 rounded-md hover:bg-white/10">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>
        <!-- Main Content -->
        <main class="flex-1 ml-64 relative h-screen overflow-y-auto">
            <!-- Header -->
            @if(request()->is('dashboard'))
            <header class="header-dashboard sticky top-0 z-50 px-8 py-6 shadow-lg" style="background: linear-gradient(90deg, #9B1A1A 0%, #CA2626 100%);"> 
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-white">Dashboard Overview</h2>
                        <p class="text-white/80 mt-1">Get a quick glance at your project's performance.</p>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="text-sm text-white/80 font-medium hidden sm:block">
                            {{ now()->format('l, F j, Y') }}
                        </div>
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center shadow-md">
                            <button class="w-10 h-10 bg-white/15 backdrop-blur-sm rounded-xl shadow-md flex items-center justify-center hover:bg-white/25 transition-all duration-300 text-white">
                                <i class="fas fa-user text-white text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </header>
            @elseif(request()->is('projects') || request()->is('projects/*'))
            <header class="header-dashboard sticky top-0 z-50 px-8 py-6 shadow-lg" style="background: linear-gradient(90deg, #9B1A1A 0%, #CA2626 100%);">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-white">Projects Overview</h2>
                        <p class="text-white/80 mt-1">Manage and track all your projects in one place.</p>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="text-sm text-white/80 font-medium hidden sm:block">
                            {{ now()->format('l, F j, Y') }}
                        </div>
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center shadow-md">
                            <button class="w-10 h-10 bg-white/15 backdrop-blur-sm rounded-xl shadow-md flex items-center justify-center hover:bg-white/25 transition-all duration-300 text-white">
                                <i class="fas fa-user text-white text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </header>
            @elseif(request()->is('jobs') || request()->is('jobs/*'))
            <header class="header-dashboard sticky top-0 z-50 px-8 py-6 shadow-lg" style="background: linear-gradient(90deg, #9B1A1A 0%, #CA2626 100%);">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-white">Jobs List</h2>
                        <p class="text-white/80 mt-1">Manage and track all jobs across your projects in one place.</p>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="text-sm text-white/80 font-medium hidden sm:block">
                            {{ now()->format('l, F j, Y') }}
                        </div>
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center shadow-md">
                            <button class="w-10 h-10 bg-white/15 backdrop-blur-sm rounded-xl shadow-md flex items-center justify-center hover:bg-white/25 transition-all duration-300 text-white">
                                <i class="fas fa-user text-white text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </header>
            @elseif(request()->is('activities') || request()->is('activities/*'))
            <header class="header-dashboard sticky top-0 z-50 px-8 py-6 shadow-lg" style="background: linear-gradient(90deg, #9B1A1A 0%, #CA2626 100%);">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-white">Activity Logs</h2>
                        <p class="text-white/80 mt-1">Monitor and track all job activities and operator performance.</p>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="text-sm text-white/80 font-medium hidden sm:block">
                            {{ now()->format('l, F j, Y') }}
                        </div>
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center shadow-md">
                            <button class="w-10 h-10 bg-white/15 backdrop-blur-sm rounded-xl shadow-md flex items-center justify-center hover:bg-white/25 transition-all duration-300 text-white">
                                <i class="fas fa-user text-white text-lg"></i>
                            </button>   
                        </div>
                    </div>
                </div>
            </header>
            @elseif(request()->is('users') || request()->is('users/*'))
            <header class="header-dashboard sticky top-0 z-50 px-8 py-6 shadow-lg" style="background: linear-gradient(90deg, #9B1A1A 0%, #CA2626 100%);">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-white">User List</h2>
                        <p class="text-white/80 mt-1">Manage all users in the system easily and efficiently.</p>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="text-sm text-white/80 font-medium hidden sm:block">
                            {{ now()->format('l, F j, Y') }}
                        </div>
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center shadow-md">
                            <button class="w-10 h-10 bg-white/15 backdrop-blur-sm rounded-xl shadow-md flex items-center justify-center hover:bg-white/25 transition-all duration-300 text-white">
                                <i class="fas fa-user text-white text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </header>
            @elseif(request()->is('reports') || request()->is('reports/*'))
            <header class="header-dashboard sticky top-0 z-50 px-8 py-6 shadow-lg"
                    style="background: linear-gradient(90deg, #9B1A1A 0%, #CA2626 100%);">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-white">Reports</h2>
                        <p class="text-white/80 mt-1">
                            View and export project & activity reports.
                        </p>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="text-sm text-white/80 font-medium hidden sm:block">
                            {{ now()->format('l, F j, Y') }}
                        </div>
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center shadow-md">
                            <button class="w-10 h-10 bg-white/15 backdrop-blur-sm rounded-xl shadow-md flex items-center justify-center hover:bg-white/25 transition-all duration-300 text-white">
                                <i class="fas fa-user text-white text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </header>
            @else
            <header class="header-other sticky top-0 z-50 px-8 py-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-maroon">@yield('page-title', 'Page')</h2>
                        @hasSection('page-description')
                        <p class="text-gray-500 text-sm mt-1">@yield('page-description')</p>
                        @endif
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-9 h-9 bg-maroon-lighter rounded-lg flex items-center justify-center shadow-sm">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-semibold text-maroon">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            @endif
            <div class="p-8">
                @if (session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-5 py-3 rounded-lg shadow-sm flex items-center space-x-3 animate-fade-in-down">
                        <i class="fas fa-check-circle text-lg"></i>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-5 py-3 rounded-lg shadow-sm flex items-center space-x-3 animate-fade-in-down">
                        <i class="fas fa-exclamation-circle text-lg"></i>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>