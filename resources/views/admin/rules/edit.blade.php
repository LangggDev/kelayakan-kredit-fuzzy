@extends('layouts.app')
@section('title', 'Edit Rule Fuzzy')
@section('page-title', 'Edit Rule Fuzzy #' . $rule->nomor_rule)
@section('page-subtitle', 'Perbarui aturan inferensi fuzzy')

@section('content')
<div class="w-full">
    @if($errors->any())
    <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm mb-5">
        <ul class="space-y-1">@foreach($errors->all() as $e)<li class="flex gap-2"><i class="fa-solid fa-circle-xmark mt-0.5 flex-shrink-0"></i>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('admin.rules.update', $rule->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="card p-6 space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                <h3 class="font-semibold text-slate-800">Edit Rule</h3>
                <span class="text-xs px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 font-semibold">Rule #{{ $rule->nomor_rule }}</span>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor Rule</label>
                <input type="number" name="nomor_rule" value="{{ old('nomor_rule', $rule->nomor_rule) }}"
                    class="w-full max-w-xs px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white" required>
            </div>

            <div>
                <p class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded bg-indigo-100 text-indigo-700 text-xs font-bold">IF</span>
                    Kondisi Anteseden
                </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @php
                    $options = [
                        'character'  => ['baik', 'cukup', 'buruk', 'any'],
                        'capacity'   => ['sangat layak', 'layak', 'tidak layak', 'any'],
                        'capital'    => ['sangat layak', 'layak', 'tidak layak', 'any'],
                        'collateral' => ['sangat layak', 'layak', 'tidak layak', 'any'],
                        'condition'  => ['sangat layak', 'layak', 'tidak layak', 'any'],
                    ];
                    $labels = ['character'=>'C1 — Character','capacity'=>'C2 — Capacity','capital'=>'C3 — Capital','collateral'=>'C4 — Collateral','condition'=>'C5 — Condition'];
                    $current = ['character'=>$rule->character,'capacity'=>$rule->capacity,'capital'=>$rule->capital,'collateral'=>$rule->collateral,'condition'=>$rule->condition];
                    @endphp
                    @foreach($options as $field => $opts)
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wider">{{ $labels[$field] }}</label>
                        <select name="{{ $field }}" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white" required>
                            @foreach($opts as $opt)
                            <option value="{{ $opt }}" {{ old($field, $current[$field])===$opt?'selected':'' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <p class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded bg-green-100 text-green-700 text-xs font-bold">THEN</span>
                    Konsekuen
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wider">Kelayakan</label>
                        <select name="kelayakan" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white" required>
                            <option value="layak"       {{ old('kelayakan',$rule->kelayakan)==='layak'       ?'selected':'' }}>Layak</option>
                            <option value="tidak_layak" {{ old('kelayakan',$rule->kelayakan)==='tidak_layak' ?'selected':'' }}>Tidak Layak</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wider">Tipe Output</label>
                        <select name="output_tipe" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white" required>
                            <option value="linear_naik"  {{ old('output_tipe',$rule->output_tipe)==='linear_naik' ?'selected':'' }}>Linear Naik</option>
                            <option value="linear_turun" {{ old('output_tipe',$rule->output_tipe)==='linear_turun'?'selected':'' }}>Linear Turun</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wider">Output a</label>
                            <input type="number" name="output_a" value="{{ old('output_a', $rule->output_a) }}" step="any"
                                class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white" required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wider">Output b</label>
                            <input type="number" name="output_b" value="{{ old('output_b', $rule->output_b) }}" step="any"
                                class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white" required>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi Rule</label>
                <input type="text" name="deskripsi" value="{{ old('deskripsi', $rule->deskripsi) }}"
                    class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" id="isActive" {{ old('is_active', $rule->is_active) ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600">
                <label for="isActive" class="text-sm font-medium text-slate-700 cursor-pointer">Rule Aktif</label>
            </div>
        </div>

        <div class="flex gap-3 mt-5">
            <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl text-white font-semibold text-sm flex items-center gap-2">
                <i class="fa-solid fa-save"></i> Simpan Perubahan
            </button>
            <a href="{{ route('admin.rules.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-medium text-sm hover:bg-slate-50">Batal</a>
        </div>
    </form>
</div>
@endsection
