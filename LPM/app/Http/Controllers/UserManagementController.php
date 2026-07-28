<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('manage-users');

        $users = User::with(['roles', 'programStudi'])
            ->when($request->search, fn ($q) => $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%'))
            ->paginate(20)
            ->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $this->authorize('manage-users');

        $roles  = Role::orderBy('name')->get();
        $prodis = ProgramStudi::orderBy('nama_prodi')->get();

        return view('users.create', compact('roles', 'prodis'));
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->safe()->except(['roles', 'password_confirmation']);

        $user = User::create($data);
        $user->syncRoles($request->roles);

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $this->authorize('manage-users');

        $roles  = Role::orderBy('name')->get();
        $prodis = ProgramStudi::orderBy('nama_prodi')->get();
        $user->load('roles', 'programStudi');

        return view('users.edit', compact('user', 'roles', 'prodis'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->safe()->except(['roles', 'password', 'password_confirmation']);

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $user->update($data);
        $user->syncRoles($request->roles);

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $this->authorize('manage-users');

        abort_if($user->id === auth()->id(), 403, 'Anda tidak dapat menghapus akun Anda sendiri.');

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
