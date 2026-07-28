@extends('layouts.app')

@section('title', 'Edit Evaluasi Diri')

@section('breadcrumb')
<nav class="flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('dashboard') }}" class="hover:text-slate-800">Dashboard</a>
    <span>/</span>
    <a href="{{ route('evaluasi-diri.index') }}" class="hover:text-slate-800">Evaluasi Diri</a>
    <span>/</span>
    <span class="font-semibold text-slate-800">Edit</span>
</nav>
@endsection

@section('content')
<div class="max-w-3xl">
    <div class="mb-4 rounded-md bg-yellow-50 p-4 border border-yellow-200">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700 font-medium">Hanya evaluasi berstatus Draft yang dapat diubah.</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('evaluasi-diri.update', $evaluasi) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="p-6 sm:p-8 space-y-6">
                <div>
                    <label for="standar_mutu_id" class="block text-sm font-medium text-slate-700">Standar Mutu</label>
                    <select name="standar_mutu_id" id="standar_mutu_id" class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">-- Pilih Standar Mutu --</option>
                        @foreach($standars as $standar)
                            <option value="{{ $standar->id }}" {{ old('standar_mutu_id', $evaluasi->standar_mutu_id) == $standar->id ? 'selected' : '' }}>
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
                            <option value="{{ $prodi->id }}" {{ old('program_studi_id', $evaluasi->program_studi_id) == $prodi->id ? 'selected' : '' }}>
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
                        <input type="text" name="tahun_akademik" id="tahun_akademik" placeholder="e.g. 2024/2025" value="{{ old('tahun_akademik', $evaluasi->tahun_akademik) }}" class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('tahun_akademik')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="semester" class="block text-sm font-medium text-slate-700">Semester</label>
                        <select name="semester" id="semester" class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">-- Pilih Semester --</option>
                            <option value="Ganjil" {{ old('semester', $evaluasi->semester) == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="Genap" {{ old('semester', $evaluasi->semester) == 'Genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                        @error('semester')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="capaian_aktual" class="block text-sm font-medium text-slate-700">Capaian Aktual</label>
                    <input type="text" name="capaian_aktual" id="capaian_aktual" value="{{ old('capaian_aktual', $evaluasi->capaian_aktual) }}" class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('capaian_aktual')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="deskripsi_ketercapaian" class="block text-sm font-medium text-slate-700">Deskripsi Ketercapaian</label>
                    <textarea name="deskripsi_ketercapaian" id="deskripsi_ketercapaian" rows="5" class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('deskripsi_ketercapaian', $evaluasi->deskripsi_ketercapaian) }}</textarea>
                    @error('deskripsi_ketercapaian')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="bukti_fisik" class="block text-sm font-medium text-slate-700">Bukti Fisik</label>
                    <input type="file" name="bukti_fisik" id="bukti_fisik" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip" class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="mt-1 text-xs text-slate-500">Maks. 10MB. Format: PDF, DOC, DOCX, JPG, PNG, ZIP. Upload file baru untuk menimpa yang lama.</p>
                    @if($evaluasi->bukti_fisik)
                        <div class="mt-2">
                            <span class="text-sm text-slate-600">File saat ini:</span>
                            <a href="{{ Storage::url($evaluasi->bukti_fisik) }}" target="_blank" class="ml-1 text-sm text-indigo-600 hover:text-indigo-900 underline">Lihat file</a>
                        </div>
                    @endif
                    @error('bukti_fisik')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                <a href="{{ route('evaluasi-diri.index') }}" class="inline-flex justify-center py-2 px-4 border border-slate-300 shadow-sm text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Batal
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
