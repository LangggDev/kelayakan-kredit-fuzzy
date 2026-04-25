<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\KreditAnalis;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->to($this->dashboardRoute(Auth::user()->role));
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nik'      => 'required|string',
            'password' => 'required|string',
        ], [
            'nik.required'      => 'NIK wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::where('nik', $request->nik)->first();

        // Validasi: user tidak ditemukan
        if (!$user) {
            return back()
                ->withErrors(['nik' => 'NIK tidak terdaftar dalam sistem.'])
                ->withInput($request->only('nik'));
        }

        // Validasi: password salah
        if (!Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['password' => 'Password yang Anda masukkan salah.'])
                ->withInput($request->only('nik'));
        }

        // Validasi: akun nonaktif
        if (!$user->is_active) {
            return back()
                ->withErrors(['nik' => 'Akun Anda telah dinonaktifkan. Hubungi Administrator.'])
                ->withInput($request->only('nik'));
        }

        // Login manual
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended($this->dashboardRoute($user->role));
    }

    private function dashboardRoute(string $role): string
    {
        return match($role) {
            'admin'         => route('admin.dashboard'),
            'analis'        => route('analis.dashboard'),
            'kepala_cabang' => route('kepala_cabang.dashboard'),
            'marketing'     => route('marketing.dashboard'),
            default         => route('login'),
        };
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'nik'      => 'required|string|max:20|unique:users,nik',
            'email'    => 'nullable|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'nip_ka'   => 'nullable|string|max:50',
            'jabatan'  => 'nullable|string|max:100',
            'telepon'  => 'nullable|string|max:20',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.unique'   => 'NIK sudah terdaftar. Gunakan NIK lain atau hubungi Admin.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'nik'      => $request->nik,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'analis',
        ]);

        KreditAnalis::create([
            'user_id' => $user->id,
            'nip'     => $request->nip_ka,
            'jabatan' => $request->jabatan,
            'telepon' => $request->telepon,
        ]);

        Auth::login($user);
        return redirect()->route('analis.dashboard')
            ->with('success', 'Akun berhasil dibuat. Selamat datang, ' . $user->name . '!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda berhasil logout.');
    }
}
