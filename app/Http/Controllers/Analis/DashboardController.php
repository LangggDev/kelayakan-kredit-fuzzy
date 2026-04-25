<?php

namespace App\Http\Controllers\Analis;

use App\Http\Controllers\Controller;
use App\Models\HasilAnalisis;
use App\Models\CalonNasabah;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $stats = [
            'total_analisis'   => HasilAnalisis::where('user_id', $userId)->count(),
            'total_layak'      => HasilAnalisis::where('user_id', $userId)->where('keputusan', 'Layak')->count(),
            'total_tidak_layak'=> HasilAnalisis::where('user_id', $userId)->where('keputusan', 'Tidak Layak')->count(),
            'total_nasabah'    => CalonNasabah::count(),
        ];

        $recentAnalisis = HasilAnalisis::with('calonNasabah')
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('analis.dashboard', compact('stats', 'recentAnalisis'));
    }
}
