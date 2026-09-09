@extends('layouts.app')

@section('title', 'Tambah Temuan Audit')

@section('breadcrumb')
<nav class="flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('audit-internal.index') }}" class="hover:text-indigo-600">Daftar Temuan</a>
    <span>/</span>
    <a href="{{ route('audit-internal.show', $evaluasi->id) }}" class="hover:text-indigo-600">Detail</a>
    <span>/</span>
    <span class="font-medium text-slate-800">Tambah Temuan</span>
</nav>
@endsection

@section('content')
<div class="max-w-4xl mx-auto"
     x-data="{
        rows: [{ kategori_temuan: '', deskripsi_temuan: '', rekomendasi: '' }],
        addRow() {
            this.rows.push({ kategori_temuan: '', deskripsi_temuan: '', rekomendasi: '' });
        },
        removeRow(index) {
            if (this.rows.length > 1) this.rows.splice(index, 1);
        }
     }">

    {{-- Evaluasi Info --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        <div class="bg-slate-50 p-6 border-b border-slate-200">
            <h2 class="text-lg font-bold text-slate-800">Informasi Dokumen Evaluasi</h2>
            <div class="mt-2 text-sm text-slate-600 grid grid-cols-1 sm:grid-cols-2 gap-2">
                <div><span class="font-medium">Prodi:</span> {{ $evaluasi->programStudi->nama_prodi }}</div>
                <div><span class="font-medium">Tahun Akademik:</span> {{ $evaluasi->tahun_akademik }}</div>
                <div class="sm:col-span-2"><span class="font-medium">Standar:</span> {{ $evaluasi->standarMutu->kode_standar }} - {{ $evaluasi->standarMutu->nama_standar }}</div>
            </div>
        </div>
    </div>

    <form action="{{ route('audit-internal.temuan.store', $evaluasi) }}" method="POST">
        @csrf

        {{-- Dynamic Multi-Row Findings --}}
        <div class="space-y-4 mb-6">
            <template x-for="(row, index) in rows" :key="index">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="bg-slate-50 px-6 py-3 border-b border-slate-200 flex items-center justify-between">
                        <h3 class="font-semibold text-slate-700 text-sm" x-text="'Temuan #' + (index + 1)"></h3>
                        <button type="button"
                                @click="removeRow(index)"
                                x-show="rows.length > 1"
                                class="inline-flex items-center gap-1 text-xs text-red-600 hover:text-red-800 font-medium px-2 py-1 rounded hover:bg-red-50 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                    </div>

                    <div class="p-6 space-y-5">
                        {{-- Kategori Temuan --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Kategori Temuan <span class="text-red-500">*</span>
                            </label>
                            <select :name="'temuan[' + index + '][kategori_temuan]'"
                                    x-model="row.kategori_temuan"
                                    required
                                    class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Pilih Kategori</option>
                                @foreach(\App\Enums\KategoriTemuan::cases() as $kategori)
                                    <option value="{{ $kategori->value }}">{{ $kategori->label() }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Deskripsi Temuan --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Deskripsi Temuan <span class="text-red-500">*</span>
                            </label>
                            <textarea :name="'temuan[' + index + '][deskripsi_temuan]'"
                                      x-model="row.deskripsi_temuan"
                                      rows="4"
                                      required
                                      minlength="10"
                                      placeholder="Deskripsikan temuan yang ditemukan secara detail..."
                                      class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                        </div>

                        {{-- Rekomendasi (optional) --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Rekomendasi <span class="text-slate-400 font-normal">(Opsional)</span>
                            </label>
                            <textarea :name="'temuan[' + index + '][rekomendasi]'"
                                      x-model="row.rekomendasi"
                                      rows="2"
                                      placeholder="Tuliskan rekomendasi perbaikan jika ada..."
                                      class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- Add Row Button --}}
        <div class="mb-6">
            <button type="button"
                    @click="addRow()"
                    class="inline-flex items-center gap-2 px-4 py-2 border-2 border-dashed border-indigo-300 text-indigo-600 hover:border-indigo-500 hover:bg-indigo-50 rounded-xl text-sm font-medium transition-colors w-full justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                + Tambah Temuan
            </button>
        </div>

        {{-- Nilai (Score) for entire document --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="bg-amber-50 px-6 py-3 border-b border-amber-200">
                <h3 class="font-semibold text-amber-800 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    Nilai Dokumen Evaluasi (Opsional)
                </h3>
            </div>
            <div class="p-6">
                <label for="nilai" class="block text-sm font-medium text-slate-700 mb-2">
                    Nilai Penilaian <span class="text-slate-400 font-normal">(Dapat diisi sekarang atau nanti)</span>
                </label>
                <select name="nilai" id="nilai" class="w-full sm:w-64 rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">— Belum dinilai —</option>
                    <option value="1">1 — Sangat Kurang</option>
                    <option value="2">2 — Kurang</option>
                    <option value="3">3 — Baik</option>
                    <option value="4">4 — Sangat Baik</option>
                </select>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('audit-internal.show', $evaluasi->id) }}"
               class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Batal
            </a>
            <button type="submit"
                    class="inline-flex items-center justify-center px-6 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Semua Temuan
            </button>
        </div>
    </form>
</div>
@endsection


