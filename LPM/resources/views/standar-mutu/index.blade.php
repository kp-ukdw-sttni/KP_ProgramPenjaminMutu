@extends('layouts.app')

@section('title', 'Daftar Standar Mutu')

@section('breadcrumb')
<nav class="flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('dashboard') }}" class="hover:text-slate-800">Dashboard</a>
    <span>/</span>
    <span class="font-semibold text-slate-800">Standar Mutu</span>
</nav>
@endsection

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form action="{{ route('standar-mutu.index') }}" method="GET" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari standar mutu..." class="border-slate-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-slate-800 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">Cari</button>
        </form>

        @can('manage-standar')
        <div class="flex items-center gap-2">
            <button type="button" onclick="openImportModal()" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 rounded-md shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
                📁 Import Excel
            </button>
            <a href="{{ route('standar-mutu.export') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150">
                📥 Export Excel
            </a>
            <a href="{{ route('standar-mutu.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
                + Tambah Standar
            </a>
        </div>
        @endcan
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Kode Standar</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama Standar</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Target Capaian</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Jumlah Evaluasi</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse ($standars as $index => $standar)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            {{ $standars->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                            {{ $standar->kode_standar }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            {{ $standar->nama_standar }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                            {{ $standar->target_capaian }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                            <a href="{{ route('evaluasi-diri.index', ['standar_id' => $standar->id]) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                {{ $standar->evaluasi_diri_count ?? 0 }} Evaluasi
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @can('manage-standar')
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('standar-mutu.edit', $standar) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <form action="{{ route('standar-mutu.destroy', $standar) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus standar ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                </form>
                            </div>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-center text-slate-500">
                            Tidak ada data standar mutu.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($standars->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 bg-white">
            {{ $standars->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

@can('manage-standar')
<!-- Import Excel Modal -->
<div id="import-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Overlay -->
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-500/75 transition-opacity" aria-hidden="true" onclick="closeImportModal()"></div>

        <!-- Trick browser to center contents -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal Panel -->
        <div class="relative inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 border border-slate-100">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 text-green-600 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left min-w-0 flex-1">
                    <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Import Standar Mutu</h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">Pilih berkas Excel (.xlsx atau .xls) untuk mengunggah dan melakukan sinkronisasi data Standar Mutu. Sistem akan menggunakan kolom ID untuk melakukan pembaruan (update) data, atau menyisipkan data baru jika ID tidak ditemukan.</p>
                    </div>

                    <form action="{{ route('standar-mutu.import') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">File Excel (.xlsx/.xls)</label>
                            <input type="file" name="file" accept=".xlsx,.xls" required class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 border border-slate-200 rounded-md p-1 bg-slate-50">
                        </div>

                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-2">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-150">Mulai Import</button>
                            <button type="button" onclick="closeImportModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors duration-150">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openImportModal() {
    document.getElementById('import-modal').classList.remove('hidden');
}
function closeImportModal() {
    document.getElementById('import-modal').classList.add('hidden');
}
</script>
@endpush
@endcan
@endsection
