<?php

namespace App\Services;

use App\Models\DokumenMutu;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DokumenMutuService
{
    private const DISK   = 'private';
    private const FOLDER = 'dokumen_mutu';

    /**
     * Store a new document, optionally uploading a file to private storage.
     */
    public function create(array $data, ?UploadedFile $file = null): DokumenMutu
    {
        $data['file_path'] = $file ? $this->storeFile($file) : null;

        return DokumenMutu::create($data);
    }

    /**
     * Update an existing document record and optionally replace the file.
     */
    public function update(DokumenMutu $dokumen, array $data, ?UploadedFile $file = null): DokumenMutu
    {
        if ($file) {
            // Delete old file from storage
            if ($dokumen->file_path) {
                Storage::disk(self::DISK)->delete($dokumen->file_path);
            }
            $data['file_path'] = $this->storeFile($file);
        }

        $dokumen->update($data);

        return $dokumen;
    }

    /**
     * Delete a document and its associated file from storage.
     */
    public function delete(DokumenMutu $dokumen): void
    {
        if ($dokumen->file_path) {
            Storage::disk(self::DISK)->delete($dokumen->file_path);
        }

        $dokumen->delete();
    }

    /**
     * Store a file in private storage and return its relative path.
     */
    private function storeFile(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename  = Str::uuid() . '.' . $extension;

        Storage::disk(self::DISK)->putFileAs(self::FOLDER, $file, $filename);

        return self::FOLDER . '/' . $filename;
    }

    /**
     * Stream a file to the browser for authorized download.
     * Caller is responsible for authorization check before calling this.
     */
    public function streamFile(DokumenMutu $dokumen): \Symfony\Component\HttpFoundation\Response
    {
        abort_unless(
            $dokumen->file_path && Storage::disk(self::DISK)->exists($dokumen->file_path),
            404,
            'File tidak ditemukan.'
        );

        $ext = strtolower(pathinfo($dokumen->file_path, PATHINFO_EXTENSION));
        if (in_array($ext, ['pdf', 'png', 'jpg', 'jpeg'])) {
            return Storage::disk(self::DISK)->response($dokumen->file_path);
        }

        return Storage::disk(self::DISK)->download($dokumen->file_path, $dokumen->originalFileName());
    }
}
