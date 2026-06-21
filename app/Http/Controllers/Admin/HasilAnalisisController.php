<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilAnalisis;
use Illuminate\Http\Request;

class HasilAnalisisController extends Controller
{
    public function index(Request $request)
    {
        $query = HasilAnalisis::with(['user', 'calonNasabah'])->latest();

        if ($request->search) {
            $query->whereHas('calonNasabah', function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                  ->orWhere('nik',  'like', "%{$request->search}%");
            });
        }

        if ($request->keputusan) {
            $query->where('keputusan', $request->keputusan);
        }

        if ($request->analis) {
            $query->where('user_id', $request->analis);
        }

        $hasilAnalisis = $query->paginate(10)->withQueryString();

        $analisList = \App\Models\User::where('role', 'analis')->orderBy('name')->get();

        return view('admin.analisis.index', compact('hasilAnalisis', 'analisList'));
    }

    public function show(HasilAnalisis $analisis)
    {
        $hasilAnalisis = $analisis;
        $hasilAnalisis->load(['user', 'calonNasabah']);
        return view('admin.analisis.show', compact('hasilAnalisis'));
    }

    public function exportPdf(HasilAnalisis $analisis)
    {
        $hasilAnalisis = $analisis;
        $hasilAnalisis->load(['calonNasabah', 'user']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.analisis.pdf', compact('hasilAnalisis'))
            ->setPaper('a4', 'portrait');
        $filename = 'analisis-5c-' . $hasilAnalisis->calonNasabah->nik . '-' . $hasilAnalisis->created_at->format('Ymd') . '.pdf';
        return $pdf->download($filename);
    }

    public function destroy(HasilAnalisis $analisis)
    {
        $hasilAnalisis = $analisis;
        $hasilAnalisis->delete();
        return redirect()->route('admin.analisis.index')->with('success', 'Data analisis berhasil dihapus.');
    }

    public function destroyBulk(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        HasilAnalisis::whereIn('id', $request->ids)->delete();
        return back()->with('success', count($request->ids) . ' data analisis berhasil dihapus.');
    }
}
