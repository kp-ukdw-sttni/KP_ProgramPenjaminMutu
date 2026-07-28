<?php

namespace App\Http\Controllers;

use App\Enums\StatusEvaluasi;
use App\Http\Requests\StoreEvaluasiDiriRequest;
use App\Http\Requests\UpdateEvaluasiDiriRequest;
use App\Models\EvaluasiDiri;
use App\Models\ProgramStudi;
use App\Models\StandarMutu;
use App\Services\EvaluasiDiriService;
use Illuminate\Http\Request;

class EvaluasiDiriController extends Controller
{
    public function __construct(
        private readonly EvaluasiDiriService $service
    ) {}

    public function index(Request $request)
    {
        $user  = $request->user();
        $query = EvaluasiDiri::with(['standarMutu', 'programStudi']);

        // Auditees only see their own program studi's evaluations
        if ($user->isAuditee() && ! $user->isSuperadmin()) {
            $query->where('program_studi_id', $user->program_studi_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tahun_akademik')) {
            $query->where('tahun_akademik', $request->tahun_akademik);
        }

        $evaluasis = $query->latest()->paginate(15)->withQueryString();

        return view('evaluasi-diri.index', compact('evaluasis'));
    }

    public function create()
    {
        $this->authorize('create-evaluasi');

        $standars = StandarMutu::orderBy('kode_standar')->get();
        $prodis   = ProgramStudi::orderBy('nama_prodi')->get();

        return view('evaluasi-diri.create', compact('standars', 'prodis'));
    }

    public function store(StoreEvaluasiDiriRequest $request)
    {
        $this->service->create(
            $request->safe()->except('file_bukti_fisik'),
            $request->file('file_bukti_fisik')
        );

        return redirect()->route('evaluasi-diri.index')
            ->with('success', 'Evaluasi diri berhasil disimpan sebagai Draft.');
    }

    public function show(EvaluasiDiri $evaluasiDiri)
    {
        // Eager-load all audit findings and their auditors to prevent N+1
        $evaluasiDiri->load([
            'standarMutu',
            'programStudi.fakultas',
            'auditMutus.auditor',
        ]);

        return view('evaluasi-diri.show', ['evaluasi' => $evaluasiDiri]);
    }

    public function edit(EvaluasiDiri $evaluasiDiri)
    {
        $this->authorize('create-evaluasi');
        abort_unless($evaluasiDiri->isDraft(), 403, 'Hanya evaluasi berstatus Draft yang dapat diubah.');

        $standars = StandarMutu::orderBy('kode_standar')->get();
        $prodis   = ProgramStudi::orderBy('nama_prodi')->get();

        return view('evaluasi-diri.edit', [
            'evaluasi' => $evaluasiDiri,
            'standars' => $standars,
            'prodis'   => $prodis,
        ]);
    }

    public function update(UpdateEvaluasiDiriRequest $request, EvaluasiDiri $evaluasiDiri)
    {
        $this->service->update(
            $evaluasiDiri,
            $request->safe()->except('file_bukti_fisik'),
            $request->file('file_bukti_fisik')
        );

        return redirect()->route('evaluasi-diri.show', $evaluasiDiri)
            ->with('success', 'Evaluasi diri berhasil diperbarui.');
    }

    /**
     * Submit a draft evaluasi for auditing.
     */
    public function submit(EvaluasiDiri $evaluasiDiri)
    {
        $this->authorize('create-evaluasi');
        $this->service->submit($evaluasiDiri);

        return redirect()->route('evaluasi-diri.show', $evaluasiDiri)
            ->with('success', 'Evaluasi diri berhasil disubmit dan siap untuk diaudit.');
    }

    /**
     * Securely stream the bukti fisik file.
     */
    public function downloadBukti(EvaluasiDiri $evaluasiDiri)
    {
        $this->authorize('view-evaluasi');

        return $this->service->streamBuktiFisik($evaluasiDiri);
    }

    public function destroy(EvaluasiDiri $evaluasiDiri)
    {
        $this->authorize('create-evaluasi');
        abort_unless($evaluasiDiri->isDraft(), 403, 'Hanya evaluasi Draft yang dapat dihapus.');

        $this->service->delete($evaluasiDiri);

        return redirect()->route('evaluasi-diri.index')
            ->with('success', 'Evaluasi diri berhasil dihapus.');
    }
}
