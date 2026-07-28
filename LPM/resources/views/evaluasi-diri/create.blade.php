@extends('layouts.app')

@section('title', 'Tambah Evaluasi Diri')

@section('breadcrumb')
<nav class="flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('dashboard') }}" class="hover:text-slate-800">Dashboard</a>
    <span>/</span>
    <a href="{{ route('evaluasi-diri.index') }}" class="hover:text-slate-800">Evaluasi Diri</a>
    <span>/</span>
    <span class="font-semibold text-slate-800">Tambah</span>
</nav>
@endsection

@section('content')
<div class="max-w-3xl">
    <div class="mb-4 rounded-md bg-blue-50 p-4 border border-blue-200">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700 font-medium">Dokumen akan disimpan sebagai Draft. Submit setelah data lengkap.</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('evaluasi-diri.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="p-6 sm:p-8 space-y-6">
                <div>
                    <label for="standar_mutu_id" class="block text-sm font-medium text-slate-700">Standar Mutu</label>
                    <select name="standar_mutu_id" id="standar_mutu_id" class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">-- Pilih Standar Mutu --</option>
                        @foreach($standars as $standar)
                            <option value="{{ $standar->id }}" {{ old('standar_mutu_id') == $standar->id ? 'selected' : '' }}>
                                {{ $standar->kode_standar }} - {{ $standar->nama_standar }}
                            </option>
                        @endforeach
                    </select>
                    @error('standar_mutu_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="program_studi_id" class="block text-sm font-medium text-slate-700">Program Studi</label>
                    <select name="program_studi_id" id="program_studi_id" class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">-- Pilih Program Studi --</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi->id }}" {{ old('program_studi_id') == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->nama_prodi }}
                            </option>
                        @endforeach
                    </select>
                    @error('program_studi_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="tahun_akademik" class="block text-sm font-medium text-slate-700">Tahun Akademik</label>
                        <input type="text" name="tahun_akademik" id="tahun_akademik" placeholder="e.g. 2024/2025" value="{{ old('tahun_akademik') }}" class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('tahun_akademik')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="semester" class="block text-sm font-medium text-slate-700">Semester</label>
                        <select name="semester" id="semester" class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">-- Pilih Semester --</option>
                            @foreach(\App\Enums\Semester::cases() as $sem)
                                <option value="{{ $sem->value }}" {{ old('semester') == $sem->value ? 'selected' : '' }}>
                                    {{ $sem->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('semester')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="capaian_aktual" class="block text-sm font-medium text-slate-700">Capaian Aktual</label>
                    <input type="text" name="capaian_aktual" id="capaian_aktual" value="{{ old('capaian_aktual') }}" class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('capaian_aktual')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="deskripsi_ketercapaian" class="block text-sm font-medium text-slate-700">Deskripsi Ketercapaian</label>
                    <textarea name="deskripsi_ketercapaian" id="deskripsi_ketercapaian" rows="5" class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('deskripsi_ketercapaian') }}</textarea>
                    @error('deskripsi_ketercapaian')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="file_bukti_fisik" class="block text-sm font-medium text-slate-700">Bukti Fisik (Opsional)</label>
                    <input type="file" name="file_bukti_fisik" id="file_bukti_fisik" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip" class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="mt-1 text-xs text-slate-500">Maks. 10MB. Format: PDF, DOC, DOCX, JPG, PNG, ZIP.</p>
                    @error('file_bukti_fisik')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                <a href="{{ route('evaluasi-diri.index') }}" class="inline-flex justify-center py-2 px-4 border border-slate-300 shadow-sm text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Batal
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Simpan Draft
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
