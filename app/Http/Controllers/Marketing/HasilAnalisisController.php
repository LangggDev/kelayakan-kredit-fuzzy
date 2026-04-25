<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\HasilAnalisis;
use Illuminate\Http\Request;

class HasilAnalisisController extends Controller
{
    public function index(Request $request)
    {
        $query = HasilAnalisis::with(['user', 'calonNasabah', 'approvedBy'])
            ->where('status_approval', 'disetujui')
            ->latest();

        if ($request->filled('search')) {
            $query->whereHas('calonNasabah', function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                  ->orWhere('nik',  'like', "%{$request->search}%");
            });
        }

        if ($request->filled('keputusan')) {
            $query->where('keputusan', $request->keputusan);
        }

        $dataAnalisis = $query->paginate(10)->withQueryString();

        return view('marketing.analisis.index', compact('dataAnalisis'));
    }

    public function show(HasilAnalisis $analisis)
    {
        // Hanya bisa lihat yang sudah disetujui
        if ($analisis->status_approval !== 'disetujui') {
            abort(403, 'Data ini belum disetujui oleh Kepala Cabang.');
        }
        $analisis->load(['user', 'calonNasabah', 'approvedBy']);
        return view('marketing.analisis.show', compact('analisis'));
    }
}
