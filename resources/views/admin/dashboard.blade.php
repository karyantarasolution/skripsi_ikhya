<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">Dashboard Administrator</h2>
        <p class="text-sm text-gray-600 mt-1">Selamat datang kembali, {{ Auth::user()->name }}. Ini ringkasan sistem Anda.</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                    <div class="p-4 bg-indigo-50 text-indigo-600 rounded-lg"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase">Total Kegiatan</p>
                        <p class="text-2xl font-black text-gray-900">{{ $total_kegiatan }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                    <div class="p-4 bg-emerald-50 text-emerald-600 rounded-lg"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg></div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase">Giat Bulan Ini</p>
                        <p class="text-2xl font-black text-gray-900">{{ $kegiatan_bulan_ini }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                    <div class="p-4 bg-amber-50 text-amber-600 rounded-lg"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase">Arsip Tersimpan</p>
                        <p class="text-2xl font-black text-gray-900">{{ $total_dokumentasi }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                    <div class="p-4 bg-rose-50 text-rose-600 rounded-lg"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase">Pengguna Sistem</p>
                        <p class="text-2xl font-black text-gray-900">{{ $total_user }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center">
                    <p class="text-xs font-bold text-blue-600 uppercase tracking-wider">Diajukan</p>
                    <p class="text-3xl font-black text-blue-700">{{ $kegiatan_diajukan ?? 0 }}</p>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
                    <p class="text-xs font-bold text-green-600 uppercase tracking-wider">Disetujui</p>
                    <p class="text-3xl font-black text-green-700">{{ $kegiatan_disetujui ?? 0 }}</p>
                </div>
                <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 text-center">
                    <p class="text-xs font-bold text-purple-600 uppercase tracking-wider">LPJ Upload</p>
                    <p class="text-3xl font-black text-purple-700">{{ $kegiatan_lpj ?? 0 }}</p>
                </div>
                <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4 text-center">
                    <p class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Total Penugasan</p>
                    <p class="text-3xl font-black text-indigo-700">{{ $total_penugasan ?? 0 }}</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900">Rekap Upload per Staf Peliput</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-white text-gray-400 uppercase text-[10px] font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Nama Staf</th>
                                <th class="px-6 py-4 text-center">Jumlah Upload File</th>
                                <th class="px-6 py-4 text-center">Ranking</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($staf_upload as $index => $s)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $s->name }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-sm font-bold bg-indigo-100 text-indigo-800">
                                            {{ $s->dokumentasi_count }} file
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($index == 0)
                                            <span class="text-yellow-500 text-lg">&#9733;</span>
                                        @else
                                            <span class="text-gray-300">#{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-500 font-medium">Belum ada data upload.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900">Agenda Kegiatan Mendatang</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-white text-gray-400 uppercase text-[10px] font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Waktu & Tanggal</th>
                                <th class="px-6 py-4">Nama Kegiatan</th>
                                <th class="px-6 py-4">Kategori</th>
                                <th class="px-6 py-4">Lokasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($jadwal_terdekat as $jadwal)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-indigo-600">{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</div>
                                        <div class="text-xs font-medium text-gray-500 mt-1">{{ \Carbon\Carbon::parse($jadwal->waktu)->format('H:i') }} WITA</div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-900">{{ $jadwal->judul_kegiatan }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-600 border border-gray-200">
                                            {{ $jadwal->kategori->nama_kategori ?? 'Umum' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-medium">{{ $jadwal->lokasi }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500 font-medium">Belum ada agenda kegiatan mendatang yang dijadwalkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
