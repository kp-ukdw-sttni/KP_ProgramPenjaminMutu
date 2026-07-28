<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuditMutuRequest;
use App\Http\Requests\UpdateTindakLanjutRequest;
use App\Models\AuditMutu;
use App\Models\EvaluasiDiri;
use App\Services\AuditMutuService;
use Illuminate\Http\Request;

class AuditInternalController extends Controller
{
    public function __construct(
        private readonly AuditMutuService $service
    ) {}

    /**
     * List all submitted evaluasi_diri available for auditing.
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
     * Show a single evaluasi with all its findings and CAPA thread.
     */
    public function show(EvaluasiDiri $evaluasiDiri)
    {
        $this->authorize('view-evaluasi');

        // Eager load everything to avoid N+1
        $evaluasiDiri->load([
            'standarMutu',
            'programStudi.fakultas',
            'auditMutus.auditor',
        ]);

        return view('audit-internal.show', ['evaluasi' => $evaluasiDiri]);
    }

    /**
     * Show the form to add a new temuan for an evaluasi.
     */
    public function createTemuan(EvaluasiDiri $evaluasiDiri)
    {
        $this->authorize('create-audit');
        abort_unless($evaluasiDiri->isSubmitted() || $evaluasiDiri->isAudited(), 403, 'Evaluasi belum disubmit.');

        return view('audit-internal.create-temuan', ['evaluasi' => $evaluasiDiri]);
    }

    /**
     * Auditor stores a new finding.
     */
    public function storeTemuan(StoreAuditMutuRequest $request, EvaluasiDiri $evaluasiDiri)
    {
        $this->service->createFinding(
            $evaluasiDiri,
            $request->validated(),
            $request->user()
        );

        return redirect()->route('audit-internal.show', $evaluasiDiri)
            ->with('success', 'Temuan audit berhasil dicatat.');
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
     * Auditee submits CAPA for a finding.
     */
    public function respond(UpdateTindakLanjutRequest $request, AuditMutu $auditMutu)
    {
        $this->service->submitTindakLanjut(
            $auditMutu,
            $request->validated()['rencana_tindak_lanjut']
        );

        return redirect()->route('audit-internal.show', $auditMutu->evaluasi_diri_id)
            ->with('success', 'Rencana tindak lanjut berhasil disimpan.');
    }

    /**
     * Auditor closes a finding after verifying CAPA.
     */
    public function close(AuditMutu $auditMutu)
    {
        $this->authorize('close-audit');
        $this->service->closeFinding($auditMutu);

        return redirect()->route('audit-internal.show', $auditMutu->evaluasi_diri_id)
            ->with('success', 'Temuan berhasil ditutup (Closed).');
    }
}
