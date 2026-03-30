<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title', 'Dashboard') - Recap SADC</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Smooth transitions */
        * {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-duration: 200ms;
            transition-timing-function: ease-in-out;
        }
        
        /* Sidebar hover effect */
        .sidebar-link {
            transition: all 0.2s ease;
        }
        .sidebar-link:hover {
            transform: translateX(4px);
        }
    </style>
</head>
<body class="bg-gray-100">
    
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-blue-800 to-blue-900 text-white flex-shrink-0 overflow-y-auto">
            <div class="p-6">
                <!-- Logo & Title -->
                <div class="flex items-center mb-8">
                    <img 
                        src="{{ asset('images/logo-binus.png') }}" 
                        alt="BINUS Logo" 
                        class="h-12 w-12 object-contain mr-3"
                        onerror="this.style.display='none'"
                    >
                    <div>
                        <h1 class="text-xl font-bold">Recap SADC</h1>
                        <p class="text-xs text-blue-200">BINUS Bandung</p>
                    </div>
                </div>

                <!-- Current Periode Badge -->
                <div class="mb-6 p-3 bg-blue-700 rounded-lg">
                    <p class="text-xs text-blue-200 mb-1">Periode Aktif:</p>
                    <p class="font-semibold text-sm">
                        <i class="fas fa-calendar-check mr-2"></i>
                        {{ \App\Helpers\PeriodeHelper::getCurrentPeriode() }}
                    </p>
                </div>

                <!-- Navigation Menu -->
                <nav class="space-y-2">
                    <!-- Dashboard -->
                    <a 
                        href="{{ route('dashboard') }}" 
                        class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-700 text-white shadow-lg' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}"
                    >
                        <i class="fas fa-home w-5 text-center"></i>
                        <span class="ml-3 font-medium">Dashboard</span>
                    </a>

                    <!-- Data Mahasiswa -->
                    <a 
                        href="{{ route('students.index') }}" 
                        class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('students.*') ? 'bg-blue-700 text-white shadow-lg' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}"
                    >
                        <i class="fas fa-users w-5 text-center"></i>
                        <span class="ml-3 font-medium">Data Mahasiswa</span>
                    </a>

                    <!-- Data Periode -->
                    <a 
                        href="{{ route('periode.index') }}" 
                        class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('periode.*') ? 'bg-blue-700 text-white shadow-lg' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}"
                    >
                        <i class="fas fa-calendar-alt w-5 text-center"></i>
                        <span class="ml-3 font-medium">Data Periode</span>
                    </a>

                    <!-- Divider -->
                    <div class="border-t border-blue-700 my-4"></div>

                    <!-- Import Data -->
                    <a 
                        href="{{ route('import') }}" 
                        class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('import') ? 'bg-blue-700 text-white shadow-lg' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}"
                    >
                        <i class="fas fa-file-import w-5 text-center"></i>
                        <span class="ml-3 font-medium">Import Data</span>
                    </a>

                    <!-- Export Data -->
                    <a 
                        href="{{ route('export') }}" 
                        class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('export') ? 'bg-blue-700 text-white shadow-lg' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}"
                    >
                        <i class="fas fa-file-export w-5 text-center"></i>
                        <span class="ml-3 font-medium">Export Data</span>
                    </a>
                </nav>
            </div>

            <!-- User Info (Bottom) -->
            <div class="absolute bottom-0 w-64 p-6 bg-blue-900 border-t border-blue-700">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-blue-300 capitalize">{{ auth()->user()->role ?? 'Staff' }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button 
                            type="submit" 
                            class="text-blue-300 hover:text-white"
                            title="Logout"
                        >
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto">
            
            <!-- Top Bar -->
            <header class="bg-white shadow-sm sticky top-0 z-10">
                <div class="px-8 py-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">
                            @yield('page-title', 'Dashboard')
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">
                            @yield('page-subtitle', 'Sistem Follow-Up Mahasiswa SADC')
                        </p>
                    </div>

                    <!-- User Actions -->
                    <div class="flex items-center gap-4">
                        <!-- Notifications (Optional) -->
                        <button class="text-gray-600 hover:text-gray-800 relative">
                            <i class="fas fa-bell text-xl"></i>
                            <!-- Badge (if has notifications) -->
                            <!-- <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span> -->
                        </button>

                        <!-- Settings -->
                        <a href="{{ route('profile.edit') }}" class="text-gray-600 hover:text-gray-800">
                            <i class="fas fa-cog text-xl"></i>
                        </a>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-8">
                
                <!-- Success Message -->
                @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <p class="text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                <!-- Error Message -->
                @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                        <p class="text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
                @endif

                <!-- Validation Errors -->
                @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-3 mt-1"></i>
                        <div>
                            <p class="font-semibold text-red-800 mb-2">Terdapat kesalahan:</p>
                            <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Main Content -->
                @yield('content')
            </div>

        </main>
    </div>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>