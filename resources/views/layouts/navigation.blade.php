<div x-show="sidebarOpen" class="fixed inset-0 z-20 transition-opacity bg-gray-900 bg-opacity-50 lg:hidden" @click="sidebarOpen = false"></div>

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-64 h-screen bg-gray-900 text-white transition duration-300 transform lg:translate-x-0 lg:static lg:inset-0 flex flex-col">
    
    <div class="flex items-center justify-center h-20 border-b border-gray-800 shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4">
            <img src="{{ asset('images/logoikhya.png') }}" alt="Logo" class="h-10 w-auto bg-white rounded-full p-1">
            <div class="flex flex-col">
                <span class="text-lg font-bold uppercase tracking-wider text-white">SIA Biro Adpim</span>
                <span class="text-xs text-gray-400">Pemprov Kalsel</span>
            </div>
        </a>
    </div>

    <nav class="flex-1 px-4 py-5 space-y-2 overflow-y-auto custom-scrollbar">
        
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition-colors {{ request()->routeIs('*dashboard*') ? 'bg-gray-800 border-l-4 border-yellow-400 text-yellow-400' : 'text-gray-300' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Dashboard
        </a>

        @if(Auth::user()->role === 'admin')
            <p class="px-4 pt-4 pb-2 text-xs font-bold text-gray-500 uppercase">Kelola Data Master</p>
            
            <a href="{{ route('admin.user.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition-colors {{ request()->routeIs('admin.user.*') ? 'bg-gray-800 border-l-4 border-yellow-400 text-yellow-400' : 'text-gray-300' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Manajemen User
            </a>
            
            <a href="{{ route('admin.kategori.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition-colors {{ request()->routeIs('admin.kategori.*') ? 'bg-gray-800 border-l-4 border-yellow-400 text-yellow-400' : 'text-gray-300' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                Kategori Kegiatan
            </a>
            
            <a href="{{ route('admin.penandatangan.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition-colors {{ request()->routeIs('admin.penandatangan.*') ? 'bg-gray-800 border-l-4 border-yellow-400 text-yellow-400' : 'text-gray-300' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Data Penandatangan
            </a>
        @endif

        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'staf')
            <p class="px-4 pt-4 pb-2 text-xs font-bold text-gray-500 uppercase">Peliputan Lapangan</p>
            
            <a href="{{ route('peliputan.kegiatan.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition-colors {{ request()->routeIs('peliputan.kegiatan.*') ? 'bg-gray-800 border-l-4 border-yellow-400 text-yellow-400' : 'text-gray-300' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Jadwal Kegiatan
            </a>
            
            <a href="{{ route('peliputan.arsip.global') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition-colors {{ request()->routeIs('peliputan.arsip.global') || request()->routeIs('peliputan.dokumentasi.*') ? 'bg-gray-800 border-l-4 border-yellow-400 text-yellow-400' : 'text-gray-300' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Arsip Dokumentasi
            </a>
        @endif

        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'pimpinan')
            <p class="px-4 pt-4 pb-2 text-xs font-bold text-gray-500 uppercase">Laporan & Evaluasi</p>
            
         <a href="{{ route('laporan.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition-colors {{ request()->routeIs('laporan.*') ? 'bg-gray-800 border-l-4 border-yellow-400 text-yellow-400' : 'text-gray-300' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Cetak 8 Laporan
            </a>
        @endif
        
    </nav>
</aside>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #4B5563;
        border-radius: 20px;
    }
</style>