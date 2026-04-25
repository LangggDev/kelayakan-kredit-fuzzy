@extends('layouts.app')
@section('title', 'Edit Kredit Analis')
@section('page-title', 'Edit Kredit Analis')
@section('page-subtitle', 'Perbarui data ' . $user->name)

@section('content')
<div class="w-full">
    @if($errors->any())
    <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm mb-5">
        <ul class="space-y-1">@foreach($errors->all() as $e)<li class="flex gap-2"><i class="fa-solid fa-circle-xmark mt-0.5"></i>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-5">
        @csrf @method('PUT')
        <div class="card p-6 space-y-4">
            <h3 class="font-semibold text-slate-800 border-b border-slate-100 pb-3">Informasi Akun</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white" required>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password Baru <span class="text-slate-400 font-normal">(kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
                </div>
                <div class="col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600">
                        <span class="text-sm font-medium text-slate-700">Akun Aktif</span>
                    </label>
                </div>
            </div>
        </div>
        <div class="card p-6 space-y-4">
            <h3 class="font-semibold text-slate-800 border-b border-slate-100 pb-3">Informasi Profil</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">NIP</label>
                    <input type="text" name="nip" value="{{ old('nip', optional($user->kreditAnalis)->nip) }}" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Telepon</label>
                    <input type="text" name="telepon" value="{{ old('telepon', optional($user->kreditAnalis)->telepon) }}" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jabatan</label>
                    <input type="text" name="jabatan" value="{{ old('jabatan', optional($user->kreditAnalis)->jabatan) }}" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
                </div>
            </div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl text-white font-semibold text-sm flex items-center gap-2">
                <i class="fa-solid fa-save"></i> Simpan Perubahan
            </button>
            <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-medium text-sm hover:bg-slate-50">Batal</a>
        </div>
    </form>
</div>
@endsection
