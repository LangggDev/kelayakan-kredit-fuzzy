<?php

namespace App\Http\Controllers\KepCab;

use App\Http\Controllers\Controller;
use App\Models\HasilAnalisis;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'menunggu'   => HasilAnalisis::where('status_approval', 'menunggu')->count(),
            'disetujui'  => HasilAnalisis::where('status_approval', 'disetujui')->count(),
            'ditolak'    => HasilAnalisis::where('status_approval', 'ditolak')->count(),
            'total'      => HasilAnalisis::count(),
        ];

        $menungguList = HasilAnalisis::with(['user', 'calonNasabah'])
            ->where('status_approval', 'menunggu')
            ->latest()
            ->take(5)
            ->get();

        $recentApproved = HasilAnalisis::with(['user', 'calonNasabah', 'approvedBy'])
            ->whereIn('status_approval', ['disetujui', 'ditolak'])
            ->where('approved_by', auth()->id())
            ->latest('approved_at')
            ->take(5)
            ->get();

        return view('kepala_cabang.dashboard', compact('stats', 'menungguList', 'recentApproved'));
    }
}
