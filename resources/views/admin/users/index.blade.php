@extends('layouts.app')
@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('content')

    @php
        $roleConfig = [
            'analis' => ['label' => 'Kredit Analis', 'color' => 'bg-blue-100 text-blue-700', 'icon' => 'fa-user-pen'],
            'kepala_cabang' => ['label' => 'Kepala Cabang', 'color' => 'bg-amber-100 text-amber-700', 'icon' => 'fa-user-tie'],
            'marketing' => ['label' => 'Marketing', 'color' => 'bg-green-100 text-green-700', 'icon' => 'fa-bullhorn'],
        ];
    @endphp

    <div class="card p-4">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 flex-wrap">
            <div class="relative flex-1 min-w-48">
                <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                    class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
            </div>
            <select name="role" class="px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50">
                <option value="">Semua Role</option>
                <option value="analis" {{ request('role') === 'analis' ? 'selected' : '' }}>Credit Analyst</option>
                <option value="kepala_cabang" {{ request('role') === 'kepala_cabang' ? 'selected' : '' }}>Branch Manager
                </option>
                <option value="marketing" {{ request('role') === 'marketing' ? 'selected' : '' }}>Marketing Officer</option>
            </select>
            <button type="submit"
                class="btn-primary px-5 py-2.5 rounded-xl text-white font-semibold text-sm flex items-center gap-2">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
            @if(request()->anyFilled(['search', 'role']))
                <a href="{{ route('admin.users.index') }}"
                    class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-medium text-sm hover:bg-slate-50 flex items-center gap-2">
                    <i class="fa-solid fa-xmark"></i> Reset
                </a>
            @endif
            <a href="{{ route('admin.users.create') }}"
                class="btn-primary px-5 py-2.5 rounded-xl text-white font-semibold text-sm flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Tambah Pengguna
            </a>
        </form>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100" style="background:#f4f6fb">
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase">No</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase">Pengguna</th>
                        <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase">Role</th>
                        <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase">NIK</th>
                        <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase">Info Tambahan</th>
                        <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase">Telepon</th>
                        <th class="text-center px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase">Status</th>
                        <th class="px-5 py-3.5"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($users as $user)
                        @php $rc = $roleConfig[$user->role] ?? ['label' => $user->role, 'color' => 'bg-slate-100 text-slate-500', 'icon' => 'fa-user']; @endphp
                        <tr class="table-row">
                            <td class="px-5 py-4 text-slate-400 text-xs">{{ $users->firstItem() + $loop->index }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                                        style="background:#1a2e5a">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-800">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $rc['color'] }}">
                                    <i class="fa-solid {{ $rc['icon'] }} text-xs"></i>
                                    {{ $rc['label'] }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-xs font-mono text-slate-600">
                                @if($user->role === 'analis')
                                    {{ $user->kreditAnalis?->nip ?? '—' }}
                                @elseif($user->role === 'kepala_cabang')
                                    {{ $user->kepalaKabang?->nip ?? '—' }}
                                @elseif($user->role === 'marketing')
                                    {{ $user->marketingStaff?->nip ?? '—' }}
                                @endif
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-500">
                                @if($user->role === 'analis')
                                    {{ $user->kreditAnalis?->jabatan ?? '—' }}
                                @elseif($user->role === 'kepala_cabang')
                                    Cabang: {{ $user->kepalaKabang?->cabang ?? '—' }}
                                @elseif($user->role === 'marketing')
                                    Area: {{ $user->marketingStaff?->area ?? '—' }}
                                @endif
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-500">
                                @if($user->role === 'analis') {{ $user->kreditAnalis?->telepon ?? '—' }}
                                @elseif($user->role === 'kepala_cabang') {{ $user->kepalaKabang?->telepon ?? '—' }}
                                @elseif($user->role === 'marketing') {{ $user->marketingStaff?->telepon ?? '—' }}
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold transition-colors
                                                {{ $user->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-600 hover:bg-red-200' }}">
                                        <span
                                            class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-green-600' : 'bg-red-500' }}"></span>
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center gap-2 justify-end">
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                        class="p-1.5 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 transition-colors">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus akun {{ $user->name }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-14 text-center text-slate-400">
                                <i class="fa-solid fa-users text-2xl mb-2 block"></i>
                                Belum ada pengguna terdaftar
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-5 py-4 border-t border-slate-50">{{ $users->links() }}</div>
        @endif
    </div>
@endsection