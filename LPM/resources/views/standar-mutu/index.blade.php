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
        <a href="{{ route('standar-mutu.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            + Tambah Standar
        </a>
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
@endsection
