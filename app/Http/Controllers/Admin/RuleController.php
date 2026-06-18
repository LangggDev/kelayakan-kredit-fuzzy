<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RuleFuzzy;
use Illuminate\Http\Request;

class RuleController extends Controller
{
    public function index(Request $request)
    {
        $query = RuleFuzzy::orderBy('nomor_rule');
        if ($request->search) {
            $query->where('deskripsi', 'like', "%{$request->search}%");
        }
        if ($request->kelayakan) {
            $query->where('kelayakan', $request->kelayakan);
        }
        $rules = $query->paginate(15)->withQueryString();
        return view('admin.rules.index', compact('rules'));
    }

    public function create()
    {
        $nextNo = (RuleFuzzy::max('nomor_rule') ?? 0) + 1;
        return view('admin.rules.create', compact('nextNo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_rule'  => 'required|integer|unique:rule_fuzzy,nomor_rule',
            'character'   => 'required|in:baik,cukup,buruk,any',
            'capacity'    => 'required|in:sangat layak,layak,tidak layak,any',
            'capital'     => 'required|in:sangat layak,layak,tidak layak,any',
            'collateral'  => 'required|in:sangat layak,layak,tidak layak,any',
            'condition'   => 'required|in:sangat layak,layak,tidak layak,any',
            'kelayakan'   => 'required|in:layak,tidak_layak',
            'output_tipe' => 'required|in:linear_naik,linear_turun',
            'output_a'    => 'required|numeric',
            'output_b'    => 'required|numeric',
            'deskripsi'   => 'nullable|string',
        ]);

        RuleFuzzy::create($request->all());
        return redirect()->route('admin.rules.index')->with('success', 'Rule fuzzy berhasil ditambahkan.');
    }

    public function edit(RuleFuzzy $rule)
    {
        return view('admin.rules.edit', compact('rule'));
    }

    public function update(Request $request, RuleFuzzy $rule)
    {
        $request->validate([
            'nomor_rule'  => 'required|integer|unique:rule_fuzzy,nomor_rule,' . $rule->id,
            'character'   => 'required|in:baik,cukup,buruk,any',
            'capacity'    => 'required|in:sangat layak,layak,tidak layak,any',
            'capital'     => 'required|in:sangat layak,layak,tidak layak,any',
            'collateral'  => 'required|in:sangat layak,layak,tidak layak,any',
            'condition'   => 'required|in:sangat layak,layak,tidak layak,any',
            'kelayakan'   => 'required|in:layak,tidak_layak',
            'output_tipe' => 'required|in:linear_naik,linear_turun',
            'output_a'    => 'required|numeric',
            'output_b'    => 'required|numeric',
        ]);

        $rule->update($request->all());
        return redirect()->route('admin.rules.index')->with('success', 'Rule fuzzy berhasil diperbarui.');
    }

    public function destroy(RuleFuzzy $rule)
    {
        $rule->delete();
        return redirect()->route('admin.rules.index')->with('success', 'Rule fuzzy berhasil dihapus.');
    }
}
