<?php

namespace App\Services;

use App\Enums\StatusAudit;
use App\Enums\StatusEvaluasi;
use App\Models\AuditMutu;
use App\Models\EvaluasiDiri;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AuditMutuService
{
    private const DISK          = 'private';
    private const BUKTI_FOLDER  = 'bukti_perbaikan';

    public function __construct(
        private readonly EvaluasiDiriService $evaluasiDiriService
    ) {}

    /**
     * Create a new audit finding (temuan) for a given evaluasi_diri.
     * Sets evaluasi_diri status to 'audited' (Selesai) on first finding.
     *
     * @param  array  $data  Single finding data: kategori_temuan, deskripsi_temuan, rekomendasi (opt)
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
     * Create multiple findings at once from an Alpine.js multi-row form.
     *
     * @param  array  $rows  Array of ['kategori_temuan' => ..., 'deskripsi_temuan' => ..., 'rekomendasi' => ...]
     * @return AuditMutu[]
     */
    public function createMultipleFindings(EvaluasiDiri $evaluasi, array $rows, User $auditor): array
    {
        abort_unless($evaluasi->isSubmitted() || $evaluasi->isAudited(), 422, 'Temuan hanya dapat dibuat untuk evaluasi yang telah disubmit.');

        $created = [];
        foreach ($rows as $row) {
            $created[] = AuditMutu::create([
                'evaluasi_diri_id' => $evaluasi->id,
                'auditor_id'       => $auditor->id,
                'kategori_temuan'  => $row['kategori_temuan'],
                'deskripsi_temuan' => $row['deskripsi_temuan'],
                'rekomendasi'      => $row['rekomendasi'] ?? null,
                'status_audit'     => StatusAudit::Open->value,
            ]);
        }

        // Mark the evaluasi as under-audit on first batch of findings
        if ($evaluasi->isSubmitted()) {
            $this->evaluasiDiriService->markAudited($evaluasi);
        }

        return $created;
    }

    /**
     * Auditee submits a CAPA response for a finding.
     * Saves akar_masalah, tindak_lanjut, and optional bukti_perbaikan file.
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
     * Auditee submits extended CAPA: akar_masalah, tindak_lanjut, and optional file.
     * Transitions the finding to 'in_progress'.
     */
    public function submitTindakLanjutLengkap(
        AuditMutu $finding,
        string $akarMasalah,
        string $tindakLanjut,
        ?UploadedFile $buktiFisik = null
    ): AuditMutu {
        abort_if($finding->isClosed(), 422, 'Temuan yang sudah ditutup tidak dapat direspons.');

        $data = [
            'akar_masalah'          => $akarMasalah,
            'tindak_lanjut'         => $tindakLanjut,
            'rencana_tindak_lanjut' => $tindakLanjut, // keep legacy column in sync
            'status_audit'          => StatusAudit::InProgress->value,
        ];

        if ($buktiFisik) {
            // Delete old file if exists
            if ($finding->bukti_perbaikan) {
                Storage::disk(self::DISK)->delete($finding->bukti_perbaikan);
            }
            $filename           = Str::uuid() . '.' . $buktiFisik->getClientOriginalExtension();
            Storage::disk(self::DISK)->putFileAs(self::BUKTI_FOLDER, $buktiFisik, $filename);
            $data['bukti_perbaikan'] = self::BUKTI_FOLDER . '/' . $filename;
        }

        $finding->update($data);

        return $finding;
    }

    /**
     * Auditor closes a finding after verifying the corrective action.
     * If ALL findings for the parent evaluasi are now Closed, auto-mark it as Selesai.
     */
    public function closeFinding(AuditMutu $finding): AuditMutu
    {
        abort_unless($finding->isInProgress(), 422, 'Hanya temuan berstatus In Progress yang dapat ditutup.');

        $finding->update(['status_audit' => StatusAudit::Closed->value]);

        // Auto-complete evaluasi when all findings are closed
        $evaluasi = $finding->evaluasiDiri;
        $allClosed = $evaluasi->auditMutus()
            ->where('status_audit', '!=', StatusAudit::Closed->value)
            ->doesntExist();

        if ($allClosed) {
            $this->evaluasiDiriService->markAudited($evaluasi);
        }

        return $finding;
    }

    /**
     * Stream or download the bukti_perbaikan file.
     */
    public function streamBuktiPerbaikan(AuditMutu $finding): \Symfony\Component\HttpFoundation\Response
    {
        abort_unless(
            $finding->bukti_perbaikan && Storage::disk(self::DISK)->exists($finding->bukti_perbaikan),
            404,
            'File bukti perbaikan tidak ditemukan.'
        );

        $ext = strtolower(pathinfo($finding->bukti_perbaikan, PATHINFO_EXTENSION));
        if (in_array($ext, ['pdf', 'png', 'jpg', 'jpeg'])) {
            return Storage::disk(self::DISK)->response($finding->bukti_perbaikan);
        }

        return Storage::disk(self::DISK)->download(
            $finding->bukti_perbaikan,
            basename($finding->bukti_perbaikan)
        );
    }
}
