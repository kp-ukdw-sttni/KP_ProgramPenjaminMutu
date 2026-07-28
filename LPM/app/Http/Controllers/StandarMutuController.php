<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStandarMutuRequest;
use App\Http\Requests\UpdateStandarMutuRequest;
use App\Models\StandarMutu;
use Illuminate\Http\Request;
use App\Exports\StandarMutuExport;
use App\Imports\StandarMutuImport;
use Maatwebsite\Excel\Facades\Excel;

class StandarMutuController extends Controller
{
    public function index(Request $request)
    {
        $query = StandarMutu::query();

        if ($request->filled('search')) {
            $query->where('nama_standar', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_standar', 'like', '%' . $request->search . '%');
        }

        $standars = $query->orderBy('kode_standar')->paginate(20)->withQueryString();

        return view('standar-mutu.index', compact('standars'));
    }

    public function create()
    {
        $this->authorize('manage-standar');

        return view('standar-mutu.create');
    }

    public function store(StoreStandarMutuRequest $request)
    {
        StandarMutu::create($request->validated());

        return redirect()->route('standar-mutu.index')
            ->with('success', 'Standar mutu berhasil ditambahkan.');
    }

    public function show(StandarMutu $standarMutu)
    {
        $standarMutu->load('evaluasiDiris.programStudi');

        return view('standar-mutu.show', ['standar' => $standarMutu]);
    }

    public function edit(StandarMutu $standarMutu)
    {
        $this->authorize('manage-standar');

        return view('standar-mutu.edit', ['standar' => $standarMutu]);
    }

    public function update(UpdateStandarMutuRequest $request, StandarMutu $standarMutu)
    {
        $standarMutu->update($request->validated());

        return redirect()->route('standar-mutu.index')
            ->with('success', 'Standar mutu berhasil diperbarui.');
    }

    public function destroy(StandarMutu $standarMutu)
    {
        $this->authorize('manage-standar');
        $standarMutu->delete();

        return redirect()->route('standar-mutu.index')
            ->with('success', 'Standar mutu berhasil dihapus.');
    }

    public function export()
    {
        $this->authorize('manage-standar');

        return Excel::download(new StandarMutuExport, 'standar-mutu.xlsx');
    }

    public function import(Request $request)
    {
        $this->authorize('manage-standar');

        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:5120',
        ]);

        Excel::import(new StandarMutuImport, $request->file('file'));

        return redirect()->route('standar-mutu.index')
            ->with('success', 'Data standar mutu berhasil diimport.');
    }
}
