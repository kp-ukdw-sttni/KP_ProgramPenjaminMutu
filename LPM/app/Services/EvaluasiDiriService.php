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
     * Transition status from draft → submitted.
     */
    public function submit(EvaluasiDiri $evaluasi): EvaluasiDiri
    {
        abort_unless($evaluasi->isDraft(), 422, 'Hanya evaluasi berstatus Draft yang dapat disubmit.');

        $evaluasi->update(['status' => StatusEvaluasi::Submitted->value]);

        return $evaluasi;
    }

    /**
     * Transition status from submitted → audited (called after audit is closed).
     */
    public function markAudited(EvaluasiDiri $evaluasi): EvaluasiDiri
    {
        $evaluasi->update(['status' => StatusEvaluasi::Audited->value]);

        return $evaluasi;
    }

    public function delete(EvaluasiDiri $evaluasi): void
    {
        if ($evaluasi->file_bukti_fisik) {
            Storage::disk(self::DISK)->delete($evaluasi->file_bukti_fisik);
        }

        $evaluasi->delete();
    }

    public function streamBuktiFisik(EvaluasiDiri $evaluasi): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        abort_unless(
            $evaluasi->file_bukti_fisik && Storage::disk(self::DISK)->exists($evaluasi->file_bukti_fisik),
            404,
            'File bukti fisik tidak ditemukan.'
        );

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
