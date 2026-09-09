@extends('layouts.app')

@section('title', 'Detail Audit — Daftar Temuan')

@section('breadcrumb')
<nav class="flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('audit-internal.index') }}" class="hover:text-indigo-600">Daftar Temuan</a>
    <span>/</span>
    <span class="font-medium text-slate-800">Detail</span>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Header Card --}}
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
                        <dd class="mt-1 text-slate-900 font-medium">{{ $evaluasi->standarMutu->kode_standar }} — {{ $evaluasi->standarMutu->nama_standar }}</dd>
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

                {{-- Nilai Badge --}}
                @if($evaluasi->nilai)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium {{ $evaluasi->nilaiColor() }}">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        Nilai: {{ $evaluasi->nilaiLabel() }}
                    </span>
                @endif

                {{-- Set Nilai Form (Auditor only) --}}
                @role('auditor|Auditor')
                    <div x-data="{ showNilai: false }">
                        <button @click="showNilai = !showNilai"
                                class="text-xs text-amber-600 hover:text-amber-800 font-medium underline">
                            {{ $evaluasi->nilai ? 'Ubah Nilai' : 'Set Nilai' }}
                        </button>
                        <div x-show="showNilai" x-transition class="mt-2">
                            <form action="{{ route('audit-internal.setNilai', $evaluasi) }}" method="POST" class="flex gap-2 items-center">
                                @csrf
                                <select name="nilai" required class="rounded border-slate-300 text-sm">
                                    <option value="">Pilih</option>
                                    <option value="1" {{ $evaluasi->nilai == 1 ? 'selected' : '' }}>1 — Sangat Kurang</option>
                                    <option value="2" {{ $evaluasi->nilai == 2 ? 'selected' : '' }}>2 — Kurang</option>
                                    <option value="3" {{ $evaluasi->nilai == 3 ? 'selected' : '' }}>3 — Baik</option>
                                    <option value="4" {{ $evaluasi->nilai == 4 ? 'selected' : '' }}>4 — Sangat Baik</option>
                                </select>
                                <button type="submit" class="px-3 py-1 bg-amber-600 text-white rounded text-xs font-medium hover:bg-amber-700">
                                    Simpan
                                </button>
                            </form>
                        </div>
                    </div>
                @endrole

                @if($evaluasi->hasFile())
                    <a href="{{ route('evaluasi-diri.download-bukti', $evaluasi) }}"
                       class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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

    {{-- Findings Section --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Daftar Temuan</h3>
                <div class="flex gap-2 mt-1">
                    @php
                        $kts = $evaluasi->auditMutus->where('kategori', \App\Enums\KategoriTemuan::KTS)->count();
                        $ob = $evaluasi->auditMutus->where('kategori', \App\Enums\KategoriTemuan::OB)->count();
                        $peluang = $evaluasi->auditMutus->where('kategori', \App\Enums\KategoriTemuan::PeluangPeningkatan)->count();
                    @endphp
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">{{ $kts }} KTS</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">{{ $ob }} OB</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-brand-100 text-brand-800">{{ $peluang }} Peluang</span>
                </div>
            </div>

            @role('auditor|Auditor')
                @if(in_array($evaluasi->status->value, [\App\Enums\StatusEvaluasi::Submitted->value, \App\Enums\StatusEvaluasi::Audited->value]))
                    <a href="{{ route('audit-internal.temuan.create', $evaluasi) }}"
                       class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        + Tambah Temuan
                    </a>
                @endif
            @endrole
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
                            <div class="text-slate-800 text-sm whitespace-pre-line">{!! $temuan->deskripsi_temuan !!}</div>
                        </div>

                        @if($temuan->rekomendasi)
                        <div>
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Rekomendasi</h4>
                            <div class="text-slate-800 text-sm whitespace-pre-line">{{ $temuan->rekomendasi }}</div>
                        </div>
                        @endif

                        {{-- CAPA Thread --}}
                        <div class="mt-4 pt-4 border-t border-slate-100 space-y-3">
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tindak Lanjut (CAPA)</h4>

                            @if($temuan->akar_masalah)
                                <div>
                                    <span class="text-xs font-medium text-slate-500">Akar Masalah</span>
                                    <div class="mt-1 bg-amber-50 border border-amber-100 p-3 rounded-lg text-sm text-amber-900 whitespace-pre-line">{!! $temuan->akar_masalah !!}</div>
                                </div>
                            @endif

                            @if($temuan->tindak_lanjut ?? $temuan->rencana_tindak_lanjut)
                                <div>
                                    <span class="text-xs font-medium text-slate-500">Rencana Tindak Lanjut</span>
                                    <div class="mt-1 bg-green-50 border border-green-100 p-3 rounded-lg text-sm text-green-900 whitespace-pre-line">
                                        {!! $temuan->tindak_lanjut ?? $temuan->rencana_tindak_lanjut !!}
                                    </div>
                                </div>
                            @else
                                <div class="text-sm text-slate-400 italic">Belum ada tindak lanjut.</div>
                            @endif

                            @if($temuan->bukti_perbaikan)
                                <div>
                                    <span class="text-xs font-medium text-slate-500">Bukti Perbaikan</span>
                                    <div class="mt-1">
                                        <a href="{{ route('audit-internal.bukti.download', $temuan) }}"
                                           target="_blank"
                                           class="inline-flex items-center gap-1.5 text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                            </svg>
                                            Lihat Bukti Perbaikan
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Action buttons --}}
                        <div class="flex justify-end gap-2 mt-2">
                            @role('auditee|Auditee')
                                @if($temuan->status !== \App\Enums\StatusAudit::Closed && auth()->user()->program_studi_id === $evaluasi->program_studi_id)
                                    <a href="{{ route('audit-internal.respond.form', $temuan) }}"
                                       class="inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                        Balas dengan Tindak Lanjut
                                    </a>
                                @endif
                            @endrole

                            @role('auditor|Auditor')
                                @if($temuan->status === \App\Enums\StatusAudit::InProgress)
                                    <form action="{{ route('audit-internal.close', $temuan->id) }}" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menutup temuan ini?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700">
                                            Tutup Temuan
                                        </button>
                                    </form>
                                @endif
                            @endrole
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


