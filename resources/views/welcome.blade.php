<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIA - Biro Administrasi Pimpinan</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-900 selection:bg-indigo-500 selection:text-white">
    <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-indigo-500 selection:text-white">
        
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-[url('https://laravel.com/assets/img/welcome/background.svg')] bg-center bg-cover opacity-20"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-gray-900/80 via-gray-900/90 to-gray-900"></div>
        </div>

        <div class="relative z-10 w-full max-w-2xl px-6 lg:max-w-4xl flex flex-col items-center text-center">
            
            <div class="mb-8 flex justify-center">
                <div class="bg-white p-4 rounded-full shadow-2xl">
                    <img src="{{ asset('images/logoikhya.png') }}" alt="Logo Kalsel" class="h-24 w-auto">
                </div>
            </div>

            <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight mb-4">
                Sistem Informasi Arsip & Peliputan<br>
                <span class="text-yellow-400">Biro Administrasi Pimpinan</span>
            </h1>
            
            <p class="text-lg text-gray-300 mb-10 max-w-2xl font-medium">
                Pemerintah Provinsi Kalimantan Selatan<br>
                Platform digital terpadu untuk manajemen jadwal kegiatan pimpinan, pengarsipan dokumentasi lapangan, dan pelaporan Biro Adpim secara *real-time*.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center w-full max-w-md">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" class="w-full flex items-center justify-center px-8 py-3.5 border border-transparent text-base font-bold rounded-lg text-gray-900 bg-yellow-400 hover:bg-yellow-300 transition-all shadow-lg hover:shadow-yellow-400/30">
                            Masuk ke Dashboard
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-8 py-3.5 border border-transparent text-base font-bold rounded-lg text-white bg-indigo-600 hover:bg-indigo-500 transition-all shadow-lg hover:shadow-indigo-500/30">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                            Login Sistem
                        </a>
                    @endauth
                @endif
            </div>

        </div>

        <div class="absolute bottom-6 z-10 text-center text-sm text-gray-400 font-medium">
            &copy; {{ date('Y') }} Biro Administrasi Pimpinan Setda Prov. Kalsel. Hak Cipta Dilindungi.
        </div>
    </div>
</body>
</html>