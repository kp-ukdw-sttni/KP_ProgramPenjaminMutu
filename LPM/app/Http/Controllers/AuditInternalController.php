<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuditMutuRequest;
use App\Http\Requests\UpdateTindakLanjutRequest;
use App\Models\AuditMutu;
use App\Models\EvaluasiDiri;
use App\Models\ProgramStudi;
use App\Services\AuditMutuService;
use Illuminate\Http\Request;

class AuditInternalController extends Controller
{
    public function __construct(
        private readonly AuditMutuService $service
    ) {}

    /**
     * List all submitted/audited evaluasi_diri available for the Monev process.
     * Auditors see all; auditees see only their prodi.
     */
    public function index(Request $request)
    {
        $user  = $request->user();
        $query = EvaluasiDiri::with(['standarMutu', 'programStudi', 'auditMutus'])
            ->whereIn('status', ['submitted', 'audited']);

        if ($user->isAuditee() && ! $user->isSuperadmin()) {
            $query->where('program_studi_id', $user->program_studi_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $evaluasis = $query->latest()->paginate(15)->withQueryString();

        return view('audit-internal.index', compact('evaluasis'));
    }

    /**
     * Rekapitulasi: summary of evaluasi status and findings per prodi.
     */
    public function rekap(Request $request)
    {
        $this->authorize('view-evaluasi');

        $prodis = ProgramStudi::with([
            'evaluasiDiris.auditMutus',
        ])->orderBy('nama_prodi')->get();

        return view('audit-internal.rekap', compact('prodis'));
    }

    /**
     * Show a single evaluasi with all its findings and CAPA thread.
     */
    public function show(EvaluasiDiri $evaluasiDiri)
    {
        $this->authorize('view-evaluasi');

        $evaluasiDiri->load([
            'standarMutu',
            'programStudi.fakultas',
            'auditMutus.auditor',
        ]);

        return view('audit-internal.show', ['evaluasi' => $evaluasiDiri]);
    }

    /**
     * Show the Alpine.js multi-row form to add new temuan for an evaluasi.
     */
    public function createTemuan(EvaluasiDiri $evaluasiDiri)
    {
        $this->authorize('create-audit');
        abort_unless($evaluasiDiri->isSubmitted() || $evaluasiDiri->isAudited(), 403, 'Evaluasi belum disubmit.');

        $evaluasiDiri->load(['standarMutu', 'programStudi']);

        return view('audit-internal.create-temuan', ['evaluasi' => $evaluasiDiri]);
    }

    /**
     * Auditor stores multiple findings at once.
     * Accepts: temuan[*][kategori_temuan], temuan[*][deskripsi_temuan], temuan[*][rekomendasi]
     * Also optionally accepts: nilai (1-4) for the evaluasi document.
     */
    public function storeTemuan(Request $request, EvaluasiDiri $evaluasiDiri)
    {
        $this->authorize('create-audit');

        $validated = $request->validate([
            'temuan'                          => 'required|array|min:1',
            'temuan.*.kategori_temuan'        => 'required|string|in:KTS,OB,Peluang_Peningkatan',
            'temuan.*.deskripsi_temuan'       => 'required|string|min:10',
            'temuan.*.rekomendasi'            => 'nullable|string',
            'nilai'                           => 'nullable|integer|min:1|max:4',
        ]);

        $rows = $request->input('temuan');
        $this->service->createMultipleFindings(
            $evaluasiDiri,
            $rows,
            $request->user()
        );

        // Optionally set nilai
        if (! empty($validated['nilai'])) {
            app(\App\Services\EvaluasiDiriService::class)->setNilai($evaluasiDiri, (int) $validated['nilai']);
        }

        return redirect()->route('audit-internal.show', $evaluasiDiri)
            ->with('success', count($rows) . ' temuan audit berhasil dicatat.');
    }

    /**
     * Auditor sets the nilai (score 1–4) for an evaluasi document.
     */
    public function setNilai(Request $request, EvaluasiDiri $evaluasiDiri)
    {
        $this->authorize('create-audit');

        $validated = $request->validate([
            'nilai' => 'required|integer|min:1|max:4',
        ]);

        app(\App\Services\EvaluasiDiriService::class)->setNilai($evaluasiDiri, $validated['nilai']);

        return redirect()->route('audit-internal.show', $evaluasiDiri)
            ->with('success', 'Nilai berhasil disimpan.');
    }

    /**
     * Show the CAPA response form for an auditee.
     */
    public function respondForm(AuditMutu $auditMutu)
    {
        $this->authorize('respond-audit');
        abort_if($auditMutu->isClosed(), 403, 'Temuan sudah ditutup.');

        $auditMutu->load('evaluasiDiri.programStudi', 'auditor');

        return view('audit-internal.respond', ['finding' => $auditMutu]);
    }

    /**
     * Auditee submits CAPA (akar_masalah + tindak_lanjut + optional bukti file).
     */
    public function respond(Request $request, AuditMutu $auditMutu)
    {
        $this->authorize('respond-audit');

        $validated = $request->validate([
            'akar_masalah'   => 'required|string|min:10',
            'tindak_lanjut'  => 'required|string|min:20',
            'bukti_perbaikan'=> 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        $this->service->submitTindakLanjutLengkap(
            $auditMutu,
            $validated['akar_masalah'],
            $validated['tindak_lanjut'],
            $request->file('bukti_perbaikan')
        );

        return redirect()->route('audit-internal.show', $auditMutu->evaluasi_diri_id)
            ->with('success', 'Tindak lanjut berhasil disimpan.');
    }

    /**
     * Auditor closes a finding after verifying CAPA.
     * Auto-marks evaluasi as Selesai when all findings are closed.
     */
    public function close(AuditMutu $auditMutu)
    {
        $this->authorize('close-audit');
        $this->service->closeFinding($auditMutu);

        return redirect()->route('audit-internal.show', $auditMutu->evaluasi_diri_id)
            ->with('success', 'Temuan berhasil ditutup.');
    }

    /**
     * Download bukti perbaikan file.
     */
    public function downloadBuktiPerbaikan(AuditMutu $auditMutu)
    {
        $this->authorize('view-evaluasi');

        return $this->service->streamBuktiPerbaikan($auditMutu);
    }
}
