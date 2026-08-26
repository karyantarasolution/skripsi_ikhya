<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Penugasan Liputan</h2>
        <p class="text-sm text-gray-500 mt-1">Kelola pembagian tugas liputan untuk staf peliput.</p>
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
                <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-900">Daftar Penugasan per Kegiatan</h3>
                    <a href="{{ route('admin.penugasan.create') }}" class="bg-gray-900 hover:bg-gray-800 text-white font-medium py-2.5 px-5 rounded-lg flex items-center transition-all shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Penugasan
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-100/75 text-gray-700 uppercase text-xs font-semibold">
                            <tr>
                                <th class="px-6 py-4">Kegiatan</th>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Staf Ditugaskan</th>
                                <th class="px-6 py-4">Jenis</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($kegiatan as $item)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $item->judul_kegiatan }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->kategori->nama_kategori ?? 'Umum' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($item->penugasan as $p)
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                    {{ $p->user->name ?? '?' }}
                                                    <form action="{{ route('admin.penugasan.destroy', $p->id) }}" method="POST" class="ml-1" onsubmit="return confirm('Hapus penugasan {{ $p->user->name ?? '?' }}?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="text-indigo-400 hover:text-red-600 transition-colors" title="Hapus">
                                                            <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                    </form>
                                                </span>
                                            @endforeach
                                            @if($item->penugasan->isEmpty())
                                                <span class="text-xs text-gray-400 italic">Belum ada</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php $jenisList = $item->penugasan->pluck('jenis')->unique(); @endphp
                                        @foreach($jenisList as $j)
                                            <span class="text-xs font-bold uppercase {{ $j == 'tim' ? 'text-blue-600' : 'text-emerald-600' }}">{{ $j }}</span>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('admin.penugasan.edit', $item->penugasan->first()?->id ?? 0) }}" class="text-xs px-3 py-1.5 text-blue-600 bg-blue-50 rounded hover:bg-blue-100 inline-flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">Belum ada penugasan liputan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
