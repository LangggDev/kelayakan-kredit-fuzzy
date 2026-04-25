<?php

namespace App\Http\Controllers\KepCab;

use App\Http\Controllers\Controller;
use App\Models\HasilAnalisis;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index(Request $request)
    {
        $query = HasilAnalisis::with(['user', 'calonNasabah', 'approvedBy'])->latest();

        if ($request->filled('status')) {
            $query->where('status_approval', $request->status);
        }

        if ($request->filled('search')) {
            $query->whereHas('calonNasabah', function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                  ->orWhere('nik', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('keputusan')) {
            $query->where('keputusan', $request->keputusan);
        }

        $approvals = $query->paginate(10)->withQueryString();
        return view('kepala_cabang.approval.index', compact('approvals'));
    }

    public function show(HasilAnalisis $approval)
    {
        $approval->load(['user', 'calonNasabah', 'approvedBy']);
        return view('kepala_cabang.approval.show', compact('approval'));
    }

    public function approve(Request $request, HasilAnalisis $approval)
    {
        $request->validate([
            'catatan_approval' => 'nullable|string|max:500',
        ]);

        $approval->update([
            'status_approval'  => 'disetujui',
            'approved_by'      => auth()->user()->id,
            'approved_at'      => now(),
            'catatan_approval' => $request->catatan_approval,
        ]);

        return redirect()->route('kepala_cabang.approval.index')
            ->with('success', "Analisis atas nama {$approval->calonNasabah->nama} telah DISETUJUI.");
    }

    public function reject(Request $request, HasilAnalisis $approval)
    {
        $request->validate([
            'catatan_approval' => 'required|string|max:500',
        ], [
            'catatan_approval.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $approval->update([
            'status_approval'  => 'ditolak',
            'approved_by'      => auth()->user()->id,
            'approved_at'      => now(),
            'catatan_approval' => $request->catatan_approval,
        ]);

        return redirect()->route('kepala_cabang.approval.index')
            ->with('success', "Analisis atas nama {$approval->calonNasabah->nama} telah DITOLAK.");
    }

    public function resetApproval(HasilAnalisis $approval)
    {
        $approval->update([
            'status_approval'  => 'menunggu',
            'approved_by'      => null,
            'approved_at'      => null,
            'catatan_approval' => null,
        ]);

        return back()->with('success', 'Status approval berhasil direset ke Menunggu.');
    }
}
