@extends('layouts.app')
@section('title', 'Tambah Pengguna')
@section('page-title', 'Tambah Pengguna Baru')
@section('page-subtitle', 'Buat akun dengan NIK sebagai identitas login')

@section('content')
    <div class="max-w-full">
        @if($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm mb-5">
                <ul class="space-y-1">
                    @foreach($errors->all() as $e)
                        <li class="flex gap-2"><i class="fa-solid fa-circle-xmark mt-0.5 flex-shrink-0"></i>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Pilih Role --}}
            <div class="card p-6">
                <h3 class="font-bold text-slate-800 mb-4 text-sm border-b border-slate-100 pb-3">Pilih Role Pengguna</h3>
                <div class="grid grid-cols-3 gap-3">
                    @php
                        $roles = [
                            'analis' => ['Credit Analyst', 'fa-user-pen', '#2563eb', 'Melakukan analisis kelayakan kredit'],
                            'kepala_cabang' => ['Branch Manager', 'fa-user-tie', '#d97706', 'Menyetujui hasil analisis kredit'],
                            'marketing' => ['Marketing Officier', 'fa-bullhorn', '#16a34a', 'Melihat data analisis yang disetujui'],
                        ];
                    @endphp
                    @foreach($roles as $key => [$label, $icon, $color, $desc])
                        <label
                            class="cursor-pointer border-2 border-slate-200 rounded-xl p-3 hover:border-slate-300 transition-all has-[:checked]:border-blue-400 has-[:checked]:shadow-sm">
                            <input type="radio" name="role" value="{{ $key }}" class="sr-only" {{ old('role') === $key ? 'checked' : '' }} onchange="updateRoleFields('{{ $key }}')">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-2"
                                style="background:{{ $color }}20">
                                <i class="fa-solid {{ $icon }}" style="color:{{ $color }}"></i>
                            </div>
                            <div class="font-semibold text-slate-700 text-xs">{{ $label }}</div>
                            <div class="text-slate-400 text-xs mt-0.5">{{ $desc }}</div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Info Akun --}}
            <div class="card p-6 space-y-4">
                <h3 class="font-bold text-slate-800 border-b border-slate-100 pb-3 text-sm">Informasi Akun</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white"
                            required>
                    </div>

                    {{-- NIK — field utama login --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            NIK <span class="text-red-500">*</span>
                            <span class="ml-2 px-2 py-0.5 rounded-full text-xs font-semibold"
                                style="background:#eef1f8; color:#1a2e5a">
                                <i class="fa-solid fa-key mr-1"></i>Digunakan untuk Login
                            </span>
                        </label>
                        <div class="relative">
                            <i
                                class="fa-solid fa-id-card absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="text" name="nik" value="{{ old('nik') }}"
                                placeholder="Nomor Induk Karyawan (8 karakter)" maxlength="8" minlength="8"
                                class="w-full pl-10 pr-4 py-2.5 border-2 rounded-xl text-sm bg-slate-50 focus:bg-white font-mono font-semibold"
                                style="border-color:#1a2e5a20" required>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">
                            <i class="fa-solid fa-circle-info mr-1"></i>
                            NIK ini akan digunakan pengguna setiap kali login ke sistem.
                        </p>
                    </div>

                    {{-- Email opsional --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Email
                            <span class="text-xs text-slate-400 font-normal">(opsional)</span>
                        </label>
                        <div class="relative">
                            <i
                                class="fa-solid fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com"
                                class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password <span
                                class="text-red-500">*</span></label>
                        <input type="password" name="password" placeholder="Min. 8 karakter"
                            class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi Password <span
                                class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password"
                            class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white"
                            required>
                    </div>

                    <div class="col-span-2 flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" id="isActive" checked
                            class="rounded border-slate-300 w-4 h-4" style="accent-color:#1a2e5a">
                        <label for="isActive" class="text-sm font-medium text-slate-700 cursor-pointer">Akun Aktif</label>
                    </div>
                </div>
            </div>

            {{-- Profil tambahan --}}
            <div class="card p-6 space-y-4">
                <h3 class="font-bold text-slate-800 border-b border-slate-100 pb-3 text-sm">Informasi Profil</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">NIP</label>
                        <input type="text" name="nip" value="{{ old('nip') }}" placeholder="Nomor Induk Pegawai"
                            class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Telepon</label>
                        <input type="text" name="telepon" value="{{ old('telepon') }}" placeholder="08xxxxxxxxxx"
                            class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
                    </div>

                    {{-- Analis: Jabatan --}}
                    <div class="col-span-2 role-field field-analis hidden">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jabatan</label>
                        <input type="text" name="jabatan" value="{{ old('jabatan') }}"
                            placeholder="e.g. Kredit Analis Senior"
                            class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
                    </div>

                    {{-- Kepala Cabang: Cabang --}}
                    <div class="col-span-2 role-field field-kepala_cabang hidden">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Cabang</label>
                        <input type="text" name="cabang" value="{{ old('cabang') }}"
                            placeholder="e.g. Cabang Jakarta Selatan"
                            class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
                    </div>

                    {{-- Marketing: Area --}}
                    <div class="col-span-2 role-field field-marketing hidden">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Area Pemasaran</label>
                        <input type="text" name="area" value="{{ old('area') }}" placeholder="e.g. Jabodetabek"
                            class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="btn-primary px-6 py-2.5 rounded-xl text-white font-bold text-sm flex items-center gap-2">
                    <i class="fa-solid fa-save"></i> Simpan Pengguna
                </button>
                <a href="{{ route('admin.users.index') }}"
                    class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-medium text-sm hover:bg-slate-50">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <script>
        function updateRoleFields(role) {
            document.querySelectorAll('.role-field').forEach(el => el.classList.add('hidden'));
            const t = document.querySelector('.field-' + role);
            if (t) t.classList.remove('hidden');
        }
        const savedRole = '{{ old('role', '') }}';
        if (savedRole) updateRoleFields(savedRole);
    </script>
@endsection