@extends('layouts.app')

@section('title', 'Dashboard Analytics')

@section('breadcrumb')
<nav class="flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('dashboard') }}" class="font-bold text-slate-800 hover:text-indigo-600 transition-colors">Dashboard</a>
</nav>
@endsection

@push('head-scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endpush

@section('content')
<div class="space-y-6">
    <!-- KPI Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">
        <!-- Total Standar -->
        <div class="bg-indigo-600 rounded-2xl shadow-sm p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-indigo-100 text-sm font-medium">Total Standar</p>
                    <p class="text-3xl font-bold mt-1">{{ $kpi['total_standar'] ?? 0 }}</p>
                </div>
                <div class="p-2 bg-indigo-500 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
        </div>
        
        <!-- Total Prodi -->
        <div class="bg-blue-600 rounded-2xl shadow-sm p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Program Studi</p>
                    <p class="text-3xl font-bold mt-1">{{ $kpi['total_prodi'] ?? 0 }}</p>
                </div>
                <div class="p-2 bg-blue-500 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>
        </div>

        <!-- Total Evaluasi -->
        <div class="bg-violet-600 rounded-2xl shadow-sm p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-violet-100 text-sm font-medium">Evaluasi Diri</p>
                    <p class="text-3xl font-bold mt-1">{{ $kpi['total_evaluasi'] ?? 0 }}</p>
                </div>
                <div class="p-2 bg-violet-500 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
            </div>
        </div>

        <!-- Open Audits -->
        <div class="bg-red-500 rounded-2xl shadow-sm p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Open Audits</p>
                    <p class="text-3xl font-bold mt-1">{{ $kpi['open_audits'] ?? 0 }}</p>
                </div>
                <div class="p-2 bg-red-400 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
        </div>

        <!-- In Progress Audits -->
        <div class="bg-amber-500 rounded-2xl shadow-sm p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-amber-100 text-sm font-medium">In Progress</p>
                    <p class="text-3xl font-bold mt-1">{{ $kpi['in_progress_audits'] ?? 0 }}</p>
                </div>
                <div class="p-2 bg-amber-400 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Closed Audits -->
        <div class="bg-green-500 rounded-2xl shadow-sm p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Closed Audits</p>
                    <p class="text-3xl font-bold mt-1">{{ $kpi['closed_audits'] ?? 0 }}</p>
                </div>
                <div class="p-2 bg-green-400 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- PPEPP Cycle Donut Chart -->
        <div class="col-span-1 bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">PPEPP Status</h3>
            <div id="ppepp-chart" class="flex justify-center"></div>
        </div>
        
        <!-- Open KTS by Prodi Bar Chart -->
        <div class="col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Open KTS per Program Studi</h3>
            <div id="kts-chart"></div>
        </div>
    </div>

    <!-- Recent Findings Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">Temuan Audit Terbaru</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 text-sm font-medium text-slate-500">
                        <th class="py-3 px-4">No</th>
                        <th class="py-3 px-4">Prodi</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Deskripsi</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Auditor</th>
                        <th class="py-3 px-4">Waktu</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700">
                    @forelse($recentFindings as $index => $finding)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="py-3 px-4">{{ $index + 1 }}</td>
                            <td class="py-3 px-4">{{ $finding->evaluasiDiri->programStudi->nama_prodi ?? '-' }}</td>
                            <td class="py-3 px-4">
                                @if(method_exists($finding->kategori, 'badgeColor') && method_exists($finding->kategori, 'label'))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $finding->kategori->badgeColor() }}">{{ $finding->kategori->label() }}</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">{{ $finding->kategori->value ?? $finding->kategori }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4" title="{{ $finding->deskripsi }}">{{ Str::limit($finding->deskripsi, 60) }}</td>
                            <td class="py-3 px-4">
                                @if(method_exists($finding->status, 'badgeColor') && method_exists($finding->status, 'label'))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $finding->status->badgeColor() }}">{{ $finding->status->label() }}</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">{{ $finding->status->value ?? $finding->status }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">{{ $finding->auditor->name ?? '-' }}</td>
                            <td class="py-3 px-4">{{ $finding->created_at->translatedFormat('d M Y, H:i') }} WIB</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 px-4 text-center text-slate-500">Tidak ada temuan audit terbaru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // PPEPP Donut Chart
        const ppeppData = @json($pppepData);
        const ppeppOptions = {
            series: [ppeppData.draft || 0, ppeppData.submitted || 0, ppeppData.audited || 0],
            labels: ['Draft', 'Submitted', 'Audited'],
            chart: {
                type: 'donut',
                height: 300,
                fontFamily: 'inherit',
            },
            colors: ['#6366f1', '#f59e0b', '#10b981'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%'
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                position: 'bottom'
            }
        };
        const ppeppChart = new ApexCharts(document.querySelector("#ppepp-chart"), ppeppOptions);
        ppeppChart.render();

        // KTS Bar Chart
        const ktsData = @json($openKtsByProdi);
        const ktsOptions = {
            series: [{
                name: 'Open KTS',
                data: Object.values(ktsData)
            }],
            chart: {
                type: 'bar',
                height: 300,
                fontFamily: 'inherit',
                toolbar: {
                    show: false
                }
            },
            colors: ['#ef4444'], // red bars
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: false,
                    columnWidth: '45%'
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: Object.keys(ktsData),
                labels: {
                    style: {
                        colors: '#64748b'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#64748b'
                    }
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
            }
        };
        const ktsChart = new ApexCharts(document.querySelector("#kts-chart"), ktsOptions);
        ktsChart.render();
    });
</script>
@endpush
