@extends('layouts.app')

@section('title', 'Edit Program Studi')

@section('breadcrumb')
<nav class="flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('program-studi.index') }}" class="hover:text-indigo-600">Program Studi</a>
    <span>/</span>
    <span class="font-medium text-slate-800">Edit</span>
</nav>
@endsection

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('program-studi.update', $programStudi->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="nama_prodi" class="block text-sm font-medium text-slate-700 mb-1">Nama Program Studi</label>
                <input type="text" name="nama_prodi" id="nama_prodi" value="{{ old('nama_prodi', $programStudi->nama_prodi) }}" required class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('nama_prodi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="fakultas_id" class="block text-sm font-medium text-slate-700 mb-1">Fakultas <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <select name="fakultas_id" id="fakultas_id" class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">-- Pilih Fakultas --</option>
                    @foreach($fakultas as $fak)
                        <option value="{{ $fak->id }}" {{ old('fakultas_id', $programStudi->fakultas_id) == $fak->id ? 'selected' : '' }}>
                            {{ $fak->nama_fakultas }}
                        </option>
                    @endforeach
                </select>
                @error('fakultas_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="kepala_prodi" class="block text-sm font-medium text-slate-700 mb-1">Kepala Prodi <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <input type="text" name="kepala_prodi" id="kepala_prodi" value="{{ old('kepala_prodi', $programStudi->kepala_prodi) }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('kepala_prodi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('program-studi.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
