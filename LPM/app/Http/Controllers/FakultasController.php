<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFakultasRequest;
use App\Models\Fakultas;
use App\Models\ProgramStudi;
use App\Http\Requests\StoreProgramStudiRequest;
use Illuminate\Http\Request;

class FakultasController extends Controller
{
    public function index()
    {
        $this->authorize('manage-users');
        $fakultas = Fakultas::withCount('programStudis')->paginate(20);

        return view('fakultas.index', compact('fakultas'));
    }

    public function create()
    {
        $this->authorize('manage-users');

        return view('fakultas.create');
    }

    public function store(StoreFakultasRequest $request)
    {
        Fakultas::create($request->validated());

        return redirect()->route('fakultas.index')->with('success', 'Fakultas berhasil ditambahkan.');
    }

    public function edit(Fakultas $fakultas)
    {
        $this->authorize('manage-users');

        return view('fakultas.edit', compact('fakultas'));
    }

    public function update(StoreFakultasRequest $request, Fakultas $fakultas)
    {
        $fakultas->update($request->validated());

        return redirect()->route('fakultas.index')->with('success', 'Fakultas berhasil diperbarui.');
    }

    public function destroy(Fakultas $fakultas)
    {
        $this->authorize('manage-users');
        $fakultas->delete();

        return redirect()->route('fakultas.index')->with('success', 'Fakultas berhasil dihapus.');
    }
}
