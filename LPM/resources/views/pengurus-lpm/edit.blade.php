<x-app-layout>
    <x-slot name="header">
        <nav class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('pengurus-lpm.index') }}" class="hover:text-slate-700">Kepengurusan LPM</a>
            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="font-medium text-slate-800">Edit Pengurus</span>
        </nav>
    </x-slot>

    <div class="max-w-xl mx-auto mt-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-lg font-semibold text-slate-900">Edit Data Pengurus LPM</h3>
                <p class="text-slate-500 text-xs mt-1">Ubah informasi pengurus organisasi di bawah ini.</p>
            </div>
            
            <form action="{{ route('pengurus-lpm.update', $pengurusLpm->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="jabatan" class="block text-sm font-medium {{ $pengurusLpm->is_permanent ? 'text-slate-500' : 'text-slate-700' }} mb-1">
                        Jabatan 
                        @if($pengurusLpm->is_permanent)
                            <span class="text-xs text-slate-400 font-normal">(Jabatan Ketua tidak dapat diubah)</span>
                        @endif
                    </label>
                    <input type="text" name="jabatan" id="jabatan" 
                        value="{{ old('jabatan', $pengurusLpm->jabatan) }}" 
                        {{ $pengurusLpm->is_permanent ? 'readonly' : 'required' }}
                        class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm {{ $pengurusLpm->is_permanent ? 'bg-gray-100 cursor-not-allowed text-gray-500' : '' }}">
                    @error('jabatan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nama_lengkap" class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap (Beserta Gelar)</label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $pengurusLpm->nama_lengkap) }}" required 
                        placeholder="Misal: Dr. John Doe, M.Th."
                        class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('nama_lengkap')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $pengurusLpm->email) }}" 
                        placeholder="Misal: nama@domain.com"
                        class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="foto" class="block text-sm font-medium text-slate-700 mb-1">Foto Profil</label>
                    
                    @if($pengurusLpm->foto)
                        <div class="flex items-center gap-3 mb-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <img src="{{ asset('storage/' . $pengurusLpm->foto) }}" alt="Preview Foto" class="w-12 h-12 rounded-full object-cover shadow-sm border border-slate-300">
                            <div>
                                <span class="text-xs font-semibold text-slate-700 block">Foto Saat Ini</span>
                                <span class="text-xs text-slate-500 block">Akan digantikan jika Anda mengunggah berkas baru.</span>
                                <label class="inline-flex items-center mt-2 cursor-pointer">
                                    <input type="checkbox" name="hapus_foto" value="1" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                                    <span class="ml-2 text-sm text-red-600 font-medium">Hapus Foto Profil Saat Ini</span>
                                </label>
                            </div>
                        </div>
                    @endif

                    <input type="file" name="foto" id="foto" accept="image/*"
                        class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="text-xs text-slate-400 mt-1">Format: JPEG, PNG, JPG. Maksimal 2MB.</p>
                    @error('foto')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('pengurus-lpm.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>


