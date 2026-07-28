@extends('layouts.app')

@section('title', 'Program Studi')

@section('breadcrumb')
<nav class="flex items-center gap-2 text-sm text-slate-500">
    <span class="font-medium text-slate-800">Program Studi</span>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold text-slate-800">Daftar Program Studi</h2>
        <a href="{{ route('program-studi.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            + Tambah Prodi
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left whitespace-nowrap">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Nama Prodi</th>
                        <th scope="col" class="px-6 py-3">Fakultas</th>
                        <th scope="col" class="px-6 py-3">Kepala Prodi</th>
                        <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($prodis as $prodi)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">{{ $loop->iteration + $prodis->firstItem() - 1 }}</td>
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $prodi->nama_prodi }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $prodi->fakultas->nama_fakultas ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $prodi->kepala_prodi ?? '-' }}</td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('program-studi.edit', $prodi->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">Edit</a>
                                <form action="{{ route('program-studi.destroy', $prodi->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus prodi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium text-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                Tidak ada data program studi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($prodis->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $prodis->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
