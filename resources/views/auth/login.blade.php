<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Arsip Adpim Kalsel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-900">
    <div class="min-h-screen flex">
        <div class="hidden lg:flex lg:w-1/2 bg-white items-center justify-center border-r border-gray-200">
            <div class="max-w-md text-center">
                <img src="{{ asset('images/logoikhya.png') }}" alt="Logo Kalsel" class="h-32 mx-auto mb-6">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Biro Adpim Kalsel</h2>
                <p class="text-gray-500">Sistem Informasi Manajemen Peliputan dan Arsip Dokumentasi Kegiatan Berbasis Web</p>
            </div>
        </div>
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50">
            <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8 border border-gray-100">
                <div class="mb-8 text-center lg:hidden">
                    <img src="{{ asset('images/logoikhya.png') }}" alt="Logo Kalsel" class="h-20 mx-auto mb-4">
                    <h2 class="text-2xl font-bold text-gray-800">Biro Adpim Kalsel</h2>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center lg:text-left">Masuk ke Akun Anda</h3>
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-800 focus:border-gray-800 transition-colors" placeholder="Masukkan email anda">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input id="password" type="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-800 focus:border-gray-800 transition-colors" placeholder="••••••••">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-gray-800 shadow-sm focus:ring-gray-800">
                            <span class="ml-2 text-sm text-gray-600">Ingat Saya</span>
                        </label>
                    </div>
                    <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-semibold py-2.5 rounded-lg shadow-md transition duration-300 ease-in-out">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>