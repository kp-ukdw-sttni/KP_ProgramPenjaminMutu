<?php

namespace App\Http\Controllers;

use App\Models\PengurusLpm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller as BaseController;

class PengurusLpmController extends BaseController
{
    /**
     * Protect the management routes using Spatie permission middleware.
     */
    public function __construct()
    {
        $this->middleware('permission:manage-kepengurusan')->except(['index']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch data ordered by urutan ascending
        $pengurusList = PengurusLpm::orderBy('urutan', 'asc')->get();

        return view('pengurus-lpm.index', compact('pengurusList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pengurus-lpm.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jabatan' => 'required|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'email' => 'nullable|email|max:255',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('pengurus', 'public');
        }

        // Get the next order number dynamically
        $maxUrutan = PengurusLpm::max('urutan') ?? 0;

        PengurusLpm::create([
            'jabatan' => $request->jabatan,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'foto' => $fotoPath,
            'is_permanent' => false,
            'urutan' => $maxUrutan + 1,
        ]);

        return redirect()->route('pengurus-lpm.index')
            ->with('success', 'Anggota baru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PengurusLpm $pengurusLpm)
    {
        return view('pengurus-lpm.edit', compact('pengurusLpm'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PengurusLpm $pengurusLpm)
    {
        if ($pengurusLpm->is_permanent) {
            $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'email' => 'nullable|email|max:255',
            ]);

            $data = [
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
            ];
        } else {
            $request->validate([
                'jabatan' => 'required|string|max:255',
                'nama_lengkap' => 'required|string|max:255',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'email' => 'nullable|email|max:255',
            ]);

            $data = [
                'jabatan' => $request->jabatan,
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
            ];
        }

        // Check if user requested to remove the current photo
        if ($request->has('hapus_foto') && $pengurusLpm->foto) {
            Storage::disk('public')->delete($pengurusLpm->foto);
            $pengurusLpm->foto = null;
            $pengurusLpm->save();
            $data['foto'] = null;
        }

        if ($request->hasFile('foto')) {
            // Delete old photo from storage if exists
            if ($pengurusLpm->foto) {
                Storage::disk('public')->delete($pengurusLpm->foto);
            }
            $data['foto'] = $request->file('foto')->store('pengurus', 'public');
        }

        $pengurusLpm->update($data);

        return redirect()->route('pengurus-lpm.index')
            ->with('success', 'Data pengurus berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PengurusLpm $pengurusLpm)
    {
        // Strict Validation: prevent deleting permanent roles
        if ($pengurusLpm->is_permanent) {
            return redirect()->route('pengurus-lpm.index')
                ->with('error', 'Jabatan Ketua tidak dapat dihapus, hanya dapat diedit.');
        }

        // Delete photo from storage if exists
        if ($pengurusLpm->foto) {
            Storage::disk('public')->delete($pengurusLpm->foto);
        }

        $pengurusLpm->delete();

        return redirect()->route('pengurus-lpm.index')
            ->with('success', 'Anggota berhasil dihapus.');
    }
}
