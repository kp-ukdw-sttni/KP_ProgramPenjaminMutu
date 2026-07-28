<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDokumenMutuRequest;
use App\Http\Requests\UpdateDokumenMutuRequest;
use App\Models\DokumenMutu;
use App\Services\DokumenMutuService;
use Illuminate\Http\Request;

class DokumenMutuController extends Controller
{
    public function __construct(
        private readonly DokumenMutuService $service
    ) {}

    public function index(Request $request)
    {
        $query = DokumenMutu::query();

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('nomor_dokumen', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $dokumens = $query->latest()->paginate(15)->withQueryString();

        return view('dokumen-mutu.index', compact('dokumens'));
    }

    public function create()
    {
        $this->authorize('manage-dokumen');

        return view('dokumen-mutu.create');
    }

    public function store(StoreDokumenMutuRequest $request)
    {
        $this->service->create(
            $request->safe()->except('file'),
            $request->file('file')
        );

        return redirect()->route('dokumen-mutu.index')
            ->with('success', 'Dokumen berhasil ditambahkan.');
    }

    public function edit(DokumenMutu $dokumenMutu)
    {
        $this->authorize('manage-dokumen');

        return view('dokumen-mutu.edit', ['dokumen' => $dokumenMutu]);
    }

    public function update(UpdateDokumenMutuRequest $request, DokumenMutu $dokumenMutu)
    {
        $this->service->update(
            $dokumenMutu,
            $request->safe()->except('file'),
            $request->file('file')
        );

        return redirect()->route('dokumen-mutu.index')
            ->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(DokumenMutu $dokumenMutu)
    {
        $this->authorize('manage-dokumen');
        $this->service->delete($dokumenMutu);

        return redirect()->route('dokumen-mutu.index')
            ->with('success', 'Dokumen berhasil dihapus.');
    }

    /**
     * Securely stream the document file to the authenticated user.
     * Authorization: user must have view-dokumen permission.
     */
    public function download(Request $request, DokumenMutu $dokumenMutu)
    {
        $this->authorize('view-dokumen');

        return $this->service->streamFile($dokumenMutu);
    }
}
