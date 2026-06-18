@extends('layouts.auth')
@section('title', 'Login')

@section('content')

    <div class="text-center mb-6">
        <h2 class="text-xl font-bold text-slate-800">Selamat Datang</h2>
        <p class="text-slate-500 text-sm mt-1">Masuk menggunakan NIK dan password Anda</p>
    </div>

    @if($errors->any())
        <div class="flex flex-col gap-1 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm mb-5">
            @foreach($errors->all() as $error)
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-xmark flex-shrink-0"></i>
                    <span>{{ $error }}</span>
                </div>
            @endforeach
        </div>
    @endif

    @if(session('success'))
        <div class="flex items-center gap-2 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm mb-5">
            <i class="fa-solid fa-circle-check flex-shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
        @csrf

        {{-- NIK --}}
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                NIK <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <i class="fa-solid fa-id-card absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="nik" value="{{ old('nik') }}" placeholder="Masukkan NIK Anda" maxlength="20" class="w-full pl-10 pr-4 py-2.5 border rounded-xl text-sm bg-slate-50 focus:bg-white transition-colors
                        {{ $errors->has('nik') ? 'border-red-300 bg-red-50' : 'border-slate-200' }}" required autofocus
                    autocomplete="username">
            </div>
            @error('nik')
                <p class="text-xs text-red-600 mt-1 flex items-center gap-1">
                    <i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                Password <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="password" name="password" id="pwdInput" placeholder="••••••••" class="w-full pl-10 pr-10 py-2.5 border rounded-xl text-sm bg-slate-50 focus:bg-white transition-colors
                        {{ $errors->has('password') ? 'border-red-300 bg-red-50' : 'border-slate-200' }}" required
                    autocomplete="current-password">
                <button type="button" onclick="togglePwd()"
                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-eye text-sm" id="eyeIco"></i>
                </button>
            </div>
            @error('password')
                <p class="text-xs text-red-600 mt-1 flex items-center gap-1">
                    <i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Remember --}}
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer select-none">
                <input type="checkbox" name="remember" class="rounded border-slate-300 w-4 h-4"
                    style="accent-color:#1a2e5a">
                <span>Ingat saya</span>
            </label>
        </div>

        {{-- Submit --}}
        <button type="submit"
            class="btn-primary w-full py-3 rounded-xl text-white font-bold text-sm flex items-center justify-center gap-2">
            <i class="fa-solid fa-right-to-bracket"></i>
            Masuk
        </button>
    </form>

    {{-- Register link --}}
    {{-- <div class="mt-5 text-center">
        <p class="text-sm text-slate-500">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-semibold hover:underline" style="color:#1a2e5a">
                Daftar sebagai Kredit Analis
            </a>
        </p>
    </div> --}}

    {{-- Demo accounts --}}
    <div class="mt-5 pt-5 border-t border-slate-100">
        <p class="text-xs text-slate-400 text-center mb-3 font-semibold uppercase tracking-wider">Demo Akun</p>
        <div class="grid grid-cols-2 gap-2">
            <button onclick="fillLogin('3171021508900001','admin123')"
                class="p-2.5 rounded-xl text-left hover:opacity-90 transition-opacity border"
                style="background:#eef1f8; border-color:#d5ddef">
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-5 h-5 rounded flex items-center justify-center text-white text-xs font-bold"
                        style="background:#1a2e5a">A</span>
                    <span class="text-xs font-bold" style="color:#1a2e5a">Administrator</span>
                </div>
                <div class="text-xs text-slate-500">NIK: <span class="font-mono font-semibold">3171021508900001</span></div>
                <div class="text-xs text-slate-400">Pass: admin123</div>
            </button>

            <button onclick="fillLogin('3273016005850002','analis123')"
                class="p-2.5 rounded-xl text-left hover:opacity-90 transition-opacity border"
                style="background:#dbeafe; border-color:#bfdbfe">
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-5 h-5 rounded flex items-center justify-center text-white text-xs font-bold"
                        style="background:#2563eb">KA</span>
                    <span class="text-xs font-bold text-blue-700">Kredit Analis</span>
                </div>
                <div class="text-xs text-slate-500">NIK: <span class="font-mono font-semibold">3273016005850002</span></div>
                <div class="text-xs text-slate-400">Pass: analis123</div>
            </button>

            <button onclick="fillLogin('3301010101000003','kc12345678')"
                class="p-2.5 rounded-xl text-left hover:opacity-90 transition-opacity border"
                style="background:#fef9ee; border-color:#fde68a">
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-5 h-5 rounded flex items-center justify-center text-white text-xs font-bold"
                        style="background:#d97706">KC</span>
                    <span class="text-xs font-bold text-amber-700">Kepala Cabang</span>
                </div>
                <div class="text-xs text-slate-500">NIK: <span class="font-mono font-semibold">3301010101000003</span></div>
                <div class="text-xs text-slate-400">Pass: kc12345678</div>
            </button>

            <button onclick="fillLogin('3578015012950004','mkt12345678')"
                class="p-2.5 rounded-xl text-left hover:opacity-90 transition-opacity border"
                style="background:#f0fdf4; border-color:#bbf7d0">
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-5 h-5 rounded flex items-center justify-center text-white text-xs font-bold"
                        style="background:#16a34a">MK</span>
                    <span class="text-xs font-bold text-green-700">Marketing</span>
                </div>
                <div class="text-xs text-slate-500">NIK: <span class="font-mono font-semibold">3578015012950004</span></div>
                <div class="text-xs text-slate-400">Pass: mkt12345678</div>
            </button>
        </div>
    </div>

    <script>
        function togglePwd() {
            const i = document.getElementById('pwdInput');
            const e = document.getElementById('eyeIco');
            i.type = i.type === 'password' ? 'text' : 'password';
            e.className = 'fa-solid ' + (i.type === 'password' ? 'fa-eye' : 'fa-eye-slash') + ' text-sm';
        }
        function fillLogin(nik, pwd) {
            document.querySelector('input[name="nik"]').value = nik;
            document.getElementById('pwdInput').value = pwd;
            document.querySelector('input[name="nik"]').focus();
        }
    </script>

@endsection