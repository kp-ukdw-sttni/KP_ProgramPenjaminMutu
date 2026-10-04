<?php

namespace App\Http\Controllers;

use App\Models\EvaluasiDiri;
use App\Models\StandarMutu;
use Illuminate\Http\Request;

class SummaryEvaluasiController extends Controller
{
    public function index()
    {
        $standars = StandarMutu::orderBy('kode_standar')->get();
        return view('summary-evaluasi.index', compact('standars'));
    }

    public function show(Request $request, StandarMutu $standarMutu)
    {
        $user = $request->user();
        $query = EvaluasiDiri::with(['programStudi', 'auditMutus'])
            ->where('standar_mutu_id', $standarMutu->id);

        // Batasan: Auditee murni hanya bisa melihat summary prodi miliknya
        if ($user->hasRole('auditee') && !$user->hasAnyRole(['superadmin', 'admin', 'auditor'])) {
            $query->where('program_studi_id', $user->program_studi_id);
        }

        $evaluasis = $query->orderBy('tahun_akademik', 'desc')
            ->orderBy('semester', 'desc')
            ->get()
            ->groupBy(function ($item) {
                return $item->programStudi ? $item->programStudi->nama_prodi : 'Tanpa Prodi';
            });

        return view('summary-evaluasi.show', compact('standarMutu', 'evaluasis'));
    }
}
