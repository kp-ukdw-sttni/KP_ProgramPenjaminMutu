@extends('layouts.app')

@section('title', 'Tambah Temuan Audit')

@section('breadcrumb')
<nav class="flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('audit-internal.index') }}" class="hover:text-indigo-600">Audit Internal</a>
    <span>/</span>
    <a href="{{ route('audit-internal.show', $evaluasi->id) }}" class="hover:text-indigo-600">Detail</a>
    <span>/</span>
    <span class="font-medium text-slate-800">Tambah Temuan</span>
</nav>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-slate-50 p-6 border-b border-slate-200">
            <h2 class="text-lg font-bold text-slate-800">Informasi Evaluasi</h2>
            <div class="mt-2 text-sm text-slate-600 grid grid-cols-1 sm:grid-cols-2 gap-2">
                <div><span class="font-medium">Prodi:</span> {{ $evaluasi->programStudi->nama_prodi }}</div>
                <div><span class="font-medium">Tahun Akademik:</span> {{ $evaluasi->tahun_akademik }}</div>
                <div class="sm:col-span-2"><span class="font-medium">Standar:</span> {{ $evaluasi->standarMutu->kode }} - {{ $evaluasi->standarMutu->nama }}</div>
            </div>
        </div>

        <form action="{{ route('audit-internal.store-temuan') }}" method="POST" class="p-6 space-y-6">
            @csrf
            <input type="hidden" name="evaluasi_diri_id" value="{{ $evaluasi->id }}">

            <div>
                <label for="kategori" class="block text-sm font-medium text-slate-700 mb-1">Kategori Temuan</label>
                <select name="kategori" id="kategori" required class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Pilih Kategori</option>
                    @foreach(\App\Enums\KategoriTemuan::cases() as $kategori)
                        <option value="{{ $kategori->value }}" {{ old('kategori') == $kategori->value ? 'selected' : '' }}>
                            {{ $kategori->label() }}
                        </option>
                    @endforeach
                </select>
                @error('kategori')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="deskripsi_temuan" class="block text-sm font-medium text-slate-700 mb-1">Deskripsi Temuan</label>
                <textarea name="deskripsi_temuan" id="deskripsi_temuan" rows="5" required class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('deskripsi_temuan') }}</textarea>
                @error('deskripsi_temuan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="rekomendasi" class="block text-sm font-medium text-slate-700 mb-1">Rekomendasi <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <textarea name="rekomendasi" id="rekomendasi" rows="3" class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('rekomendasi') }}</textarea>
                @error('rekomendasi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('audit-internal.show', $evaluasi->id) }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Simpan Temuan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
