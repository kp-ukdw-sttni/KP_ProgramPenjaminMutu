{{-- REMINDER: Run "php artisan storage:link" in your terminal to enable public photo access --}}
<x-app-layout>
    <x-slot name="header">
        <style>
            .org-tree ul { padding-top: 20px; position: relative; transition: all 0.5s; display: flex; justify-content: center; }
            .org-tree li { float: left; text-align: center; list-style-type: none; position: relative; padding: 20px 5px 0 5px; transition: all 0.5s; }
            /* Connecting lines */
            .org-tree li::before, .org-tree li::after { content: ''; position: absolute; top: 0; right: 50%; border-top: 2px solid #9ca3af; width: 50%; height: 20px; }
            .org-tree li::after { right: auto; left: 50%; border-left: 2px solid #9ca3af; }
            /* Remove left/right connectors from first/last child */
            .org-tree li:only-child::after, .org-tree li:only-child::before { display: none; }
            .org-tree li:only-child { padding-top: 0; }
            .org-tree li:first-child::before, .org-tree li:last-child::after { border: 0 none; }
            .org-tree li:last-child::before { border-right: 2px solid #9ca3af; border-radius: 0 5px 0 0; }
            .org-tree li:first-child::after { border-radius: 5px 0 0 0; }
            /* Downward connectors from parents */
            .org-tree ul ul::before { content: ''; position: absolute; top: 0; left: 50%; border-left: 2px solid #9ca3af; width: 0; height: 20px; margin-left: -1px; }
        </style>
        <nav class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('dashboard') }}" class="hover:text-slate-700">Dashboard</a>
            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="font-medium text-slate-800">Kepengurusan LPM</span>
        </nav>
    </x-slot>

    <div class="space-y-6 mt-4">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Susunan Kepengurusan LPM</h2>
                <p class="text-slate-500 text-xs mt-1">Struktur organisasi Lembaga Penjaminan Mutu (LPM) STTNI</p>
            </div>
            @can('manage-kepengurusan')
                <a href="{{ route('pengurus-lpm.create') }}">
                    <x-primary-button>
                        Tambah Anggota
                    </x-primary-button>
                </a>
            @endcan
        </div>

        @if($pengurusList->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center text-slate-500">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <p class="text-slate-600 font-semibold">Tidak ada data kepengurusan</p>
                <p class="text-slate-400 text-xs mt-1">Silakan tambahkan data melalui tombol Tambah Anggota di atas.</p>
            </div>
        @else
            @php
                $ketua = $pengurusList->firstWhere('urutan', 1);
                $anggotaList = $pengurusList->filter(fn($p) => $p->urutan > 1);
            @endphp

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 lg:p-12 overflow-x-auto">
                <div class="min-w-[768px] org-tree">
                    <ul>
                        @if($ketua)
                            <li>
                                {{-- Ketua Card --}}
                                <div class="bg-white shadow-sm rounded-lg overflow-hidden text-center border border-gray-200 w-48 mx-auto flex flex-col hover:shadow-md transition-shadow">
                                    <div class="w-full h-40 bg-gray-100 flex items-center justify-center overflow-hidden">
                                        @if($ketua->foto)
                                            <img src="{{ asset('storage/' . $ketua->foto) }}" alt="{{ $ketua->nama_lengkap }}" class="w-full h-full object-cover border-none">
                                        @else
                                            <div class="w-full h-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                                                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-3 flex-1 flex flex-col justify-between">
                                        <div>
                                            <div class="text-xs text-brand-600 font-semibold uppercase tracking-wider">{{ $ketua->jabatan }}</div>
                                            <div class="text-sm font-bold text-gray-800 mt-1 line-clamp-2" title="{{ $ketua->nama_lengkap }}">{{ $ketua->nama_lengkap }}</div>
                                            @if($ketua->email)
                                                <div class="text-xs text-gray-500 mt-1">{{ $ketua->email }}</div>
                                            @endif
                                        </div>
                                        @can('manage-kepengurusan')
                                            <div class="flex justify-center gap-4 mt-3 pt-3 border-t border-slate-100">
                                                <a href="{{ route('pengurus-lpm.edit', $ketua->id) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-900 transition-colors">Edit</a>
                                                @if(!$ketua->is_permanent)
                                                    <form action="{{ route('pengurus-lpm.destroy', $ketua->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-900 transition-colors">Hapus</button>
                                                    </form>
                                                @endif
                                            </div>
                                        @endcan
                                    </div>
                                </div>

                                @if($anggotaList->isNotEmpty())
                                    <ul>
                                        @foreach($anggotaList as $anggota)
                                            <li>
                                                {{-- Anggota Card --}}
                                                <div class="bg-white shadow-sm rounded-lg overflow-hidden text-center border border-gray-200 w-48 mx-auto flex flex-col hover:shadow-md transition-shadow">
                                                    <div class="w-full h-40 bg-gray-100 flex items-center justify-center overflow-hidden">
                                                        @if($anggota->foto)
                                                            <img src="{{ asset('storage/' . $anggota->foto) }}" alt="{{ $anggota->nama_lengkap }}" class="w-full h-full object-cover border-none">
                                                        @else
                                                            <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-400">
                                                                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                                                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="p-3 flex-1 flex flex-col justify-between">
                                                        <div>
                                                            <div class="text-xs text-brand-600 font-semibold uppercase tracking-wider">{{ $anggota->jabatan }}</div>
                                                            <div class="text-sm font-bold text-gray-800 mt-1 line-clamp-2" title="{{ $anggota->nama_lengkap }}">{{ $anggota->nama_lengkap }}</div>
                                                            @if($anggota->email)
                                                                <div class="text-xs text-gray-500 mt-1">{{ $anggota->email }}</div>
                                                            @endif
                                                        </div>
                                                        @can('manage-kepengurusan')
                                                            <div class="flex justify-center gap-4 mt-3 pt-3 border-t border-slate-100">
                                                                <a href="{{ route('pengurus-lpm.edit', $anggota->id) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-900 transition-colors">Edit</a>
                                                                @if(!$anggota->is_permanent)
                                                                    <form action="{{ route('pengurus-lpm.destroy', $anggota->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini?');">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-900 transition-colors">Hapus</button>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>


