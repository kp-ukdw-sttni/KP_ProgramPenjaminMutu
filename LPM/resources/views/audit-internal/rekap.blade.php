@extends('layouts.app')

@section('title', 'Rekapitulasi Monev')

@section('breadcrumb')
<nav class="flex items-center gap-2 text-sm text-slate-500">
    <span class="font-medium text-slate-800">Rekapitulasi</span>
</nav>
@endsection

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Rekapitulasi Monitoring Evaluasi</h2>
            <p class="text-slate-500 text-sm mt-1">Ringkasan status evaluasi dan temuan per program studi</p>
        </div>
    </div>

    {{-- Summary KPI Cards --}}
    @php
        use App\Enums\StatusEvaluasi;
        use App\Enums\StatusAudit;
        $allEvaluasi = $prodis->flatMap->evaluasiDiris;
        $allTemuan   = $allEvaluasi->flatMap->auditMutus;

        $totalEvaluasi = $allEvaluasi->count();
        $totalSelesai  = $allEvaluasi->where('status', StatusEvaluasi::Audited)->count();
        $totalTemuan   = $allTemuan->count();
        $openTemuan    = $allTemuan->where('status_audit', StatusAudit::Open->value)->count();
        $closedTemuan  = $allTemuan->where('status_audit', StatusAudit::Closed->value)->count();
    @endphp

    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 text-center">
            <div class="text-2xl font-bold text-slate-800">{{ $prodis->count() }}</div>
            <div class="text-xs text-slate-500 mt-1">Program Studi</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 text-center">
            <div class="text-2xl font-bold text-indigo-700">{{ $totalEvaluasi }}</div>
            <div class="text-xs text-slate-500 mt-1">Total Evaluasi</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 text-center">
            <div class="text-2xl font-bold text-green-700">{{ $totalSelesai }}</div>
            <div class="text-xs text-slate-500 mt-1">Selesai</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 text-center">
            <div class="text-2xl font-bold text-slate-700">{{ $totalTemuan }}</div>
            <div class="text-xs text-slate-500 mt-1">Total Temuan</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 text-center">
            <div class="text-2xl font-bold text-red-700">{{ $openTemuan }}</div>
            <div class="text-xs text-slate-500 mt-1">Temuan Open</div>
        </div>
    </div>

    {{-- Per-Prodi Breakdown Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200">
            <h3 class="font-bold text-slate-800">Rincian per Program Studi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3">Program Studi</th>
                        <th class="px-4 py-3 text-center">Total Evaluasi</th>
                        <th class="px-4 py-3 text-center">Draft</th>
                        <th class="px-4 py-3 text-center">Menunggu Review</th>
                        <th class="px-4 py-3 text-center">Selesai</th>
                        <th class="px-4 py-3 text-center">Total Temuan</th>
                        <th class="px-4 py-3 text-center">Open</th>
                        <th class="px-4 py-3 text-center">In Progress</th>
                        <th class="px-4 py-3 text-center">Closed</th>
                        <th class="px-4 py-3 text-center">Avg Nilai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($prodis as $prodi)
                        @php
                            $evaluasis = $prodi->evaluasiDiris;
                            $temauns   = $evaluasis->flatMap->auditMutus;
                            $draft     = $evaluasis->where('status', StatusEvaluasi::Draft)->count();
                            $submitted = $evaluasis->where('status', StatusEvaluasi::Submitted)->count();
                            $selesai   = $evaluasis->where('status', StatusEvaluasi::Audited)->count();
                            $tOpen     = $temauns->where('status_audit', StatusAudit::Open->value)->count();
                            $tInProg   = $temauns->where('status_audit', StatusAudit::InProgress->value)->count();
                            $tClosed   = $temauns->where('status_audit', StatusAudit::Closed->value)->count();
                            $nilaiVals = $evaluasis->filter(fn($e) => $e->nilai !== null)->pluck('nilai');
                            $avgNilai  = $nilaiVals->count() > 0 ? round($nilaiVals->avg(), 1) : null;
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $prodi->nama_prodi }}</td>
                            <td class="px-4 py-4 text-center">{{ $evaluasis->count() }}</td>
                            <td class="px-4 py-4 text-center">
                                @if($draft > 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">{{ $draft }}</span>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($submitted > 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-brand-100 text-brand-700">{{ $submitted }}</span>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($selesai > 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700">{{ $selesai }}</span>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center text-slate-700 font-medium">{{ $temauns->count() ?: '—' }}</td>
                            <td class="px-4 py-4 text-center">
                                @if($tOpen > 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700">{{ $tOpen }}</span>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($tInProg > 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-700">{{ $tInProg }}</span>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($tClosed > 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700">{{ $tClosed }}</span>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($avgNilai !== null)
                                    @php
                                        $nilaiColor = match(true) {
                                            $avgNilai >= 3.5 => 'text-green-700 bg-green-100',
                                            $avgNilai >= 2.5 => 'text-brand-700 bg-brand-100',
                                            $avgNilai >= 1.5 => 'text-amber-700 bg-amber-100',
                                            default => 'text-red-700 bg-red-100',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold {{ $nilaiColor }}">{{ $avgNilai }}</span>
                                @else
                                    <span class="text-slate-300 text-xs">Belum dinilai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-8 text-center text-slate-500">
                                Belum ada data program studi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection


