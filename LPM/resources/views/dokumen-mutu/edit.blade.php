@extends('layouts.app')

@section('title', 'Edit Dokumen Mutu')

@section('breadcrumb')
<nav class="flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition-colors">Dashboard</a>
    <span>/</span>
    <a href="{{ route('dokumen-mutu.index') }}" class="hover:text-indigo-600 transition-colors">Dokumen Mutu</a>
    <span>/</span>
    <span class="font-semibold text-slate-800">Edit</span>
</nav>
@endsection

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Card Component Container -->
    <div class="bg-white rounded-lg shadow border border-gray-100 p-6 md:p-8">
        <div class="mb-6 border-b border-gray-100 pb-4">
            <h2 class="text-xl font-bold text-gray-800">Edit Dokumen Mutu</h2>
            <p class="text-sm text-gray-500">Perbarui data dokumen penjaminan mutu di bawah ini.</p>
        </div>

        <form action="{{ route('dokumen-mutu.update', $dokumen->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Side-by-Side balanced layout -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Left Column: Form Fields (7 columns) -->
                <div class="lg:col-span-7 space-y-5">
                    <!-- Judul -->
                    <div>
                        <label for="judul" class="block text-sm font-semibold text-gray-700 mb-1">Judul Dokumen <span class="text-red-500">*</span></label>
                        <input type="text" id="judul" name="judul" value="{{ old('judul', $dokumen->judul) }}" required placeholder="Contoh: Dokumen Standar Kompetensi Lulusan" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5 @error('judul') border-red-300 focus:ring-red-500 focus:ring-red-500 @enderror">
                        @error('judul')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor Dokumen -->
                    <div>
                        <label for="nomor_dokumen" class="block text-sm font-semibold text-gray-700 mb-1">Nomor Dokumen</label>
                        <input type="text" id="nomor_dokumen" name="nomor_dokumen" value="{{ old('nomor_dokumen', $dokumen->nomor_dokumen) }}" placeholder="Contoh: SK/001/LPM/2026" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5 @error('nomor_dokumen') border-red-300 focus:ring-red-500 focus:ring-red-500 @enderror">
                        @error('nomor_dokumen')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sub-grid for Category, Year, Semester -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Kategori -->
                        <div>
                            <label for="kategori" class="block text-sm font-semibold text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                            <select id="kategori" name="kategori" required class="w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5 @error('kategori') border-red-300 focus:ring-red-500 focus:ring-red-500 @enderror">
                                <option value="">Pilih Kategori</option>
                                <option value="kebijakan" {{ old('kategori', $dokumen->kategori->value) == 'kebijakan' ? 'selected' : '' }}>Kebijakan</option>
                                <option value="manual" {{ old('kategori', $dokumen->kategori->value) == 'manual' ? 'selected' : '' }}>Manual</option>
                                <option value="standar" {{ old('kategori', $dokumen->kategori->value) == 'standar' ? 'selected' : '' }}>Standar</option>
                                <option value="formulir" {{ old('kategori', $dokumen->kategori->value) == 'formulir' ? 'selected' : '' }}>Formulir</option>
                            </select>
                            @error('kategori')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tahun Berlaku -->
                        <div>
                            <label for="tahun_berlaku" class="block text-sm font-semibold text-gray-700 mb-1">Tahun Berlaku <span class="text-red-500">*</span></label>
                            <input type="number" id="tahun_berlaku" name="tahun_berlaku" value="{{ old('tahun_berlaku', $dokumen->tahun_berlaku) }}" required min="2000" max="2100" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5 @error('tahun_berlaku') border-red-300 focus:ring-red-500 focus:ring-red-500 @enderror">
                            @error('tahun_berlaku')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Semester -->
                        <div>
                            <label for="semester" class="block text-sm font-semibold text-gray-700 mb-1">Semester <span class="text-red-500">*</span></label>
                            <select id="semester" name="semester" required class="w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5 @error('semester') border-red-300 focus:ring-red-500 focus:ring-red-500 @enderror">
                                <option value="">Pilih Semester</option>
                                @foreach(\App\Enums\Semester::cases() as $sem)
                                    <option value="{{ $sem->value }}" {{ old('semester', $dokumen->semester->value) == $sem->value ? 'selected' : '' }}>
                                        {{ $sem->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('semester')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Status Aktif -->
                    <div class="flex items-center pt-2">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $dokumen->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="is_active" class="ml-2 block text-sm font-medium text-gray-700">Dokumen Aktif</label>
                    </div>
                    @error('is_active')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Right Column: File Upload & Previews (5 columns) -->
                <div class="lg:col-span-5 flex flex-col">
                    <label for="file" class="block text-sm font-semibold text-gray-700 mb-1">File Dokumen</label>
                    
                    <div class="flex-1 flex flex-col justify-center">
                        <div id="upload-container" class="relative w-full">
                            
                            <!-- Existing File Card (Distinct, shown by default if file exists) -->
                            @if($dokumen->file_path)
                                <div id="existing-file-card" class="border border-blue-200 bg-blue-50/30 rounded-lg p-5 text-center space-y-4 shadow-sm">
                                    <div class="flex items-center justify-center gap-3 bg-white p-3 rounded-lg border border-blue-100 shadow-sm max-w-sm mx-auto">
                                        <span class="text-2xl">📁</span>
                                        <div class="text-left min-w-0 flex-1">
                                            <p class="text-sm font-semibold text-slate-800 truncate">{{ basename($dokumen->file_path) }}</p>
                                            <p class="text-xs text-slate-500">Berkas saat ini</p>
                                        </div>
                                    </div>

                                    <!-- Existing File Inline Previews -->
                                    @php
                                        $ext = pathinfo($dokumen->file_path, PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png']);
                                        $isPdf = strtolower($ext) === 'pdf';
                                    @endphp
                                    @if($isImage)
                                        <div class="p-1 bg-white border border-slate-200 rounded max-w-xs mx-auto shadow-sm">
                                            <img src="{{ route('dokumen-mutu.download', $dokumen) }}" alt="Pratinjau Berkas" class="max-h-36 rounded object-contain mx-auto">
                                        </div>
                                    @elseif($isPdf)
                                        <div class="p-1 bg-white border border-slate-200 rounded shadow-sm">
                                            <iframe src="{{ route('dokumen-mutu.download', $dokumen) }}" class="w-full h-48 rounded" frameborder="0"></iframe>
                                        </div>
                                    @endif

                                    <!-- Change File Trigger Button -->
                                    <div class="pt-1">
                                        <label for="file" class="cursor-pointer inline-flex items-center justify-center px-4 py-2 border border-slate-300 rounded-md shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition-all duration-150">
                                            Ganti Berkas
                                        </label>
                                    </div>
                                </div>
                            @endif

                            <!-- Default Dropzone Area (Hidden if existing file is present, unless they hit Ganti Berkas) -->
                            <div id="dropzone-area" class="{{ $dokumen->file_path ? 'hidden' : '' }} flex flex-col justify-center px-6 pt-10 pb-10 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-500 transition-all duration-200 bg-gray-50/50 text-center @error('file') border-red-300 bg-red-50/50 @enderror">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center mt-3">
                                    <label for="file" class="relative cursor-pointer bg-white rounded-md font-semibold text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500 px-3 py-1.5 border border-gray-200 shadow-sm transition-all duration-150">
                                        <span>Pilih Berkas Baru</span>
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">PDF, DOC, DOCX (Maksimal 5MB)</p>
                            </div>

                            <!-- Hidden Input File -->
                            <input id="file" name="file" type="file" class="sr-only" accept=".pdf,.doc,.docx" onchange="previewNewFile(this)">

                            <!-- Integrated Selected File Preview Card (Hidden by default, shown by JS) -->
                            <div id="new-file-preview-card" class="hidden border-2 border-green-300 bg-green-50/30 rounded-lg p-6 text-center space-y-4 shadow-sm transition-all duration-200">
                                <div class="flex items-center justify-between gap-3 bg-white p-3 rounded-lg border border-green-100 shadow-sm max-w-sm mx-auto">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="text-xl">📄</span>
                                        <div class="text-left min-w-0">
                                            <p id="new-file-name" class="text-sm font-semibold text-gray-800 truncate max-w-[180px]"></p>
                                            <p id="new-file-size" class="text-xs text-gray-500"></p>
                                        </div>
                                    </div>
                                    <button type="button" onclick="resetFileSelection()" class="text-red-500 hover:text-red-700 text-xs font-semibold px-2 py-1 hover:bg-red-50 rounded transition-colors">Batal</button>
                                </div>

                                <!-- Image Preview Container -->
                                <div id="new-file-image-preview-container" class="hidden p-1 bg-white border border-gray-200 rounded max-w-xs mx-auto shadow-sm">
                                    <img id="new-file-image-preview" src="" alt="Pratinjau" class="max-h-40 rounded object-contain mx-auto">
                                </div>

                                <!-- PDF Preview Container -->
                                <div id="new-file-pdf-preview-container" class="hidden p-1 bg-white border border-gray-200 rounded shadow-sm">
                                    <div class="flex items-center justify-center p-4 bg-slate-50 text-slate-600 rounded text-xs gap-1.5 font-medium">
                                        <span>PDF terpilih baru. Pratinjau akan ditampilkan setelah diperbarui.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @error('file')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 mt-6">
                <a href="{{ route('dokumen-mutu.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200 shadow-sm">Batal</a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm">Perbarui Dokumen</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewNewFile(input) {
    const dropzone = document.getElementById('dropzone-area');
    const existingCard = document.getElementById('existing-file-card');
    const newCard = document.getElementById('new-file-preview-card');
    const nameEl = document.getElementById('new-file-name');
    const sizeEl = document.getElementById('new-file-size');
    const imgContainer = document.getElementById('new-file-image-preview-container');
    const imgPreview = document.getElementById('new-file-image-preview');
    const pdfContainer = document.getElementById('new-file-pdf-preview-container');

    if (input.files && input.files[0]) {
        const file = input.files[0];
        nameEl.innerText = file.name;
        sizeEl.innerText = (file.size / (1024 * 1024)).toFixed(2) + ' MB';

        // Hide default dropzone and existing card
        if (dropzone) dropzone.classList.add('hidden');
        if (existingCard) existingCard.classList.add('hidden');

        // Show preview card
        newCard.classList.remove('hidden');

        // Render preview if image
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imgPreview.src = e.target.result;
                imgContainer.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
            pdfContainer.classList.add('hidden');
        } else if (file.type === 'application/pdf') {
            imgContainer.classList.add('hidden');
            imgPreview.src = '';
            pdfContainer.classList.remove('hidden');
        } else {
            imgContainer.classList.add('hidden');
            imgPreview.src = '';
            pdfContainer.classList.add('hidden');
        }
    }
}

function resetFileSelection() {
    const input = document.getElementById('file');
    const dropzone = document.getElementById('dropzone-area');
    const existingCard = document.getElementById('existing-file-card');
    const newCard = document.getElementById('new-file-preview-card');

    input.value = ''; // Reset selection
    newCard.classList.add('hidden');

    if (existingCard) {
        existingCard.classList.remove('hidden');
    } else if (dropzone) {
        dropzone.classList.remove('hidden');
    }
}
</script>
@endpush
