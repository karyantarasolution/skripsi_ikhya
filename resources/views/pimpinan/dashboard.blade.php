<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">Dashboard Pimpinan</h2>
        <p class="text-sm text-gray-600 mt-1">Selamat datang, Bapak/Ibu {{ Auth::user()->name }}. Ini adalah pantauan kegiatan saat ini.</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-xl shadow-md p-6 text-white flex items-center gap-4">
                    <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                    <div>
                        <p class="text-sm font-semibold text-indigo-100 uppercase tracking-wider">Total Kegiatan</p>
                        <p class="text-3xl font-black">{{ $total_kegiatan }}</p>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-xl shadow-md p-6 text-white flex items-center gap-4">
                    <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg></div>
                    <div>
                        <p class="text-sm font-semibold text-emerald-100 uppercase tracking-wider">Giat Bulan Ini</p>
                        <p class="text-3xl font-black">{{ $kegiatan_bulan_ini }}</p>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-amber-500 to-amber-700 rounded-xl shadow-md p-6 text-white flex items-center gap-4">
                    <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                    <div>
                        <p class="text-sm font-semibold text-amber-100 uppercase tracking-wider">Arsip Dokumen</p>
                        <p class="text-3xl font-black">{{ $total_dokumentasi }}</p>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl shadow-md p-6 text-white flex items-center gap-4">
                    <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></div>
                    <div>
                        <p class="text-sm font-semibold text-blue-100 uppercase tracking-wider">Pengajuan</p>
                        <p class="text-3xl font-black">{{ $kegiatan_diajukan ?? 0 }}</p>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-xl shadow-md p-6 text-white flex items-center gap-4">
                    <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                    <div>
                        <p class="text-sm font-semibold text-purple-100 uppercase tracking-wider">LPJ Upload</p>
                        <p class="text-3xl font-black">{{ $kegiatan_lpj ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 text-center">
                    <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Disetujui</p>
                    <p class="text-3xl font-black text-emerald-700">{{ $kegiatan_disetujui ?? 0 }}</p>
                </div>
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-center">
                    <p class="text-xs font-bold text-red-600 uppercase tracking-wider">Ditolak</p>
                    <p class="text-3xl font-black text-red-700">{{ $kegiatan_ditolak ?? 0 }}</p>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center">
                    <p class="text-xs font-bold text-blue-600 uppercase tracking-wider">Menunggu Persetujuan</p>
                    <p class="text-3xl font-black text-blue-700">{{ $kegiatan_diajukan ?? 0 }}</p>
                </div>
            </div>

            @if(($pengajuan_terbaru ?? collect())->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900">Pengajuan Kegiatan Menunggu Persetujuan</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-white text-gray-400 uppercase text-[10px] font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Kegiatan</th>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Pengaju</th>
                                <th class="px-6 py-4">RAB</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($pengajuan_terbaru as $item)
                                <tr class="hover:bg-gray-50 transition-colors" x-data="{ openTolak: false }">
                                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $item->judul_kegiatan }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                    <td class="px-6 py-4">{{ $item->user->name ?? '?' }}</td>
                                    <td class="px-6 py-4">
                                        @if($item->rab_file)
                                            <a href="{{ asset($item->rab_file) }}" target="_blank" class="text-blue-600 hover:underline text-xs">Lihat RAB</a>
                                        @else
                                            <span class="text-gray-400 text-xs">Tidak ada</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            <form action="{{ route('persetujuan.kegiatan.approve', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-emerald-600 text-white text-xs rounded-lg hover:bg-emerald-700 font-semibold">
                                                    Setujui
                                                </button>
                                            </form>
                                            <button @click="openTolak = true" class="px-3 py-1.5 bg-red-600 text-white text-xs rounded-lg hover:bg-red-700 font-semibold">
                                                Tolak
                                            </button>
                                        </div>

                                        <div x-show="openTolak" style="display: none;" class="relative z-50">
                                            <div class="fixed inset-0 bg-gray-900 bg-opacity-75"></div>
                                            <div class="fixed inset-0 z-10 overflow-y-auto">
                                                <div class="flex min-h-full items-center justify-center p-4 text-center">
                                                    <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md" @click.away="openTolak = false">
                                                        <form action="{{ route('persetujuan.kegiatan.tolak', $item->id) }}" method="POST">
                                                            @csrf
                                                            <div class="p-6 text-center">
                                                                <svg class="mx-auto mb-4 text-red-500 w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                                <h3 class="mb-2 text-lg font-bold text-gray-900">Tolak Kegiatan</h3>
                                                                <textarea name="catatan_penolakan" rows="3" required placeholder="Alasan penolakan..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 mb-4"></textarea>
                                                                <div class="flex justify-center gap-3">
                                                                    <button type="button" @click="openTolak = false" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Batal</button>
                                                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Tolak</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mt-8">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900">Pantauan Agenda Mendatang</h3>
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
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500 font-medium">Belum ada agenda kegiatan mendatang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
