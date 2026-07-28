@extends('layouts.app')

@section('title', 'Detail Audit Internal')

@section('breadcrumb')
<nav class="flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('audit-internal.index') }}" class="hover:text-indigo-600">Audit Internal</a>
    <span>/</span>
    <span class="font-medium text-slate-800">Detail</span>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden p-6">
        <div class="flex flex-col md:flex-row justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 mb-1">{{ $evaluasi->programStudi->nama_prodi }}</h2>
                <div class="text-slate-500 text-sm mb-4">
                    {{ $evaluasi->programStudi->fakultas?->nama_fakultas }}
                </div>
                
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 text-sm">
                    <div>
                        <dt class="font-medium text-slate-500">Standar Mutu</dt>
                        <dd class="mt-1 text-slate-900 font-medium">{{ $evaluasi->standarMutu->kode }} - {{ $evaluasi->standarMutu->nama }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-slate-500">Tahun Akademik / Semester</dt>
                        <dd class="mt-1 text-slate-900">{{ $evaluasi->tahun_akademik }} / {{ $evaluasi->semester }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="font-medium text-slate-500">Capaian Aktual</dt>
                        <dd class="mt-1 text-slate-900 bg-slate-50 p-3 rounded-lg border border-slate-100">{{ $evaluasi->capaian_aktual }}</dd>
                    </div>
                </dl>
            </div>
            
            <div class="flex flex-col items-start md:items-end gap-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $evaluasi->status->badgeColor() }}">
                    {{ $evaluasi->status->label() }}
                </span>
                
                @if($evaluasi->hasFile())
                    <a href="{{ route('evaluasi-diri.download', $evaluasi->id) }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download Bukti
                    </a>
                @endif
                <a href="{{ route('audit-internal.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium mt-2">
                    &larr; Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>

    <!-- Findings Section -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Daftar Temuan Audit</h3>
                <div class="flex gap-2 mt-1">
                    @php
                        $kts = $evaluasi->auditMutus->where('kategori', \App\Enums\KategoriTemuan::KTS)->count();
                        $ob = $evaluasi->auditMutus->where('kategori', \App\Enums\KategoriTemuan::OBSERVASI)->count();
                        $peluang = $evaluasi->auditMutus->where('kategori', \App\Enums\KategoriTemuan::PELUANG_PENINGKATAN)->count();
                    @endphp
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">{{ $kts }} KTS</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">{{ $ob }} OB</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">{{ $peluang }} Peluang</span>
                </div>
            </div>
            
            @can('create-audit')
                @if(in_array($evaluasi->status->value, [\App\Enums\StatusEvaluasi::SUBMITTED->value, \App\Enums\StatusEvaluasi::AUDITED->value]))
                    <a href="{{ route('audit-internal.create-temuan', $evaluasi->id) }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        + Tambah Temuan
                    </a>
                @endif
            @endcan
        </div>

        <div class="space-y-4">
            @forelse($evaluasi->auditMutus as $index => $temuan)
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="border-b border-slate-100 bg-slate-50 px-5 py-3 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $temuan->kategori->badgeColor() }}">
                                {{ $temuan->kategori->label() }}
                            </span>
                            <span class="text-sm font-medium text-slate-700">Temuan #{{ $index + 1 }}</span>
                            <span class="text-xs text-slate-500">{{ $temuan->created_at->format('d M Y') }} • {{ $temuan->auditor->name }}</span>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $temuan->status->badgeColor() }}">
                            {{ $temuan->status->label() }}
                        </span>
                    </div>
                    
                    <div class="p-5 space-y-4">
                        <div>
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Deskripsi Temuan</h4>
                            <div class="text-slate-800 text-sm whitespace-pre-line">{{ $temuan->deskripsi_temuan }}</div>
                        </div>
                        
                        @if($temuan->rekomendasi)
                        <div>
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Rekomendasi</h4>
                            <div class="text-slate-800 text-sm whitespace-pre-line">{{ $temuan->rekomendasi }}</div>
                        </div>
                        @endif

                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Rencana Tindak Lanjut (CAPA)</h4>
                            @if($temuan->rencana_tindak_lanjut)
                                <div class="bg-green-50 border border-green-100 p-3 rounded-lg text-sm text-green-900 whitespace-pre-line">
                                    {{ $temuan->rencana_tindak_lanjut }}
                                </div>
                            @else
                                <div class="text-sm text-slate-400 italic">Belum ada tindak lanjut.</div>
                            @endif
                        </div>

                        <div class="flex justify-end gap-2 mt-2">
                            @can('respond-audit')
                                @if($temuan->status !== \App\Enums\StatusTemuan::CLOSED && auth()->user()->program_studi_id === $evaluasi->program_studi_id)
                                    <a href="{{ route('audit-internal.respond', $temuan->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                        Balas dengan CAPA
                                    </a>
                                @endif
                            @endcan

                            @can('close-audit')
                                @if($temuan->status === \App\Enums\StatusTemuan::IN_PROGRESS)
                                    <form action="{{ route('audit-internal.close', $temuan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menutup temuan ini?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700">
                                            Tutup Temuan
                                        </button>
                                    </form>
                                @endif
                            @endcan
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 text-center">
                    <div class="text-slate-400 mb-2">
                        <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <p class="text-slate-500 font-medium">Tidak ada temuan audit.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
