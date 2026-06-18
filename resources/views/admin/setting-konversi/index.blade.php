@extends('layouts.app')

@section('title', 'Pengaturan Rumus Konversi Otomatis')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Pengaturan Rumus Konversi Otomatis</h2>
    <p class="text-slate-500 mt-1">Ubah batas rasio untuk menentukan 3 tingkatan (Tidak Layak, Layak, Sangat Layak).</p>
</div>

@if(session('success'))
<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
    <i class="fa-solid fa-circle-check"></i>
    <p class="text-sm font-medium">{{ session('success') }}</p>
</div>
@endif

<form action="{{ route('admin.setting-konversi.update') }}" method="POST">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- CAPACITY --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center text-green-600 font-bold">C2</div>
                <div>
                    <h3 class="font-bold text-slate-800">Capacity</h3>
                    <p class="text-xs text-slate-500">Nilai Mentah (x)</p>
                </div>
            </div>
            
            <div class="space-y-4 font-mono">
                <div class="bg-red-50 p-4 rounded-xl border border-red-100">
                    <p class="text-sm font-sans font-semibold text-red-700 mb-2">Tidak Layak</p>
                    <div class="flex items-center gap-2 text-sm text-slate-700">
                        <span>x &le;</span>
                        <input type="number" name="settings[capacity][batas_tidak_layak]" value="{{ $settings['capacity']->batas_tidak_layak ?? 50 }}" class="w-20 px-2 py-1.5 rounded-lg border border-slate-300 text-center font-sans" step="0.1">
                    </div>
                </div>

                <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-100">
                    <p class="text-sm font-sans font-semibold text-yellow-700 mb-2">Layak</p>
                    <div class="flex items-center gap-2 text-sm text-slate-700">
                        <span>Otomatis di antara nilai tersebut</span>
                    </div>
                </div>

                <div class="bg-green-50 p-4 rounded-xl border border-green-100">
                    <p class="text-sm font-sans font-semibold text-green-700 mb-2">Sangat Layak</p>
                    <div class="flex items-center gap-2 text-sm text-slate-700">
                        <span>x &ge;</span>
                        <input type="number" name="settings[capacity][batas_sangat_layak]" value="{{ $settings['capacity']->batas_sangat_layak ?? 90 }}" class="w-20 px-2 py-1.5 rounded-lg border border-slate-300 text-center font-sans" step="0.1">
                    </div>
                </div>
            </div>
        </div>

        {{-- CAPITAL --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 font-bold">C3</div>
                <div>
                    <h3 class="font-bold text-slate-800">Capital</h3>
                    <p class="text-xs text-slate-500">Nilai Mentah (x)</p>
                </div>
            </div>
            
            <div class="space-y-4 font-mono">
                <div class="bg-red-50 p-4 rounded-xl border border-red-100">
                    <p class="text-sm font-sans font-semibold text-red-700 mb-2">Tidak Layak</p>
                    <div class="flex items-center gap-2 text-sm text-slate-700">
                        <span>x &le;</span>
                        <input type="number" name="settings[capital][batas_tidak_layak]" value="{{ $settings['capital']->batas_tidak_layak ?? 50 }}" class="w-20 px-2 py-1.5 rounded-lg border border-slate-300 text-center font-sans" step="0.1">
                    </div>
                </div>

                <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-100">
                    <p class="text-sm font-sans font-semibold text-yellow-700 mb-2">Layak</p>
                    <div class="flex items-center gap-2 text-sm text-slate-700">
                        <span>Otomatis di antara nilai tersebut</span>
                    </div>
                </div>

                <div class="bg-green-50 p-4 rounded-xl border border-green-100">
                    <p class="text-sm font-sans font-semibold text-green-700 mb-2">Sangat Layak</p>
                    <div class="flex items-center gap-2 text-sm text-slate-700">
                        <span>x &ge;</span>
                        <input type="number" name="settings[capital][batas_sangat_layak]" value="{{ $settings['capital']->batas_sangat_layak ?? 90 }}" class="w-20 px-2 py-1.5 rounded-lg border border-slate-300 text-center font-sans" step="0.1">
                    </div>
                </div>
            </div>
        </div>

        {{-- COLLATERAL --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600 font-bold">C4</div>
                <div>
                    <h3 class="font-bold text-slate-800">Collateral</h3>
                    <p class="text-xs text-slate-500">Nilai Mentah (x)</p>
                </div>
            </div>
            
            <div class="space-y-4 font-mono">
                <div class="bg-red-50 p-4 rounded-xl border border-red-100">
                    <p class="text-sm font-sans font-semibold text-red-700 mb-2">Tidak Layak</p>
                    <div class="flex items-center gap-2 text-sm text-slate-700">
                        <span>x &le;</span>
                        <input type="number" name="settings[collateral][batas_tidak_layak]" value="{{ $settings['collateral']->batas_tidak_layak ?? 50 }}" class="w-20 px-2 py-1.5 rounded-lg border border-slate-300 text-center font-sans" step="0.1">
                    </div>
                </div>

                <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-100">
                    <p class="text-sm font-sans font-semibold text-yellow-700 mb-2">Layak</p>
                    <div class="flex items-center gap-2 text-sm text-slate-700">
                        <span>Otomatis di antara nilai tersebut</span>
                    </div>
                </div>

                <div class="bg-green-50 p-4 rounded-xl border border-green-100">
                    <p class="text-sm font-sans font-semibold text-green-700 mb-2">Sangat Layak</p>
                    <div class="flex items-center gap-2 text-sm text-slate-700">
                        <span>x &ge;</span>
                        <input type="number" name="settings[collateral][batas_sangat_layak]" value="{{ $settings['collateral']->batas_sangat_layak ?? 90 }}" class="w-20 px-2 py-1.5 rounded-lg border border-slate-300 text-center font-sans" step="0.1">
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="mt-8 flex justify-end">
        <button type="submit" class="btn-primary flex items-center gap-2 px-6 py-3 rounded-xl text-white font-semibold shadow-md">
            <i class="fa-solid fa-save"></i> Simpan Konversi
        </button>
    </div>
</form>

@endsection
