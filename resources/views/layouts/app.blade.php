<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Arsip Adpim Kalsel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        
        @include('layouts.navigation')

        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
            
            <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
                <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 h-16">
                    <div class="flex items-center lg:hidden">
                        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>

                    <div class="hidden lg:block">
                        @isset($header)
                            {{ $header }}
                        @endisset
                    </div>

                    <div class="flex items-center space-x-3" x-data="{ notifOpen: false, notifCount: 0, notifList: [] }" x-init="
                        fetch('{{ route('notifications.unread-count') }}').then(r => r.json()).then(d => notifCount = d.count);
                        setInterval(() => { fetch('{{ route('notifications.unread-count') }}').then(r => r.json()).then(d => notifCount = d.count); }, 30000);
                    ">
                        <div class="relative">
                            <button @click="notifOpen = !notifOpen; if(notifOpen) { fetch('{{ route('notifications.index') }}').then(r => r.json()).then(d => notifList = d); }" class="relative p-2 text-gray-500 hover:text-gray-700 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                <span x-show="notifCount > 0" x-text="notifCount" class="absolute -top-0.5 -right-0.5 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full"></span>
                            </button>
                            <div x-show="notifOpen" @click.away="notifOpen = false" style="display: none;" x-transition class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-2xl border border-gray-200 z-50 overflow-hidden">
                                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                                    <h3 class="text-sm font-bold text-gray-900">Notifikasi</h3>
                                    <button @click="fetch('{{ route('notifications.read-all') }}', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(() => { notifCount = 0; notifList = []; })" class="text-xs text-blue-600 hover:underline">Tandai semua dibaca</button>
                                </div>
                                <div class="max-h-80 overflow-y-auto">
                                    <template x-if="notifList.length === 0">
                                        <p class="p-4 text-sm text-gray-500 text-center">Tidak ada notifikasi baru</p>
                                    </template>
                                    <template x-for="n in notifList" :key="n.id">
                                        <div class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition-colors" :class="n.read_at ? '' : 'bg-blue-50'">
                                            <p class="text-xs font-bold text-gray-900" x-text="n.data?.title"></p>
                                            <p class="text-xs text-gray-600 mt-0.5" x-text="n.data?.body"></p>
                                            <div class="flex justify-between items-center mt-1">
                                                <span class="text-[10px] text-gray-400" x-text="new Date(n.created_at).toLocaleString('id-ID')"></span>
                                                <button x-show="!n.read_at" @click="fetch('{{ route('notifications.read', '__ID__') }}'.replace('__ID__', n.id), {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(() => { n.read_at = new Date().toISOString(); notifCount = Math.max(0, notifCount - 1); })" class="text-[10px] text-blue-600 hover:underline">Tandai dibaca</button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition duration-150 ease-in-out">
                                    <div class="mr-2 text-right hidden md:block">
                                        <div class="text-gray-800 font-bold">{{ Auth::user()->name }}</div>
                                        <div class="text-xs text-gray-400 capitalize">{{ Auth::user()->role }}</div>
                                    </div>
                                    <div class="h-8 w-8 rounded-full bg-gray-800 flex items-center justify-center text-white font-bold">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <svg class="fill-current h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                             

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Keluar (Log Out)') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </header>

            <main class="flex-1 w-full max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
                <div class="block lg:hidden mb-4">
                    @isset($header)
                        {{ $header }}
                    @endisset
                </div>
                
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>