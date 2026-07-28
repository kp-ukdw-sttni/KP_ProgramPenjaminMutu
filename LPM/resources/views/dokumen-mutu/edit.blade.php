@extends('layouts.app')

@section('title', 'Edit Dokumen Mutu')

@section('breadcrumb')
<nav class="flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition-colors">Dashboard</a>
    <span>/</span>
    <a href="{{ route('dokumen-mutu.index') }}" class="hover:text-indigo-600 transition-colors">Dokumen Mutu</a>
    <span>/</span>
    <span class="font-bold text-slate-800">Edit</span>
</nav>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <form action="{{ route('dokumen-mutu.update', $dokumen->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Judul -->
            <div>
                <label for="judul" class="block text-sm font-medium text-slate-700 mb-1">Judul Dokumen <span class="text-red-500">*</span></label>
                <input type="text" id="judul" name="judul" value="{{ old('judul', $dokumen->judul) }}" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('judul') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                @error('judul')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nomor Dokumen -->
            <div>
                <label for="nomor_dokumen" class="block text-sm font-medium text-slate-700 mb-1">Nomor Dokumen</label>
                <input type="text" id="nomor_dokumen" name="nomor_dokumen" value="{{ old('nomor_dokumen', $dokumen->nomor_dokumen) }}" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('nomor_dokumen') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                @error('nomor_dokumen')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kategori -->
                <div>
                    <label for="kategori" class="block text-sm font-medium text-slate-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select id="kategori" name="kategori" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('kategori') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                        <option value="">Pilih Kategori</option>
                        <option value="kebijakan" {{ (old('kategori') ?? (is_object($dokumen->kategori) ? $dokumen->kategori->value : $dokumen->kategori)) == 'kebijakan' ? 'selected' : '' }}>Kebijakan</option>
                        <option value="manual" {{ (old('kategori') ?? (is_object($dokumen->kategori) ? $dokumen->kategori->value : $dokumen->kategori)) == 'manual' ? 'selected' : '' }}>Manual</option>
                        <option value="standar" {{ (old('kategori') ?? (is_object($dokumen->kategori) ? $dokumen->kategori->value : $dokumen->kategori)) == 'standar' ? 'selected' : '' }}>Standar</option>
                        <option value="formulir" {{ (old('kategori') ?? (is_object($dokumen->kategori) ? $dokumen->kategori->value : $dokumen->kategori)) == 'formulir' ? 'selected' : '' }}>Formulir</option>
                    </select>
                    @error('kategori')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tahun Berlaku -->
                <div>
                    <label for="tahun_berlaku" class="block text-sm font-medium text-slate-700 mb-1">Tahun Berlaku <span class="text-red-500">*</span></label>
                    <input type="number" id="tahun_berlaku" name="tahun_berlaku" value="{{ old('tahun_berlaku', $dokumen->tahun_berlaku) }}" required min="2000" max="2100" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('tahun_berlaku') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                    @error('tahun_berlaku')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status Aktif -->
            <div class="flex items-center">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $dokumen->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                <label for="is_active" class="ml-2 block text-sm text-slate-700">Dokumen Aktif</label>
            </div>
            @error('is_active')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <!-- File Upload -->
            <div>
                <label for="file" class="block text-sm font-medium text-slate-700 mb-1">File Dokumen</label>
                
                @if(method_exists($dokumen, 'hasFile') ? $dokumen->hasFile() : $dokumen->file_path)
                    <div class="mb-3 p-3 bg-blue-50 text-blue-800 text-sm rounded-lg flex items-start">
                        <svg class="w-5 h-5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>File sudah ada. Upload file baru di bawah ini hanya jika Anda ingin mengganti file yang sudah ada.</span>
                    </div>
                @endif

                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-lg hover:border-indigo-500 transition-colors bg-slate-50 @error('file') border-red-300 bg-red-50 @enderror">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-slate-600 justify-center">
                            <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 px-1">
                                <span>Upload a file</span>
                                <input id="file" name="file" type="file" class="sr-only" accept=".pdf,.doc,.docx">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-slate-500">PDF, DOC, DOCX up to 5MB</p>
                    </div>
                </div>
                @error('file')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('dokumen-mutu.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Batal</a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">Perbarui Dokumen</button>
            </div>
        </form>
    </div>
</div>
@endsection
