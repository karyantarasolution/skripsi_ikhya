<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Surat Perjalanan Dinas (SPPD)</h2>
                <p class="text-sm text-gray-500 mt-1">Buat surat perjalanan dinas beserta rincian biaya untuk pencairan dana.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm flex items-center">
                    <div class="flex-shrink-0"><svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></div>
                    <div class="ml-3"><p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p></div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm flex items-center">
                    <div class="flex-shrink-0"><svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg></div>
                    <div class="ml-3"><p class="text-sm font-medium text-red-800">{{ session('error') }}</p></div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-900">Daftar Perjalanan Dinas</h3>
                    <a href="{{ route('dokumen.sppd.create') }}" class="bg-gray-900 hover:bg-gray-800 text-white font-medium py-2.5 px-5 rounded-lg flex items-center transition-all shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Buat SPPD
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-100/75 text-gray-700 uppercase text-xs font-semibold">
                            <tr>
                                <th class="px-6 py-4">Nomor SPPD</th>
                                <th class="px-6 py-4">Petugas</th>
                                <th class="px-6 py-4">Tujuan</th>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Total Biaya</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @php
                                $statusClasses = [
                                    'draf' => 'bg-gray-100 text-gray-800 border-gray-200',
                                    'diajukan' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'disetujui' => 'bg-green-100 text-green-800 border-green-200',
                                    'ditolak' => 'bg-red-100 text-red-800 border-red-200',
                                ];
                            @endphp
                            @forelse($sppd as $item)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 font-semibold text-gray-900 whitespace-nowrap">{{ $item->no_surat ?? 'Belum bernomor' }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse($item->peserta as $ps)
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                    {{ $ps->user->name ?? '?' }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-gray-400 italic">{{ $item->user->name ?? '?' }}</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900">{{ $item->kota_tujuan }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->tujuan }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($item->tanggal_berangkat)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($item->totalBiaya(), 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">
                                        @php $class = $statusClasses[$item->status] ?? 'bg-gray-100 text-gray-800 border-gray-200'; @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $class }}">
                                            {{ $item->status }}
                                        </span>
                                        @if($item->status === 'ditolak' && $item->catatan)
                                            <div class="text-xs text-red-600 mt-1">Alasan: {{ Str::limit($item->catatan, 30) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1.5 items-center">
                                            <a href="{{ route('dokumen.sppd.cetak', $item->id) }}" target="_blank" class="w-full text-center text-xs px-2 py-1 bg-gray-900 text-white rounded hover:bg-gray-800">Cetak PDF</a>
                                            @if($item->status === 'draf' || $item->status === 'ditolak')
                                                <form action="{{ route('dokumen.sppd.ajukan', $item->id) }}" method="POST" class="w-full">
                                                    @csrf
                                                    <button type="submit" class="w-full text-xs px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Ajukan</button>
                                                </form>
                                            @endif
                                            @if(Auth::user()->role === 'admin' && in_array($item->status, ['diajukan']))
                                                <form action="{{ route('dokumen.sppd.setujui', $item->id) }}" method="POST" class="w-full" onsubmit="return confirm('Setujui SPPD ini?')">
                                                    @csrf
                                                    <button type="submit" class="w-full text-xs px-2 py-1 bg-emerald-600 text-white rounded hover:bg-emerald-700">Setujui</button>
                                                </form>
                                                <button onclick="document.getElementById('tolak-{{ $item->id }}').showModal()" class="w-full text-xs px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700">Tolak</button>
                                                <dialog id="tolak-{{ $item->id }}" class="rounded-xl p-0 w-full max-w-md">
                                                    <form method="POST" action="{{ route('dokumen.sppd.tolak', $item->id) }}" class="p-6">
                                                        @csrf
                                                        <h3 class="text-lg font-bold text-gray-900 mb-3">Tolak SPPD</h3>
                                                        <textarea name="catatan" rows="3" required placeholder="Alasan penolakan..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900"></textarea>
                                                        <div class="flex justify-end gap-3 mt-4">
                                                            <button type="button" onclick="document.getElementById('tolak-{{ $item->id }}').close()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Batal</button>
                                                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Tolak SPPD</button>
                                                        </div>
                                                    </form>
                                                </dialog>
                                            @endif
                                            <div class="flex gap-1 w-full">
                                                @if(!in_array($item->status, ['disetujui', 'ditolak']))
                                                    <a href="{{ route('dokumen.sppd.edit', $item->id) }}" class="flex-1 text-center text-xs px-2 py-1 text-blue-600 bg-blue-50 rounded hover:bg-blue-100">Edit</a>
                                                @endif
                                                @if(!in_array($item->status, ['disetujui', 'ditolak']))
                                                    <form action="{{ route('dokumen.sppd.destroy', $item->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus SPPD ini?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="w-full text-xs px-2 py-1 text-red-600 bg-red-50 rounded hover:bg-red-100">Hapus</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">Belum ada Surat Perjalanan Dinas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
