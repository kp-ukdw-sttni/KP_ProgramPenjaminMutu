@extends('layouts.app')

@section('title', 'Evaluasi Diri')

@section('breadcrumb')
<nav class="flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('dashboard') }}" class="hover:text-slate-800">Dashboard</a>
    <span>/</span>
    <span class="font-semibold text-slate-800">Evaluasi Diri</span>
</nav>
@endsection

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form action="{{ route('evaluasi-diri.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2">
            <select name="status" class="border-slate-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="">Semua Status</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                <option value="audited" {{ request('status') == 'audited' ? 'selected' : '' }}>Audited</option>
            </select>
            <input type="text" name="tahun_akademik" value="{{ request('tahun_akademik') }}" placeholder="Tahun Akademik..." class="border-slate-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-slate-800 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 w-full sm:w-auto">Filter</button>
        </form>

        @can('create-evaluasi')
        <a href="{{ route('evaluasi-diri.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 whitespace-nowrap">
            + Tambah Evaluasi
        </a>
        @endcan
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Prodi</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Standar</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Thn Akd</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Semester</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Capaian Aktual</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse ($evaluasis as $index => $evaluasi)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            {{ $evaluasis->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                            {{ $evaluasi->programStudi->nama_prodi ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                            {{ $evaluasi->standarMutu->kode_standar ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                            {{ $evaluasi->tahun_akademik }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                            {{ $evaluasi->semester }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700 max-w-xs truncate">
                            {{ $evaluasi->capaian_aktual }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $evaluasi->status->badgeColor() }}">
                                {{ $evaluasi->status->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('evaluasi-diri.show', $evaluasi) }}" class="text-indigo-600 hover:text-indigo-900">Detail</a>
                                
                                @if($evaluasi->status->value === 'draft' && auth()->user()->can('create-evaluasi'))
                                    <span class="text-slate-300">|</span>
                                    <a href="{{ route('evaluasi-diri.edit', $evaluasi) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    
                                    <span class="text-slate-300">|</span>
                                    <form action="{{ route('evaluasi-diri.submit', $evaluasi) }}" method="POST" class="inline-block" onsubmit="return confirm('Submit evaluasi ini? Data tidak bisa diubah lagi setelah disubmit.');">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900">Submit</button>
                                    </form>
                                @endif

                                @if($evaluasi->bukti_fisik)
                                    <span class="text-slate-300">|</span>
                                    <a href="{{ Storage::url($evaluasi->bukti_fisik) }}" target="_blank" class="text-slate-600 hover:text-slate-900" download>Bukti</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-center text-slate-500">
                            Tidak ada data evaluasi diri.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($evaluasis->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 bg-white">
            {{ $evaluasis->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
