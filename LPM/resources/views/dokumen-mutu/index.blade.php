@extends('layouts.app')

@section('title', 'Dokumen Mutu')

@section('breadcrumb')
<nav class="flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition-colors">Dashboard</a>
    <span>/</span>
    <span class="font-bold text-slate-800">Dokumen Mutu</span>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header & Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <form action="{{ route('dokumen-mutu.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul/nomor..." class="rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
            <select name="kategori" class="rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="all" {{ request('kategori') == 'all' ? 'selected' : '' }}>Semua Kategori</option>
                <option value="kebijakan" {{ request('kategori') == 'kebijakan' ? 'selected' : '' }}>Kebijakan</option>
                <option value="manual" {{ request('kategori') == 'manual' ? 'selected' : '' }}>Manual</option>
                <option value="standar" {{ request('kategori') == 'standar' ? 'selected' : '' }}>Standar</option>
                <option value="formulir" {{ request('kategori') == 'formulir' ? 'selected' : '' }}>Formulir</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-lg transition-colors">Filter</button>
        </form>

        @can('manage-dokumen')
        <a href="{{ route('dokumen-mutu.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
            <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Dokumen
        </a>
        @endcan
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-sm font-medium text-slate-500">
                        <th class="py-3 px-4 w-12">No</th>
                        <th class="py-3 px-4">Judul</th>
                        <th class="py-3 px-4">Nomor Dokumen</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Tahun</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700 divide-y divide-slate-100">
                    @forelse($dokumens as $index => $d)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-4">{{ $dokumens->firstItem() + $index }}</td>
                            <td class="py-3 px-4 font-medium text-slate-900">{{ $d->judul }}</td>
                            <td class="py-3 px-4">{{ $d->nomor_dokumen ?? '-' }}</td>
                            <td class="py-3 px-4">
                                @if(method_exists($d->kategori, 'badgeColor') && method_exists($d->kategori, 'label'))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $d->kategori->badgeColor() }}">{{ $d->kategori->label() }}</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">{{ $d->kategori->value ?? $d->kategori }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">{{ $d->tahun_berlaku }}</td>
                            <td class="py-3 px-4">
                                @if($d->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right space-x-2">
                                @if(method_exists($d, 'hasFile') ? $d->hasFile() : $d->file_path)
                                <a href="{{ route('dokumen-mutu.download', $d->id) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900" title="Download">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </a>
                                @endif
                                
                                @can('manage-dokumen')
                                <a href="{{ route('dokumen-mutu.edit', $d->id) }}" class="inline-flex items-center text-amber-600 hover:text-amber-900" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('dokumen-mutu.destroy', $d->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center text-red-600 hover:text-red-900" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 px-4 text-center">
                                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="text-slate-500 font-medium">Tidak ada dokumen mutu yang ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($dokumens->hasPages())
        <div class="px-4 py-3 border-t border-slate-200 bg-slate-50">
            {{ $dokumens->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
