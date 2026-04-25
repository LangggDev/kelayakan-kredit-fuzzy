<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\HasilAnalisis;
use App\Models\CalonNasabah;
use App\Models\RuleFuzzy;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_analis'   => User::where('role', 'analis')->count(),
            'total_analisis' => HasilAnalisis::count(),
            'total_layak'    => HasilAnalisis::where('keputusan', 'Layak')->count(),
            'total_tidak_layak' => HasilAnalisis::where('keputusan', 'Tidak Layak')->count(),
            'total_nasabah'  => CalonNasabah::count(),
            'total_rule'     => RuleFuzzy::where('is_active', true)->count(),
        ];

        $recentAnalisis = HasilAnalisis::with(['user', 'calonNasabah'])
            ->latest()
            ->take(8)
            ->get();

        // Data chart per bulan (6 bulan terakhir)
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $chartData[] = [
                'label' => $month->format('M Y'),
                'layak' => HasilAnalisis::where('keputusan', 'Layak')
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
                'tidak_layak' => HasilAnalisis::where('keputusan', 'Tidak Layak')
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
            ];
        }

        return view('admin.dashboard', compact('stats', 'recentAnalisis', 'chartData'));
    }
}
