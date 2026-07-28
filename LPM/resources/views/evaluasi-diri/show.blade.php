@extends('layouts.app')

@section('title', 'Detail Evaluasi Diri')

@section('breadcrumb')
<nav class="flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('dashboard') }}" class="hover:text-slate-800">Dashboard</a>
    <span>/</span>
    <a href="{{ route('evaluasi-diri.index') }}" class="hover:text-slate-800">Evaluasi Diri</a>
    <span>/</span>
    <span class="font-semibold text-slate-800">Detail</span>
</nav>
@endsection

@section('content')
<div class="flex flex-col gap-6 max-w-5xl">
    <!-- Evaluasi Detail Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h3 class="text-lg font-medium leading-6 text-slate-900">Informasi Evaluasi Diri</h3>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $evaluasi->status->badgeColor() }}">
                    {{ $evaluasi->status->label() }}
                </span>
                
                @if($evaluasi->status->value === 'draft' && auth()->user()->can('create-evaluasi'))
                    <form action="{{ route('evaluasi-diri.submit', $evaluasi) }}" method="POST" onsubmit="return confirm('Yakin ingin submit evaluasi ini?');">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                            Submit Evaluasi
                        </button>
                    </form>
                @endif
                
                @if($evaluasi->bukti_fisik)
                    <a href="{{ Storage::url($evaluasi->bukti_fisik) }}" download class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 rounded-md shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50">
                        Download Bukti
                    </a>
                @endif
            </div>
        </div>
        <div class="px-6 py-5">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-slate-500">Program Studi</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $evaluasi->programStudi->nama_prodi ?? '-' }} ({{ $evaluasi->programStudi->fakultas->nama_fakultas ?? '-' }})</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-slate-500">Standar Mutu</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $evaluasi->standarMutu->kode_standar ?? '-' }} - {{ $evaluasi->standarMutu->nama_standar ?? '-' }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-slate-500">Tahun Akademik / Semester</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $evaluasi->tahun_akademik }} / {{ $evaluasi->semester }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-slate-500">Target Capaian</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $evaluasi->standarMutu->target_capaian ?? '-' }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-slate-500">Capaian Aktual</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $evaluasi->capaian_aktual }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-slate-500">Deskripsi Ketercapaian</dt>
                    <dd class="mt-1 text-sm text-slate-900 whitespace-pre-wrap">{{ $evaluasi->deskripsi_ketercapaian }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Temuan Audit Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-lg font-medium leading-6 text-slate-900">Temuan Audit</h3>
            @if(in_array($evaluasi->status->value, ['submitted', 'audited']) && auth()->user()->can('create-audit'))
                <a href="{{ route('audit-mutu.create', ['evaluasi_id' => $evaluasi->id]) }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    + Tambah Temuan
                </a>
            @endif
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Kategori</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Deskripsi Temuan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Auditor</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($evaluasi->auditMutus ?? [] as $audit)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $audit->kategori->badgeColor() }}">
                                {{ $audit->kategori->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-900 max-w-md truncate">
                            {{ $audit->deskripsi_temuan }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            {{ $audit->auditor->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $audit->status->badgeColor() }}">
                                {{ $audit->status->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('audit-mutu.show', $audit) }}" class="text-indigo-600 hover:text-indigo-900">Detail</a>
                            
                            @if(in_array($audit->status->value, ['open', 'in_progress']) && auth()->user()->can('respond-audit'))
                                <span class="text-slate-300 mx-2">|</span>
                                <a href="{{ route('audit-mutu.show', $audit) }}#respond" class="text-blue-600 hover:text-blue-900">Respond</a>
                            @endif
                            
                            @if($audit->status->value === 'in_progress' && auth()->user()->can('close-audit'))
                                <span class="text-slate-300 mx-2">|</span>
                                <form action="{{ route('audit-mutu.close', $audit) }}" method="POST" class="inline-block" onsubmit="return confirm('Tutup temuan ini?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-green-600 hover:text-green-900">Close</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 whitespace-nowrap text-sm text-center text-slate-500">
                            Belum ada temuan audit.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
