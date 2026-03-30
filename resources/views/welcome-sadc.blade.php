<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recap SADC - BINUS Bandung</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-900 via-blue-700 to-blue-600 min-h-screen flex items-center justify-center">
    
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            
            <!-- Logo BINUS -->
            <div class="mb-8 animate-fade-in">
                <img 
                    src="{{ asset('images/logo-binus.png') }}" 
                    alt="BINUS Logo" 
                    class="h-40 w-40 mx-auto object-contain drop-shadow-2xl"
                    onerror="this.style.display='none'"
                >
            </div>
            
            <!-- Title -->
            <h1 class="text-6xl md:text-7xl font-bold text-white mb-4 animate-slide-up">
                Recap SADC
            </h1>
            
            <!-- Subtitle -->
            <p class="text-xl md:text-2xl text-blue-100 mb-8 animate-slide-up" style="animation-delay: 0.1s">
                Sistem Rekap Follow-Up Mahasiswa
            </p>
            
            <p class="text-lg text-blue-200 mb-12 animate-slide-up" style="animation-delay: 0.2s">
                Student Academic Development Center<br>
                BINUS University Bandung
            </p>
            
            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12 animate-slide-up" style="animation-delay: 0.3s">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-8 py-4 bg-white text-blue-600 rounded-lg font-semibold hover:bg-blue-50 transition shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="fas fa-tachometer-alt mr-2"></i>
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-8 py-4 bg-white text-blue-600 rounded-lg font-semibold hover:bg-blue-50 transition shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Login
                    </a>
                @endauth
            </div>
            
            <!-- Features -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-16 animate-slide-up" style="animation-delay: 0.4s">
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-6 text-white">
                    <i class="fas fa-users text-4xl mb-4"></i>
                    <h3 class="text-lg font-semibold mb-2">Multi-User Access</h3>
                    <p class="text-sm text-blue-100">Akses bersamaan untuk seluruh staff SADC</p>
                </div>
                
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-6 text-white">
                    <i class="fas fa-file-excel text-4xl mb-4"></i>
                    <h3 class="text-lg font-semibold mb-2">Excel Integration</h3>
                    <p class="text-sm text-blue-100">Import & export data dengan mudah</p>
                </div>
                
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-6 text-white">
                    <i class="fas fa-calendar-alt text-4xl mb-4"></i>
                    <h3 class="text-lg font-semibold mb-2">Multi-Periode</h3>
                    <p class="text-sm text-blue-100">Kelola data per semester akademik</p>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="mt-16 text-blue-200 text-sm">
                <p>© {{ date('Y') }} BINUS University Bandung. All rights reserved.</p>
            </div>
            
        </div>
    </div>
    
    <style>
        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slide-up {
            from { 
                opacity: 0;
                transform: translateY(30px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fade-in 1s ease-out;
        }
        
        .animate-slide-up {
            animation: slide-up 0.8s ease-out;
            animation-fill-mode: both;
        }
    </style>
    
</body>
</html>