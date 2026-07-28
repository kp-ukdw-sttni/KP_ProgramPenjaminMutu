<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProgramStudiRequest;
use App\Models\Fakultas;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class ProgramStudiController extends Controller
{
    public function index()
    {
        $this->authorize('manage-users');
        $prodis = ProgramStudi::with('fakultas')->paginate(20);

        return view('program-studi.index', compact('prodis'));
    }

    public function create()
    {
        $this->authorize('manage-users');
        $fakultas = Fakultas::orderBy('nama_fakultas')->get();

        return view('program-studi.create', compact('fakultas'));
    }

    public function store(StoreProgramStudiRequest $request)
    {
        ProgramStudi::create($request->validated());

        return redirect()->route('program-studi.index')
            ->with('success', 'Program studi berhasil ditambahkan.');
    }

    public function edit(ProgramStudi $programStudi)
    {
        $this->authorize('manage-users');
        $fakultas = Fakultas::orderBy('nama_fakultas')->get();

        return view('program-studi.edit', compact('programStudi', 'fakultas'));
    }

    public function update(StoreProgramStudiRequest $request, ProgramStudi $programStudi)
    {
        $programStudi->update($request->validated());

        return redirect()->route('program-studi.index')
            ->with('success', 'Program studi berhasil diperbarui.');
    }

    public function destroy(ProgramStudi $programStudi)
    {
        $this->authorize('manage-users');
        $programStudi->delete();

        return redirect()->route('program-studi.index')
            ->with('success', 'Program studi berhasil dihapus.');
    }
}
