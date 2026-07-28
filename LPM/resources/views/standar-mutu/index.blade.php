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
            <button type="button" onclick="openImportModal()" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 rounded-lg shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
                <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Import Excel
            </button>
            <a href="{{ route('standar-mutu.export') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors duration-150">
                <svg class="w-4 h-4 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export Excel
            </a>
            <a href="{{ route('standar-mutu.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
                <svg class="w-4 h-4 mr-1 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Standar
            </a>
        </div>
        @endcan
    </div>

    <div id="table-container" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
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
        <div class="relative inline-block align-bottom bg-white rounded-xl p-6 sm:p-8 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-100">
            <div>
                <!-- Centered Icon -->
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 text-emerald-600 mb-4">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </div>
                
                <!-- Text Header -->
                <div class="text-center">
                    <h3 class="text-lg leading-6 font-bold text-slate-800" id="modal-title">Import Standar Mutu</h3>
                    <p class="text-xs text-slate-500 mt-1.5 px-2">Unggah berkas Excel (.xlsx/.xls) untuk melakukan sinkronisasi data Standar Mutu. ID unik digunakan untuk memperbarui data, sedangkan baris baru akan ditambahkan.</p>
                </div>

                <!-- Form Block -->
                <form action="{{ route('standar-mutu.import') }}" method="POST" enctype="multipart/form-data" class="mt-5 space-y-4">
                    @csrf
                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-lg">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih File Excel</label>
                        <input type="file" name="file" accept=".xlsx,.xls" required class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 mt-6 border-t border-slate-100">
                        <button type="button" onclick="closeImportModal()" class="w-full sm:w-auto inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">Batal</button>
                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-sm font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">Mulai Import</button>
                    </div>
                </form>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    if (!searchInput) return;

    const searchForm = searchInput.closest('form');
    let timeout = null;

    function performSearch() {
        const query = searchInput.value;
        const url = new URL(window.location.href);
        url.searchParams.set('search', query);
        url.searchParams.delete('page');

        fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTable = doc.getElementById('table-container');
            if (newTable) {
                document.getElementById('table-container').innerHTML = newTable.innerHTML;
            }
            window.history.pushState({path: url.toString()}, '', url.toString());
        })
        .catch(err => console.error(err));
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(performSearch, 300);
    });

    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            clearTimeout(timeout);
            performSearch();
        });
    }
});
</script>
@endpush
@endsection
