<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Riwayat Tanda Tangan Digital</h2>
        <p class="text-sm text-gray-500 mt-1">Catatan seluruh pengesahan dokumen secara digital dengan hash verifikasi SHA-256.</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm flex items-center">
                    <div class="flex-shrink-0"><svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></div>
                    <div class="ml-3"><p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p></div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <h3 class="text-lg font-bold text-gray-900">Daftar Riwayat TTD Digital</h3>
                        <form action="{{ route('admin.ttd-digital.riwayat') }}" method="GET" class="flex gap-2">
                            <select name="dokumen_type" class="text-sm rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                                <option value="">Semua Jenis</option>
                                <option value="sppd" {{ request('dokumen_type') == 'sppd' ? 'selected' : '' }}>SPPD</option>
                                <option value="lpj_tugas" {{ request('dokumen_type') == 'lpj_tugas' ? 'selected' : '' }}>LPJ Tugas</option>
                                <option value="surat_tugas" {{ request('dokumen_type') == 'surat_tugas' ? 'selected' : '' }}>Surat Tugas</option>
                            </select>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor/hash..." class="text-sm rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                            <button type="submit" class="bg-gray-900 hover:bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-medium">Cari</button>
                        </form>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-100/75 text-gray-700 uppercase text-xs font-semibold">
                            <tr>
                                <th class="px-6 py-4">Tanggal TTD</th>
                                <th class="px-6 py-4">Pengesah</th>
                                <th class="px-6 py-4">Jenis Dokumen</th>
                                <th class="px-6 py-4">No. Dokumen</th>
                                <th class="px-6 py-4">Pejabat Penandatangan</th>
                                <th class="px-6 py-4">Hash SHA-256</th>
                                <th class="px-6 py-4 text-center">QR</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($riwayat as $item)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900">{{ $item->disahkan_at ? \Carbon\Carbon::parse($item->disahkan_at)->format('d/m/Y') : '-' }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->disahkan_at ? \Carbon\Carbon::parse($item->disahkan_at)->format('H:i') : '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $item->disahkanOleh->name ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded text-xs font-bold uppercase
                                            {{ $item->dokumen_type === 'sppd' ? 'bg-blue-100 text-blue-700' : '' }}
                                            {{ $item->dokumen_type === 'lpj_tugas' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $item->dokumen_type === 'surat_tugas' ? 'bg-purple-100 text-purple-700' : '' }}
                                        ">{{ $item->dokumen_type }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-xs">{{ $item->nomor_dokumen ?? '-' }}</td>
                                    <td class="px-6 py-4 text-xs">{{ $item->penandatangan->nama_pejabat ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <code class="text-[10px] bg-gray-100 px-2 py-1 rounded font-mono break-all">{{ substr($item->hash_sha256, 0, 24) }}...</code>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($item->qr_code_path)
                                            <img src="{{ asset($item->qr_code_path) }}" alt="QR TTD" class="inline-block w-10 h-10 border border-gray-200 rounded cursor-pointer" onclick="window.open('{{ asset($item->qr_code_path) }}', '_blank')">
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">Belum ada riwayat Tanda Tangan Digital.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($riwayat->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $riwayat->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
