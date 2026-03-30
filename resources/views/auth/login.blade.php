<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SADC BINUS Bandung</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    
    <div class="w-full max-w-md">
        <!-- Logo & Title Card -->
        <div class="bg-white rounded-t-2xl shadow-2xl p-8 text-center">
            <!-- Logo BINUS -->
            <div class="mb-6">
                <img 
                    src="{{ asset('images/logo-binus.png') }}" 
                    alt="BINUS Logo" 
                    class="h-32 w-32 mx-auto object-contain drop-shadow-lg"
                    onerror="this.style.display='none'"
                >
            </div>
            
            <!-- Title -->
            <h1 class="text-2xl font-bold text-gray-800 mb-2">
                Sistem Follow Up Mahasiswa
            </h1>
            <p class="text-gray-600 text-sm">
                BINUS University - Bandung Campus
            </p>
        </div>

        <!-- Login Form Card -->
        <div class="bg-white rounded-b-2xl shadow-2xl p-8">
            
            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-sm text-green-800">{{ session('status') }}</p>
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <ul class="text-sm text-red-800 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-blue-600"></i>
                        Email
                    </label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                        autocomplete="username"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        placeholder="nama@binus.ac.id"
                    >
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-blue-600"></i>
                        Password
                    </label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="current-password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        placeholder="••••••••"
                    >
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        >
                        <span class="ml-2 text-sm text-gray-700">Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <!-- Login Button -->
                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold py-3 px-4 rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition shadow-lg"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Log In
                </button>
            </form>

            <!-- Divider -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-center text-xs text-gray-500">
                    © {{ date('Y') }} BINUS University Bandung. All rights reserved.
                </p>
            </div>
        </div>

        <!-- Help Info -->
        <div class="mt-6 bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-4 text-white text-center">
            <p class="text-sm mb-2">
                <i class="fas fa-info-circle mr-1"></i>
                Butuh bantuan?
            </p>
            <p class="text-xs">
                Hubungi: <a href="mailto:sadc@binus.ac.id" class="underline hover:text-blue-200">sadc@binus.ac.id</a>
            </p>
        </div>
    </div>

</body>
</html>