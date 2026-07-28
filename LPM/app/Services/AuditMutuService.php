<?php

namespace App\Services;

use App\Enums\StatusAudit;
use App\Models\AuditMutu;
use App\Models\EvaluasiDiri;
use App\Models\User;

class AuditMutuService
{
    public function __construct(
        private readonly EvaluasiDiriService $evaluasiDiriService
    ) {}

    /**
     * Create a new audit finding (temuan) for a given evaluasi_diri.
     * Sets evaluasi_diri status to 'audited' on first finding.
     */
    public function createFinding(EvaluasiDiri $evaluasi, array $data, User $auditor): AuditMutu
    {
        abort_unless($evaluasi->isSubmitted() || $evaluasi->isAudited(), 422, 'Temuan hanya dapat dibuat untuk evaluasi yang telah disubmit.');

        $finding = AuditMutu::create([
            'evaluasi_diri_id' => $evaluasi->id,
            'auditor_id'       => $auditor->id,
            'kategori_temuan'  => $data['kategori_temuan'],
            'deskripsi_temuan' => $data['deskripsi_temuan'],
            'rekomendasi'      => $data['rekomendasi'] ?? null,
            'status_audit'     => StatusAudit::Open->value,
        ]);

        // Mark the evaluasi as audited once at least one finding is recorded
        if ($evaluasi->isSubmitted()) {
            $this->evaluasiDiriService->markAudited($evaluasi);
        }

        return $finding;
    }

    /**
     * Auditee submits a corrective action plan (CAPA) for a finding.
     * Transitions the finding to 'in_progress'.
     */
    public function submitTindakLanjut(AuditMutu $finding, string $rencana): AuditMutu
    {
        abort_if($finding->isClosed(), 422, 'Temuan yang sudah ditutup tidak dapat direspons.');

        $finding->update([
            'rencana_tindak_lanjut' => $rencana,
            'status_audit'          => StatusAudit::InProgress->value,
        ]);

        return $finding;
    }

    /**
     * Auditor closes a finding after verifying the corrective action.
     */
    public function closeFinding(AuditMutu $finding): AuditMutu
    {
        abort_unless($finding->isInProgress(), 422, 'Hanya temuan berstatus In Progress yang dapat ditutup.');

        $finding->update(['status_audit' => StatusAudit::Closed->value]);

        return $finding;
    }
}
