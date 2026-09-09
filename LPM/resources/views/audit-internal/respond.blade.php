@extends('layouts.app')

@section('title', 'Balas Temuan — Tindak Lanjut')

@section('breadcrumb')
<nav class="flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('audit-internal.index') }}" class="hover:text-indigo-600">Daftar Temuan</a>
    <span>/</span>
    <a href="{{ route('audit-internal.show', $finding->evaluasi_diri_id) }}" class="hover:text-indigo-600">Detail</a>
    <span>/</span>
    <span class="font-medium text-slate-800">Tindak Lanjut</span>
</nav>
@endsection

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    {{-- Read-only finding details --}}
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
                <div class="text-slate-900 bg-slate-50 p-3 rounded border border-slate-100 whitespace-pre-line">{!! $finding->deskripsi_temuan !!}</div>
            </div>
            @if($finding->rekomendasi)
            <div>
                <span class="block font-medium text-slate-500 mb-1">Rekomendasi Auditor</span>
                <div class="text-slate-900 bg-slate-50 p-3 rounded border border-slate-100 whitespace-pre-line">{{ $finding->rekomendasi }}</div>
            </div>
            @endif
        </div>
    </div>

    {{-- CAPA Response Form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
            <h3 class="font-bold text-slate-800">Isian Tindak Lanjut (CAPA)</h3>
            <p class="text-xs text-slate-500 mt-1">Isi akar masalah, rencana tindak lanjut, dan upload bukti perbaikan.</p>
        </div>

        <form action="{{ route('audit-internal.respond', $finding) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PATCH')

            {{-- Akar Masalah --}}
            <div>
                <label for="akar_masalah" class="block text-sm font-medium text-slate-700 mb-1">
                    Akar Masalah <span class="text-red-500">*</span>
                </label>
                <textarea name="akar_masalah" id="akar_masalah" rows="4" required minlength="10"
                          class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                          placeholder="Jelaskan akar penyebab dari temuan ini...">{{ old('akar_masalah', $finding->akar_masalah) }}</textarea>
                <p class="mt-1 text-xs text-slate-500">Minimal 10 karakter.</p>
                @error('akar_masalah')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tindak Lanjut --}}
            <div>
                <label for="tindak_lanjut" class="block text-sm font-medium text-slate-700 mb-1">
                    Rencana Tindak Lanjut <span class="text-red-500">*</span>
                </label>
                <textarea name="tindak_lanjut" id="tindak_lanjut" rows="5" required minlength="20"
                          class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                          placeholder="Jelaskan tindakan koreksi dan pencegahan yang akan dilakukan...">{{ old('tindak_lanjut', $finding->tindak_lanjut ?? $finding->rencana_tindak_lanjut) }}</textarea>
                <p class="mt-1 text-xs text-slate-500">Minimal 20 karakter.</p>
                @error('tindak_lanjut')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Bukti Perbaikan --}}
            <div>
                <label for="bukti_perbaikan" class="block text-sm font-medium text-slate-700 mb-1">
                    Bukti Perbaikan <span class="text-slate-400 font-normal">(Opsional — PDF, JPG, PNG, DOC, DOCX, max 5MB)</span>
                </label>

                @if($finding->bukti_perbaikan)
                    <div class="mb-3 flex items-center gap-3 p-3 bg-green-50 border border-green-200 rounded-lg text-sm">
                        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-green-800">Sudah ada file bukti.</span>
                        <a href="{{ route('audit-internal.bukti.download', $finding) }}"
                           class="ml-auto text-indigo-600 hover:text-indigo-800 font-medium underline" target="_blank">
                            Lihat File
                        </a>
                    </div>
                @endif

                <input type="file" name="bukti_perbaikan" id="bukti_perbaikan"
                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                       class="block w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer border border-slate-300 rounded-md">
                @error('bukti_perbaikan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('audit-internal.show', $finding->evaluasi_diri_id) }}"
                   class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Kirim Tindak Lanjut
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


