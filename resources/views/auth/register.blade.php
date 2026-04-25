@extends('layouts.auth')
@section('title', 'Daftar Akun')
@section('content')
<div class="text-center mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Daftar Akun</h2>
    <p class="text-slate-500 text-sm mt-1">Buat akun Kredit Analis baru</p>
</div>

@if($errors->any())
<div class="p-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm mb-5">
    <ul class="space-y-1">
        @foreach($errors->all() as $error)
            <li class="flex items-center gap-2"><i class="fa-solid fa-circle-xmark flex-shrink-0"></i> {{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('register.post') }}" method="POST" class="space-y-4">
    @csrf
    <div class="grid grid-cols-2 gap-4">
        <div class="col-span-2">
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama lengkap"
                class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white" required>
        </div>
        <div class="col-span-2">
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com"
                class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white" required>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password <span class="text-red-500">*</span></label>
            <input type="password" name="password" placeholder="Min 8 karakter"
                class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white" required>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi Password <span class="text-red-500">*</span></label>
            <input type="password" name="password_confirmation" placeholder="Ulangi password"
                class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white" required>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">NIP</label>
            <input type="text" name="nip" value="{{ old('nip') }}" placeholder="Nomor induk pegawai"
                class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Telepon</label>
            <input type="text" name="telepon" value="{{ old('telepon') }}" placeholder="08xxxxxxxxxx"
                class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
        </div>
        <div class="col-span-2">
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jabatan</label>
            <input type="text" name="jabatan" value="{{ old('jabatan') }}" placeholder="e.g. Kredit Analis"
                class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
        </div>
    </div>

    <button type="submit" class="btn-primary w-full py-3 rounded-xl text-white font-semibold text-sm mt-2">
        <i class="fa-solid fa-user-plus mr-2"></i> Buat Akun
    </button>
</form>

<div class="mt-5 text-center">
    <p class="text-sm text-slate-500">Sudah punya akun?
        <a href="{{ route('login') }}" class="text-indigo-600 font-semibold hover:underline">Masuk di sini</a>
    </p>
</div>
@endsection
