@extends('layouts.app')

@section('title', 'Balas Temuan (CAPA)')

@section('breadcrumb')
<nav class="flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('audit-internal.index') }}" class="hover:text-indigo-600">Audit Internal</a>
    <span>/</span>
    <a href="{{ route('audit-internal.show', $finding->evaluasi_diri_id) }}" class="hover:text-indigo-600">Detail</a>
    <span>/</span>
    <span class="font-medium text-slate-800">Tindak Lanjut</span>
</nav>
@endsection

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Read-only finding details -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-slate-800">Detail Temuan</h2>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $finding->kategori->badgeColor() }}">
                {{ $finding->kategori->label() }}
            </span>
        </div>
        
        <div class="space-y-4 text-sm">
            <div>
                <span class="block font-medium text-slate-500 mb-1">Auditor</span>
                <div class="text-slate-900">{{ $finding->auditor->name }}</div>
            </div>
            <div>
                <span class="block font-medium text-slate-500 mb-1">Deskripsi Temuan</span>
                <div class="text-slate-900 bg-slate-50 p-3 rounded border border-slate-100 whitespace-pre-line">{{ $finding->deskripsi_temuan }}</div>
            </div>
            @if($finding->rekomendasi)
            <div>
                <span class="block font-medium text-slate-500 mb-1">Rekomendasi</span>
                <div class="text-slate-900 bg-slate-50 p-3 rounded border border-slate-100 whitespace-pre-line">{{ $finding->rekomendasi }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Form for CAPA -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('audit-internal.update-respond', $finding->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PATCH')

            <div>
                <label for="rencana_tindak_lanjut" class="block text-sm font-medium text-slate-700 mb-1">
                    Rencana Tindak Lanjut (CAPA)
                </label>
                <textarea name="rencana_tindak_lanjut" id="rencana_tindak_lanjut" rows="6" required minlength="20" class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Jelaskan tindakan koreksi dan pencegahan yang akan dilakukan...">{{ old('rencana_tindak_lanjut', $finding->rencana_tindak_lanjut) }}</textarea>
                <p class="mt-1 text-xs text-slate-500">Minimal 20 karakter.</p>
                @error('rencana_tindak_lanjut')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('audit-internal.show', $finding->evaluasi_diri_id) }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Kirim Tindak Lanjut
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
