@extends('layouts.app')

@section('title', 'Tambah Standar Mutu')

@section('breadcrumb')
<nav class="flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('dashboard') }}" class="hover:text-slate-800">Dashboard</a>
    <span>/</span>
    <a href="{{ route('standar-mutu.index') }}" class="hover:text-slate-800">Standar Mutu</a>
    <span>/</span>
    <span class="font-semibold text-slate-800">Tambah</span>
</nav>
@endsection

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('standar-mutu.store') }}" method="POST">
            @csrf
            <div class="p-6 sm:p-8 space-y-6">
                <div>
                    <label for="kode_standar" class="block text-sm font-medium text-slate-700">Kode Standar</label>
                    <input type="text" name="kode_standar" id="kode_standar" value="{{ old('kode_standar') }}" class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('kode_standar')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nama_standar" class="block text-sm font-medium text-slate-700">Nama Standar</label>
                    <input type="text" name="nama_standar" id="nama_standar" value="{{ old('nama_standar') }}" class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('nama_standar')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-slate-700">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="indikator_kinerja" class="block text-sm font-medium text-slate-700">Indikator Kinerja</label>
                    <textarea name="indikator_kinerja" id="indikator_kinerja" rows="4" class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('indikator_kinerja') }}</textarea>
                    @error('indikator_kinerja')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="target_capaian" class="block text-sm font-medium text-slate-700">Target Capaian</label>
                    <input type="text" name="target_capaian" id="target_capaian" value="{{ old('target_capaian') }}" class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('target_capaian')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                <a href="{{ route('standar-mutu.index') }}" class="inline-flex justify-center py-2 px-4 border border-slate-300 shadow-sm text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Batal
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
