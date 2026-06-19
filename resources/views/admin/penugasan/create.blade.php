<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.penugasan.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Tambah Penugasan Liputan</h2>
                <p class="text-sm text-gray-500 mt-1">Atur pembagian staf untuk meliput kegiatan.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <form action="{{ route('admin.penugasan.store') }}" method="POST" class="p-6 sm:p-8">
                    @csrf
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Kegiatan <span class="text-red-500">*</span></label>
                            <select name="kegiatan_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                                <option value="" disabled selected>-- Pilih Kegiatan --</option>
                                @foreach($kegiatan as $k)
                                    <option value="{{ $k->id }}">{{ \Carbon\Carbon::parse($k->tanggal)->format('d/m/Y') }} - {{ $k->judul_kegiatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Staf Peliput <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                @foreach($staf as $s)
                                    <label class="flex items-center gap-2 p-2 bg-white rounded border border-gray-200 hover:border-gray-400 cursor-pointer">
                                        <input type="checkbox" name="user_id[]" value="{{ $s->id }}" class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                                        <span class="text-sm text-gray-700">{{ $s->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Penugasan <span class="text-red-500">*</span></label>
                            <select name="jenis" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                                <option value="individu">Individu</option>
                                <option value="tim">Tim</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
                            <textarea name="keterangan" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900" placeholder="Catatan tambahan..."></textarea>
                        </div>
                    </div>
                    <div class="mt-8 pt-5 border-t border-gray-100 flex justify-end gap-3">
                        <a href="{{ route('admin.penugasan.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">Batal</a>
                        <button type="submit" class="px-5 py-2.5 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium shadow-sm">Simpan Penugasan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
