@extends('layouts.app')
@section('title', 'Tambah Rule 5C')
@section('page-title', 'Tambah Rule Fuzzy 5C')
@section('page-subtitle', 'Buat aturan IF-THEN baru berdasarkan kriteria 5C')

@section('content')
    <div class="w-full">
        @if($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm mb-5">
                <ul class="space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('admin.rules.store') }}" method="POST">
            @csrf
            <div class="card p-6 space-y-5">
                <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                    <h3 class="font-semibold text-slate-800">Konfigurasi Rule</h3>
                    <span class="text-xs px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 font-semibold">Rule
                        #{{ $nextNo }}</span>
                </div>
                <input type="hidden" name="nomor_rule" value="{{ $nextNo }}">

                <div>
                    <p class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded bg-indigo-100 text-indigo-700 text-xs font-bold">IF</span>
                        Kondisi Anteseden (5C)
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @php
                            $ante = [
                                'character' => ['C1 — Character', ['baik', 'cukup', 'buruk'], 'blue'],
                                'capacity' => ['C2 — Capacity', ['sangat layak', 'layak', 'tidak layak'], 'green'],
                                'capital' => ['C3 — Capital', ['sangat layak', 'layak', 'tidak layak'], 'amber'],
                                'collateral' => ['C4 — Collateral', ['sangat layak', 'layak', 'tidak layak'], 'purple'],
                                'condition' => ['C5 — Condition', ['sangat layak', 'layak', 'tidak layak'], 'rose'],
                            ];
                            $borderMap = ['blue' => 'border-blue-200 focus:border-blue-400', 'green' => 'border-green-200 focus:border-green-400', 'amber' => 'border-amber-200 focus:border-amber-400', 'purple' => 'border-purple-200 focus:border-purple-400', 'rose' => 'border-rose-200 focus:border-rose-400'];
                        @endphp
                        @foreach($ante as $field => [$label, $opts, $color])
                            <div>
                                <label
                                    class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wider">{{ $label }}</label>
                                <select name="{{ $field }}"
                                    class="w-full px-3 py-2.5 border {{ $borderMap[$color] }} rounded-xl text-sm bg-slate-50 capitalize"
                                    required>
                                    @foreach($opts as $opt)
                                        <option value="{{ $opt }}" {{ old($field) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <p class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded bg-green-100 text-green-700 text-xs font-bold">THEN</span>
                        Konsekuen (Hasil)
                    </p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase">Kelayakan</label>
                            <select name="kelayakan"
                                class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50" required>
                                <option value="layak" {{ old('kelayakan') === 'layak' ? 'selected' : '' }}>Layak</option>
                                <option value="tidak_layak" {{ old('kelayakan') === 'tidak_layak' ? 'selected' : '' }}>Tidak Layak
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase">Tipe Output</label>
                            <select name="output_tipe"
                                class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50" required>
                                <option value="linear_naik" {{ old('output_tipe') === 'linear_naik' ? 'selected' : '' }}>Linear
                                    Naik (Layak)</option>
                                <option value="linear_turun" {{ old('output_tipe') === 'linear_turun' ? 'selected' : '' }}>Linear
                                    Turun (Tdk Layak)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase">Output a</label>
                            <input type="number" name="output_a" value="{{ old('output_a', 0) }}" step="any"
                                class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50" required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase">Output b</label>
                            <input type="number" name="output_b" value="{{ old('output_b', 100) }}" step="any"
                                class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50" required>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi Rule</label>
                    <input type="text" name="deskripsi" value="{{ old('deskripsi') }}"
                        placeholder="Deskripsi singkat rule..."
                        class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" id="isActive" {{ old('is_active', true) ? 'checked' : '' }}
                        class="rounded border-slate-300 text-indigo-600">
                    <label for="isActive" class="text-sm font-medium text-slate-700 cursor-pointer">Rule Aktif</label>
                </div>
            </div>
            <div class="flex gap-3 mt-5">
                <button type="submit"
                    class="btn-primary px-6 py-2.5 rounded-xl text-white font-semibold text-sm flex items-center gap-2">
                    <i class="fa-solid fa-save"></i> Simpan Rule
                </button>
                <a href="{{ route('admin.rules.index') }}"
                    class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-medium text-sm hover:bg-slate-50">Batal</a>
            </div>
        </form>
    </div>
@endsection