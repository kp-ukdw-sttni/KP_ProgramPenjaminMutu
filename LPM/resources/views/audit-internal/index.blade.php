@extends('layouts.app')

@section('title', 'Audit Internal (AMI)')

@section('breadcrumb')
<nav class="flex items-center gap-2 text-sm text-slate-500">
    <span class="font-medium text-slate-800">Audit Internal</span>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h2 class="text-xl font-bold text-slate-800">Daftar Audit Internal</h2>
        <form action="{{ route('audit-internal.index') }}" method="GET" class="w-full sm:w-auto">
            <select name="status" onchange="this.form.submit()" class="w-full sm:w-64 rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="">Semua Status Evaluasi</option>
                @foreach(\App\Enums\StatusEvaluasi::cases() as $status)
                    <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left whitespace-nowrap">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Prodi</th>
                        <th scope="col" class="px-6 py-3">Standar</th>
                        <th scope="col" class="px-6 py-3">Tahun Akademik</th>
                        <th scope="col" class="px-6 py-3">Status Evaluasi</th>
                        <th scope="col" class="px-6 py-3">Temuan Audit</th>
                        <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($evaluasis as $evaluasi)
                        @php
                            $totalTemuan = $evaluasi->auditMutus->count();
                            $open = $evaluasi->auditMutus->where('status', \App\Enums\StatusTemuan::OPEN)->count();
                            $inProgress = $evaluasi->auditMutus->where('status', \App\Enums\StatusTemuan::IN_PROGRESS)->count();
                            $closed = $evaluasi->auditMutus->where('status', \App\Enums\StatusTemuan::CLOSED)->count();
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">{{ $loop->iteration + $evaluasis->firstItem() - 1 }}</td>
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $evaluasi->programStudi->nama_prodi }}</td>
                            <td class="px-6 py-4">
                                <div>{{ $evaluasi->standarMutu->kode }}</div>
                                <div class="text-xs text-slate-500 truncate max-w-xs">{{ $evaluasi->standarMutu->nama }}</div>
                            </td>
                            <td class="px-6 py-4">{{ $evaluasi->tahun_akademik }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $evaluasi->status->badgeColor() }}">
                                    {{ $evaluasi->status->label() }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($totalTemuan > 0)
                                    <div class="font-medium mb-1">{{ $totalTemuan }} Temuan</div>
                                    <div class="flex gap-1 text-xs">
                                        @if($open > 0)<span class="text-red-600 bg-red-50 px-1.5 rounded">{{ $open }} Open</span>@endif
                                        @if($inProgress > 0)<span class="text-amber-600 bg-amber-50 px-1.5 rounded">{{ $inProgress }} In Progress</span>@endif
                                        @if($closed > 0)<span class="text-green-600 bg-green-50 px-1.5 rounded">{{ $closed }} Closed</span>@endif
                                    </div>
                                @else
                                    <span class="text-slate-400 italic">Belum ada</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('audit-internal.show', $evaluasi->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    View Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                                Belum ada data evaluasi untuk diaudit.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($evaluasis->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $evaluasis->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
