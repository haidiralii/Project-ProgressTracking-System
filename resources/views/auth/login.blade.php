<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>

    <!-- Font Awesome (CDN) -->
    <link 
        rel="stylesheet" 
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" 
        integrity="sha512-XxXx..." 
        crossorigin="anonymous" 
        referrerpolicy="no-referrer" 
    />
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome Kit (duplikat, opsional jika sudah pakai link di atas) -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <!-- Konfigurasi Tailwind -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'red-primary': '#C02121',
                        'red-light': '#CA2626',
                        'red-soft': '#C54444',
                        'red-bright': '#F72727',
                    },
                    animation: {
                        'fade-in': 'fadeIn 2s ease-in-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        }
                    }
                }
            }
        }
    </script>

    <!-- Animasi Custom (CSS) -->
    <style>
        /* Blob Animation */
        @keyframes slideInTopLeft {
        0% {
            transform: translate(-50%, -50%);
            opacity: 0;
        }
        100% {
            transform: translate(0, 0);
            opacity: 1;
        }
    }

    @keyframes slideInBottomRight {
        0% {
            transform: translate(50%, 50%);
            opacity: 0;
        }
        100% {
            transform: translate(0, 0);
            opacity: 1;
        }
    }

    @keyframes blobPulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
    }

    .animate-slide-in-top-left {
        animation: slideInTopLeft 2s ease-out forwards;
    }

    .animate-slide-in-bottom-right {
        animation: slideInBottomRight 3s ease-out forwards;
    }

    .animate-blob-pulse {
        animation: blobPulse 10s ease-in-out infinite;
    }

        /* Tambahkan style tambahan di sini jika diperlukan */

    </style>

    <!-- Script: Konfirmasi Sign Up sebagai Admin -->
    <script>
        function confirmAdminSignup(event) {
            event.preventDefault();
            const confirmMsg = "PERHATIAN: Anda akan membuat akun dengan peran sebagai ADMIN. Pastikan Anda memahami hak akses serta tanggung jawab sebagai Admin sebelum melanjutkan proses pendaftaran.";
            if (confirm(confirmMsg)) {
                window.location.href = event.target.href;
            }
        }
    </script>

    <!-- Tambahkan script tambahan di sini jika diperlukan -->

</head>
<body class="min-h-screen flex items-center justify-center relative overflow-hidden bg-cover bg-center" style="background-image: url('/images/bg-red.png');">
    
    <!-- Blob kiri atas -->
    <svg id="blobTopLeft" class="absolute -top-40 -left-40 w-[600px] h-[600px] z-0 opacity-70 animate-slide-in-top-left" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="redGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#FF6B5E;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#FF3C3C;stop-opacity:1" />
            </linearGradient>
        </defs>
        <path fill="url(#redGradient)" d="M421.5,331Q400,412,326.5,445.5Q253,479,170.5,442.5Q88,406,73,317.5Q58,229,123.5,166.5Q189,104,282.5,89.5Q376,75,413.5,157.5Q451,240,421.5,331Z" />
    </svg>

    <!-- Blob kanan bawah -->
    <svg id="blobBottomRight" class="absolute bottom-[-190px] right-[-190px] w-[500px] h-[500px] z-0 opacity-60 animate-slide-in-bottom-right" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
        <path fill="#FF7878" d="M428.5,335Q400,430,316,457Q232,484,172,420.5Q112,357,94.5,284Q77,211,130,152Q183,93,276,93.5Q369,94,420.5,167Q472,240,428.5,335Z" />
    </svg>
    
    
    <div class="relative z-10 w-full max-w-md px-6 py-10 flex flex-col items-center animate-fade-in">
        <!-- Logo gear icon -->
        <div class="relative rounded-lg px-6 py-4 bg-maroon-light/20 mb-8 w-full flex justify-center">
            <div class="absolute inset-0 flex items-center justify-center opacity-80">
                <i id="gear-icon" class="fas fa-gears text-white text-7xl animate-fade-in"></i>
            </div>
        </div>

            @if(session('error'))
                <div class="mb-4 px-4 py-2 rounded-xl bg-red-100 text-red-700 font-semibold text-sm w-full text-center shadow animate-fade-in">
                    {{ session('error') }}
                </div>
            @endif

        <form method="POST" action="{{ route('login') }}" class="w-full space-y-5">
            @csrf
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-white mb-2">Email</label>
                <div class="relative">
                    <!-- Ikon -->
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-envelope text-red-primary"></i>
                    </div>
                    <!-- Input -->
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 bg-white/80 text-gray-900 focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 placeholder:text-gray-400"
                        placeholder="Enter your email">
                </div>
                <!-- Error -->
                @error('email')
                    <div class="text-red-200 text-xs mt-2 font-semibold">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-white mb-2">Password</label>
                <div class="relative">
                    <!-- Ikon -->
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-red-primary">
                        <i class="fas fa-lock"></i>
                    </span>
                    <!-- Input -->
                    <input id="password" type="password" name="password" required
                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 bg-white/80 text-gray-900 focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 placeholder:text-gray-400"
                        placeholder="Enter your password">
                </div>
                <!-- Error -->
                @error('password')
                    <div class="text-red-200 text-xs mt-2 font-semibold">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full py-3 rounded-xl font-bold text-white bg-gradient-to-r from-red-600 to-red-700 shadow-lg hover:from-red-700 hover:to-red-800 transition-all duration-200 text-lg tracking-wide">
                LOGIN
            </button>
        </form>

        <div class="mt-6 text-center text-white text-sm">
            <a href="{{ route('register') }}" 
            onclick="confirmAdminSignup(event)" 
            class="underline hover:text-red-200 transition">
            Create an admin account
            </a><br>
            <a href="{{ route('password.request') }}" class="underline hover:text-red-200 transition">Forgot password?</a>
        </div>
    </div>
</body>
</html>
