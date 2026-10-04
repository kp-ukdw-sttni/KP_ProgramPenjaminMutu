@extends('layouts.app')

@section('title', 'Riwayat Capaian Mutu')

@section('breadcrumb')
<nav class="flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('dashboard') }}" class="hover:text-slate-800">Dashboard</a>
    <span>/</span>
    <a href="{{ route('summary.index') }}" class="hover:text-slate-800">Summary Capaian Mutu</a>
    <span>/</span>
    <span class="font-semibold text-slate-800">{{ $standarMutu->kode_standar }}</span>
</nav>
@endsection

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-800">{{ $standarMutu->kode_standar }} - {{ $standarMutu->nama_standar }}</h2>
    <p class="text-slate-600 mt-1">Riwayat Capaian Mutu & Tindak Lanjut</p>
</div>

<div class="space-y-8">
    @forelse($evaluasis as $prodi => $riwayat)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-800">{{ $prodi }}</h3>
                <span class="text-xs font-medium bg-slate-200 text-slate-600 px-2.5 py-1 rounded-full">{{ $riwayat->count() }} Periode</span>
            </div>
            <div class="divide-y divide-slate-100">
                @foreach($riwayat as $eval)
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ $eval->tahun_akademik }} - Semester {{ $eval->semester->value }}
                            </span>
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $eval->status->value === 'audited' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($eval->status->value) }}
                            </span>
                        </div>
                        <div class="text-right flex items-center gap-6">
                            <div>
                                <div class="text-xs text-slate-500 uppercase tracking-wider font-semibold">Skor Akhir</div>
                                <div class="text-2xl font-bold {{ $eval->nilai ? 'text-indigo-600' : 'text-slate-400' }}">
                                    {{ $eval->nilai ?? '-' }}
                                </div>
                            </div>
                            <a href="{{ route('audit-internal.show', $eval) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Detail Monev &rarr;
                            </a>
                        </div>
                    </div>
                    
                    <div class="mb-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                            <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Target Capaian (Sesuai Standar)</span>
                            <div class="text-sm text-slate-700 prose prose-sm max-w-none">{!! $standarMutu->target_capaian ?? '-' !!}</div>
                        </div>
                        <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4">
                            <span class="block text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-2">Capaian Aktual (Evaluasi Diri)</span>
                            <div class="text-sm text-slate-800 prose prose-sm max-w-none">{!! $eval->capaian_aktual ?? '-' !!}</div>
                        </div>
                    </div>

                    <div class="bg-slate-50 rounded-xl p-5 border border-slate-100">
                        <h4 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Catatan Temuan Auditor (CAPA)
                        </h4>
                        
                        @if($eval->auditMutus->count() > 0)
                            <div class="space-y-3">
                                @foreach($eval->auditMutus as $temuan)
                                <div class="bg-white border border-slate-200 rounded-lg p-4 transition-all hover:shadow-sm">
                                    <div class="flex justify-between items-start gap-4">
                                        <div class="flex-1">
                                            <div class="text-sm text-slate-800 font-medium prose prose-sm max-w-none">{!! $temuan->deskripsi_temuan !!}</div>
                                            
                                            @if($temuan->akar_masalah)
                                                <div class="mt-3 text-slate-600 bg-red-50 p-3 rounded-md text-sm border border-red-100">
                                                    <span class="font-bold text-red-800 block mb-1">Akar Masalah:</span> 
                                                    <div class="prose prose-sm max-w-none text-red-700">{!! $temuan->akar_masalah !!}</div>
                                                </div>
                                            @endif

                                            @if($temuan->tindak_lanjut)
                                                <div class="mt-2 text-slate-700 bg-emerald-50 p-3 rounded-md text-sm border border-emerald-100">
                                                    <span class="font-bold text-emerald-800 block mb-1">Tindak Lanjut:</span> 
                                                    <div class="prose prose-sm max-w-none text-emerald-700">{!! $temuan->tindak_lanjut !!}</div>
                                                </div>
                                            @endif
                                        </div>
                                        <span class="inline-flex shrink-0 px-2.5 py-1 text-xs font-semibold rounded-full {{ $temuan->status_audit?->value === 'closed' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200' }}">
                                            {{ ucfirst($temuan->status_audit?->value) }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 bg-white rounded-lg border border-slate-200 border-dashed">
                                <p class="text-sm text-slate-500 italic">Tidak ada temuan audit yang dicatat pada periode ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-16 text-center">
            <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-slate-900">Belum ada data</h3>
            <p class="mt-1 text-sm text-slate-500">Belum ada riwayat capaian evaluasi yang dikumpulkan untuk standar mutu ini.</p>
        </div>
    @endforelse
</div>
@endsection



