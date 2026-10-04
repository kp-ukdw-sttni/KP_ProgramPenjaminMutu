@extends('layouts.app')

@section('title', 'Summary Capaian Mutu')

@section('breadcrumb')
<nav class="flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('dashboard') }}" class="hover:text-slate-800">Dashboard</a>
    <span>/</span>
    <span class="font-semibold text-slate-800">Summary Capaian Mutu</span>
</nav>
@endsection

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-200">
        <h3 class="text-lg font-semibold text-slate-800">Daftar Standar Mutu</h3>
        <p class="text-sm text-slate-500 mt-1">Pilih standar mutu untuk melihat riwayat capaian evaluasi dan temuan per program studi.</p>
    </div>
    <div class="divide-y divide-slate-100">
        @foreach($standars as $standar)
        <div class="p-6 hover:bg-slate-50 transition-colors flex items-center justify-between">
            <div>
                <h4 class="text-base font-semibold text-slate-800">{{ $standar->kode_standar }} - {{ $standar->nama_standar }}</h4>
                <p class="text-sm text-slate-500 mt-1">Target Capaian: {{ $standar->target_capaian }}</p>
            </div>
            <a href="{{ route('summary.show', $standar) }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-all">
                Lihat Riwayat &rarr;
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection
