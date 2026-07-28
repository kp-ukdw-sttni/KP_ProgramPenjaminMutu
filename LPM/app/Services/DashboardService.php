<?php

namespace App\Services;

use App\Enums\StatusAudit;
use App\Enums\StatusEvaluasi;
use App\Models\AuditMutu;
use App\Models\EvaluasiDiri;
use App\Models\ProgramStudi;
use App\Models\StandarMutu;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Top-level KPI cards.
     */
    public function getKpiCards(): array
    {
        return [
            'total_standar'     => StandarMutu::count(),
            'total_prodi'       => ProgramStudi::count(),
            'total_evaluasi'    => EvaluasiDiri::count(),
            'open_audits'       => AuditMutu::where('status_audit', StatusAudit::Open->value)->count(),
            'in_progress_audits'=> AuditMutu::where('status_audit', StatusAudit::InProgress->value)->count(),
            'closed_audits'     => AuditMutu::where('status_audit', StatusAudit::Closed->value)->count(),
        ];
    }

    /**
     * PPEPP cycle completion rate: how many evaluasi_diri are at each status.
     * Used for the donut chart.
     *
     * Returns: ['Draft' => n, 'Submitted' => n, 'Audited' => n]
     */
    public function getPppepCycleData(): array
    {
        $rows = EvaluasiDiri::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return [
            StatusEvaluasi::Draft->value     => $rows[StatusEvaluasi::Draft->value]     ?? 0,
            StatusEvaluasi::Submitted->value => $rows[StatusEvaluasi::Submitted->value] ?? 0,
            StatusEvaluasi::Audited->value   => $rows[StatusEvaluasi::Audited->value]   ?? 0,
        ];
    }

    /**
     * Open KTS findings per program studi – for bar chart.
     */
    public function getOpenKtsByProdi(): Collection
    {
        return AuditMutu::with('evaluasiDiri.programStudi')
            ->where('kategori_temuan', 'KTS')
            ->whereIn('status_audit', [StatusAudit::Open->value, StatusAudit::InProgress->value])
            ->get()
            ->groupBy(fn ($a) => $a->evaluasiDiri->programStudi->nama_prodi ?? 'Unknown')
            ->map(fn ($group) => $group->count());
    }

    /**
     * Standards met vs unmet per program studi.
     * "Met" = evaluasi_diri exists for that standar and status is 'audited'.
     */
    public function getStandardsMetByProdi(): Collection
    {
        $totalStandar = StandarMutu::count();

        return ProgramStudi::withCount([
            'evaluasiDiris as met_count' => fn ($q) => $q->where('status', StatusEvaluasi::Audited->value),
            'evaluasiDiris as total_count',
        ])
        ->get()
        ->map(fn ($prodi) => [
            'nama_prodi' => $prodi->nama_prodi,
            'met'        => $prodi->met_count,
            'unmet'      => max(0, $totalStandar - $prodi->met_count),
        ]);
    }

    /**
     * Recent audit findings (last 5) for the activity feed.
     */
    public function getRecentFindings(): \Illuminate\Database\Eloquent\Collection
    {
        return AuditMutu::with(['evaluasiDiri.programStudi', 'auditor'])
            ->latest()
            ->limit(5)
            ->get();
    }
}
