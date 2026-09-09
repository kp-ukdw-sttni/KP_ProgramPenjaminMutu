<?php

namespace App\Services;

use App\Enums\StatusEvaluasi;
use App\Models\EvaluasiDiri;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EvaluasiDiriService
{
    private const DISK   = 'private';
    private const FOLDER = 'bukti_fisik';

    public function create(array $data, ?UploadedFile $file = null): EvaluasiDiri
    {
        $data['file_bukti_fisik'] = $file ? $this->storeFile($file) : null;
        $data['status']           = StatusEvaluasi::Draft->value;

        return EvaluasiDiri::create($data);
    }

    public function update(EvaluasiDiri $evaluasi, array $data, ?UploadedFile $file = null): EvaluasiDiri
    {
        if ($file) {
            if ($evaluasi->file_bukti_fisik) {
                Storage::disk(self::DISK)->delete($evaluasi->file_bukti_fisik);
            }
            $data['file_bukti_fisik'] = $this->storeFile($file);
        }

        $evaluasi->update($data);

        return $evaluasi;
    }

    /**
     * Transition status from draft → submitted (Menunggu Review).
     */
    public function submit(EvaluasiDiri $evaluasi): EvaluasiDiri
    {
        abort_unless($evaluasi->isDraft(), 422, 'Hanya evaluasi berstatus Draft yang dapat disubmit.');

        $evaluasi->update(['status' => StatusEvaluasi::Submitted->value]);

        return $evaluasi;
    }

    /**
     * Transition status to revisi. Used by auditor when document needs correction.
     */
    public function markRevisi(EvaluasiDiri $evaluasi): EvaluasiDiri
    {
        $evaluasi->update(['status' => StatusEvaluasi::Revisi->value]);

        return $evaluasi;
    }

    /**
     * Transition status from submitted/revisi → audited (Selesai).
     * Called automatically when all findings are closed.
     */
    public function markAudited(EvaluasiDiri $evaluasi): EvaluasiDiri
    {
        $evaluasi->update(['status' => StatusEvaluasi::Audited->value]);

        return $evaluasi;
    }

    /**
     * Set the auditor's score (nilai) for an evaluasi. Score must be 1–4.
     */
    public function setNilai(EvaluasiDiri $evaluasi, int $nilai): EvaluasiDiri
    {
        abort_unless(in_array($nilai, [1, 2, 3, 4]), 422, 'Nilai harus antara 1 dan 4.');

        $evaluasi->update(['nilai' => $nilai]);

        return $evaluasi;
    }

    public function delete(EvaluasiDiri $evaluasi): void
    {
        if ($evaluasi->file_bukti_fisik) {
            Storage::disk(self::DISK)->delete($evaluasi->file_bukti_fisik);
        }

        $evaluasi->delete();
    }

    public function streamBuktiFisik(EvaluasiDiri $evaluasi): \Symfony\Component\HttpFoundation\Response
    {
        abort_unless(
            $evaluasi->file_bukti_fisik && Storage::disk(self::DISK)->exists($evaluasi->file_bukti_fisik),
            404,
            'File bukti fisik tidak ditemukan.'
        );

        $ext = strtolower(pathinfo($evaluasi->file_bukti_fisik, PATHINFO_EXTENSION));
        if (in_array($ext, ['pdf', 'png', 'jpg', 'jpeg'])) {
            return Storage::disk(self::DISK)->response($evaluasi->file_bukti_fisik);
        }

        return Storage::disk(self::DISK)->download(
            $evaluasi->file_bukti_fisik,
            basename($evaluasi->file_bukti_fisik)
        );
    }

    private function storeFile(UploadedFile $file): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        Storage::disk(self::DISK)->putFileAs(self::FOLDER, $file, $filename);

        return self::FOLDER . '/' . $filename;
    }
}
