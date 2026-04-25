<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\KreditAnalis;
use App\Models\KepalaCabang;
use App\Models\MarketingStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['kreditAnalis', 'kepalaCabang', 'marketingStaff'])
            ->where('role', '!=', 'admin');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('nik',  'like', "%{$request->search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'nik'      => 'required|string|max:20|unique:users,nik',
            'email'    => 'nullable|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role'     => 'required|in:analis,kepala_cabang,marketing',
            'telepon'  => 'nullable|string|max:20',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.unique'   => 'NIK sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'nik'       => $request->nik,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->createProfile($user, $request);

        return redirect()->route('admin.users.index')
            ->with('success', "Akun {$user->role_label} atas nama {$user->name} berhasil ditambahkan.");
    }

    public function edit(User $user)
    {
        $user->load(['kreditAnalis', 'kepalaCabang', 'marketingStaff']);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'nik'      => 'required|string|max:20|unique:users,nik,' . $user->id,
            'email'    => 'nullable|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'role'     => 'required|in:analis,kepala_cabang,marketing',
            'telepon'  => 'nullable|string|max:20',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.unique'   => 'NIK sudah digunakan pengguna lain.',
        ]);

        $user->update([
            'name'      => $request->name,
            'nik'       => $request->nik,
            'email'     => $request->email,
            'role'      => $request->role,
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $this->updateProfile($user, $request);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna {$user->name} berhasil dihapus.");
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun {$user->name} berhasil {$status}.");
    }

    private function createProfile(User $user, Request $request): void
    {
        match($user->role) {
            'analis'        => KreditAnalis::create([
                                    'user_id' => $user->id,
                                    'nip'     => $request->nip,
                                    'jabatan' => $request->jabatan,
                                    'telepon' => $request->telepon,
                                ]),
            'kepala_cabang' => KepalaCabang::create([
                                    'user_id' => $user->id,
                                    'nip'     => $request->nip,
                                    'cabang'  => $request->cabang,
                                    'telepon' => $request->telepon,
                                ]),
            'marketing'     => MarketingStaff::create([
                                    'user_id' => $user->id,
                                    'nip'     => $request->nip,
                                    'area'    => $request->area,
                                    'telepon' => $request->telepon,
                                ]),
            default         => null,
        };
    }

    private function updateProfile(User $user, Request $request): void
    {
        match($user->role) {
            'analis'        => $user->kreditAnalis()->updateOrCreate(
                                    ['user_id' => $user->id],
                                    ['nip' => $request->nip, 'jabatan' => $request->jabatan, 'telepon' => $request->telepon]
                                ),
            'kepala_cabang' => $user->kepalaCabang()->updateOrCreate(
                                    ['user_id' => $user->id],
                                    ['nip' => $request->nip, 'cabang' => $request->cabang, 'telepon' => $request->telepon]
                                ),
            'marketing'     => $user->marketingStaff()->updateOrCreate(
                                    ['user_id' => $user->id],
                                    ['nip' => $request->nip, 'area' => $request->area, 'telepon' => $request->telepon]
                                ),
            default         => null,
        };
    }
}
