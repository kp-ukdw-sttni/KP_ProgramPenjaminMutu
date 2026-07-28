@extends('layouts.app')

@section('title', 'Tambah Fakultas')

@section('breadcrumb')
<nav class="flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('fakultas.index') }}" class="hover:text-indigo-600">Fakultas</a>
    <span>/</span>
    <span class="font-medium text-slate-800">Tambah</span>
</nav>
@endsection

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('fakultas.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div>
                <label for="nama_fakultas" class="block text-sm font-medium text-slate-700 mb-1">Nama Fakultas</label>
                <input type="text" name="nama_fakultas" id="nama_fakultas" value="{{ old('nama_fakultas') }}" required class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('nama_fakultas')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="singkatan" class="block text-sm font-medium text-slate-700 mb-1">Singkatan <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <input type="text" name="singkatan" id="singkatan" value="{{ old('singkatan') }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('singkatan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('fakultas.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
