<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\HasilAnalisis;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total'       => HasilAnalisis::count(),
            'layak'       => HasilAnalisis::where('keputusan', 'Layak')->count(),
            'tidak_layak' => HasilAnalisis::where('keputusan', 'Tidak Layak')->count(),
            'disetujui'   => HasilAnalisis::where('status_approval', 'disetujui')->count(),
        ];

        $recent = HasilAnalisis::with(['calonNasabah', 'user', 'approvedBy'])
            ->where('status_approval', 'disetujui')
            ->latest('approved_at')
            ->take(5)
            ->get();

        return view('marketing.dashboard', compact('stats', 'recent'));
    }
}
