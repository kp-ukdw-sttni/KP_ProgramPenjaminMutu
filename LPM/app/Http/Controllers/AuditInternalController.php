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

    private function ensureOwnership(EvaluasiDiri $evaluasiDiri)
    {
        $user = auth()->user();
        if ($user->hasRole('auditee') && !$user->hasAnyRole(['superadmin', 'admin', 'auditor'])) {
            abort_unless($evaluasiDiri->program_studi_id === $user->program_studi_id, 403, 'Anda tidak memiliki akses ke dokumen ini.');
        }
    }

    public function index(Request $request)
    {
        $user  = $request->user();
        $query = EvaluasiDiri::with(['standarMutu', 'programStudi', 'auditMutus'])
            ->whereIn('status', ['submitted', 'audited']);

        if ($user->hasRole('auditee') && !$user->hasAnyRole(['superadmin', 'admin', 'auditor'])) {
            $query->where('program_studi_id', $user->program_studi_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $evaluasis = $query->latest()->paginate(15)->withQueryString();

        return view('audit-internal.index', compact('evaluasis'));
    }

    public function rekap(Request $request)
    {
        $this->authorize('view-evaluasi');

        $query = ProgramStudi::with([
            'evaluasiDiris.auditMutus',
        ])->orderBy('nama_prodi');

        $user = $request->user();
        if ($user->hasRole('auditee') && !$user->hasAnyRole(['superadmin', 'admin', 'auditor'])) {
            $query->where('id', $user->program_studi_id);
        }

        $prodis = $query->get();

        return view('audit-internal.rekap', compact('prodis'));
    }

    public function show(EvaluasiDiri $evaluasiDiri)
    {
        $this->authorize('view-evaluasi');
        $this->ensureOwnership($evaluasiDiri);

        $evaluasiDiri->load([
            'standarMutu',
            'programStudi.fakultas',
            'auditMutus.auditor',
        ]);

        return view('audit-internal.show', ['evaluasi' => $evaluasiDiri]);
    }

    public function createTemuan(EvaluasiDiri $evaluasiDiri)
    {
        $this->authorize('create-audit');
        abort_unless($evaluasiDiri->isSubmitted() || $evaluasiDiri->isAudited(), 403, 'Evaluasi belum disubmit.');

        $evaluasiDiri->load(['standarMutu', 'programStudi']);

        return view('audit-internal.create-temuan', ['evaluasi' => $evaluasiDiri]);
    }

            public function storeTemuan(StoreAuditMutuRequest $request, EvaluasiDiri $evaluasiDiri)
    {
        $this->authorize('create-audit');
        abort_unless($evaluasiDiri->isSubmitted() || $evaluasiDiri->isAudited(), 403, 'Evaluasi belum disubmit.');

        $validated = $request->validated();

        $this->service->createMultipleFindings($evaluasiDiri, $validated['temuan'], $request->user());

        if (! empty($validated['nilai'])) {
            app(\App\Services\EvaluasiDiriService::class)->setNilai($evaluasiDiri, (int) $validated['nilai']);
        }

        return redirect()->route('audit-internal.show', $evaluasiDiri)
            ->with('success', count($validated['temuan']) . ' temuan audit berhasil dicatat.');
    }

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

    public function respondForm(AuditMutu $auditMutu)
    {
        $this->authorize('respond-audit');
        $auditMutu->load('evaluasiDiri');
        $this->ensureOwnership($auditMutu->evaluasiDiri);
        abort_if($auditMutu->isClosed(), 403, 'Temuan sudah ditutup.');

        $auditMutu->load('evaluasiDiri.programStudi', 'auditor');

        return view('audit-internal.respond', ['finding' => $auditMutu]);
    }

    public function respond(Request $request, AuditMutu $auditMutu)
    {
        $this->authorize('respond-audit');
        $auditMutu->load('evaluasiDiri');
        $this->ensureOwnership($auditMutu->evaluasiDiri);

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

    public function close(AuditMutu $auditMutu)
    {
        $this->authorize('close-audit');
        $this->service->closeFinding($auditMutu);

        return redirect()->route('audit-internal.show', $auditMutu->evaluasi_diri_id)
            ->with('success', 'Temuan berhasil ditutup.');
    }

    public function downloadBuktiPerbaikan(AuditMutu $auditMutu)
    {
        $this->authorize('view-evaluasi');
        $auditMutu->load('evaluasiDiri');
        $this->ensureOwnership($auditMutu->evaluasiDiri);

        return $this->service->streamBuktiPerbaikan($auditMutu);
    }
}



