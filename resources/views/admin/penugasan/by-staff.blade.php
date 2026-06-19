<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Rekap Upload per Staf</h2>
        <p class="text-sm text-gray-500 mt-1">Pendataan jumlah upload file dokumentasi berdasarkan staf peliput.</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @foreach($staf as $s)
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $s->name }}</h3>
                            <p class="text-sm text-gray-500">{{ ucfirst($s->role) }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500 font-bold uppercase">Total Upload</p>
                            <p class="text-3xl font-black text-indigo-600">{{ $s->dokumentasi_count }}</p>
                        </div>
                    </div>
                    @if($s->penugasan->count() > 0)
                        <div class="px-6 py-3 border-b border-gray-100 bg-gray-50">
                            <p class="text-xs font-bold text-gray-600 uppercase tracking-wider">Penugasan:</p>
                            <div class="flex flex-wrap gap-2 mt-1">
                                @foreach($s->penugasan as $p)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        {{ $p->kegiatan->judul_kegiatan ?? 'Kegiatan Dihapus' }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach

        </div>
    </div>
</x-app-layout>
