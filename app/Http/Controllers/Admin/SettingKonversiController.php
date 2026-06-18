<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SettingKonversi;
use Illuminate\Http\Request;

class SettingKonversiController extends Controller
{
    public function index()
    {
        $settings = SettingKonversi::all()->keyBy('kriteria');
        return view('admin.setting-konversi.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*.batas_sangat_layak' => 'required|numeric',
            'settings.*.batas_tidak_layak' => 'required|numeric',
        ]);

        foreach ($request->settings as $kriteria => $data) {
            SettingKonversi::where('kriteria', $kriteria)->update([
                'batas_sangat_layak' => $data['batas_sangat_layak'],
                'batas_tidak_layak' => $data['batas_tidak_layak'],
            ]);
        }

        return redirect()->route('admin.setting-konversi.index')->with('success', 'Rumus konversi berhasil diperbarui!');
    }
}
