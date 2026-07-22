@extends('layouts.app')
@section('title', 'Detail Pengguna')
@section('page-title', 'Detail Pengguna')
@section('page-subtitle', 'Informasi lengkap akun pengguna')

@section('content')
    @php
        $roleConfig = [
            'analis' => ['label' => 'Kredit Analis', 'color' => 'bg-blue-100 text-blue-700', 'icon' => 'fa-user-pen'],
            'kepala_cabang' => ['label' => 'Kepala Cabang', 'color' => 'bg-amber-100 text-amber-700', 'icon' => 'fa-user-tie'],
            'marketing' => ['label' => 'Marketing', 'color' => 'bg-green-100 text-green-700', 'icon' => 'fa-bullhorn'],
        ];
        $rc = $roleConfig[$user->role] ?? ['label' => $user->role, 'color' => 'bg-slate-100 text-slate-500', 'icon' => 'fa-user'];
    @endphp

    <div class="card p-6">
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full flex items-center justify-center text-white font-bold text-2xl flex-shrink-0"
                    style="background:#1a2e5a">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-800">{{ $user->name }}</h2>
                    <p class="text-sm text-slate-500">{{ $user->email ?? 'Tidak ada email' }}</p>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold mt-2 {{ $rc['color'] }}">
                        <i class="fa-solid {{ $rc['icon'] }} text-xs"></i>
                        {{ $rc['label'] }}
                    </span>
                </div>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-green-600' : 'bg-red-500' }}"></span>
                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <div class="space-y-4">
                <h3 class="font-bold text-slate-800 border-b border-slate-100 pb-3 text-sm">Informasi Akun</h3>
                
                <div>
                    <div class="text-xs text-slate-400 mb-1">NIK</div>
                    <div class="font-mono font-semibold text-slate-700">{{ $user->nik }}</div>
                </div>
                
                <div>
                    <div class="text-xs text-slate-400 mb-1">Terdaftar pada</div>
                    <div class="text-sm font-semibold text-slate-700">{{ $user->created_at->format('d M Y H:i') }}</div>
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="font-bold text-slate-800 border-b border-slate-100 pb-3 text-sm">Informasi Profil</h3>
                
                <div>
                    <div class="text-xs text-slate-400 mb-1">NIP</div>
                    <div class="font-mono font-semibold text-slate-700">
                        @if($user->role === 'analis')
                            {{ $user->kreditAnalis?->nip ?? '—' }}
                        @elseif($user->role === 'kepala_cabang')
                            {{ $user->kepalaCabang?->nip ?? '—' }}
                        @elseif($user->role === 'marketing')
                            {{ $user->marketingStaff?->nip ?? '—' }}
                        @else
                            —
                        @endif
                    </div>
                </div>

                <div>
                    <div class="text-xs text-slate-400 mb-1">Telepon</div>
                    <div class="text-sm font-semibold text-slate-700">
                        @if($user->role === 'analis') {{ $user->kreditAnalis?->telepon ?? '—' }}
                        @elseif($user->role === 'kepala_cabang') {{ $user->kepalaCabang?->telepon ?? '—' }}
                        @elseif($user->role === 'marketing') {{ $user->marketingStaff?->telepon ?? '—' }}
                        @else —
                        @endif
                    </div>
                </div>

                <div>
                    <div class="text-xs text-slate-400 mb-1">
                        @if($user->role === 'analis') Jabatan
                        @elseif($user->role === 'kepala_cabang') Cabang
                        @elseif($user->role === 'marketing') Area
                        @else Info Tambahan
                        @endif
                    </div>
                    <div class="text-sm font-semibold text-slate-700">
                        @if($user->role === 'analis')
                            {{ $user->kreditAnalis?->jabatan ?? '—' }}
                        @elseif($user->role === 'kepala_cabang')
                            {{ $user->kepalaCabang?->cabang ?? '—' }}
                        @elseif($user->role === 'marketing')
                            {{ $user->marketingStaff?->area ?? '—' }}
                        @else
                            —
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex gap-3 border-t border-slate-100 pt-6">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-primary px-6 py-2.5 rounded-xl text-white font-bold text-sm flex items-center gap-2">
                <i class="fa-solid fa-pen"></i> Edit Pengguna
            </a>
            <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-medium text-sm hover:bg-slate-50">
                Kembali
            </a>
        </div>
    </div>
@endsection
