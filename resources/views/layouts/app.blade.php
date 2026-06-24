<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Kelayakan Kredit') &mdash; Mandiri Utama Finance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'], mono: ['JetBrains Mono', 'monospace'] },
                    colors: {
                        navy: { 50: '#eef1f8', 100: '#d5ddef', 200: '#adbade', 300: '#7e92c8', 400: '#5670b5', 500: '#3a539e', 600: '#2d4190', 700: '#1a2e5a', 800: '#142348', 900: '#0e1830' },
                        gold: { 50: '#fef9ee', 100: '#fdf0ce', 200: '#fae09a', 300: '#f7ca5e', 400: '#f5b830', 500: '#f5a623', 600: '#e08a0d', 700: '#b96c0d', 800: '#965211', 900: '#7c4412' },
                        mred: { 50: '#fdf2f2', 100: '#fce4e4', 200: '#f9c5c5', 300: '#f29898', 400: '#e85e5e', 500: '#d63333', 600: '#b92222', 700: '#8b1a1a', 800: '#6e1515', 900: '#531010' },
                    }
                }
            }
        }
    </script>
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background: #f4f6fb;
        }

        /* Sidebar */
        .sidebar {
            background: linear-gradient(180deg, #1a2e5a 0%, #142348 100%);
            transition: transform 0.3s ease;
        }

        .sidebar-link {
            border-radius: 10px;
            transition: all 0.2s;
            color: rgba(255, 255, 255, 0.65);
        }

        .sidebar-link:hover {
            background: rgba(245, 166, 35, 0.15);
            color: white;
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, #f5a623 0%, #e08a0d 100%);
            color: #1a2e5a !important;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(245, 166, 35, 0.4);
        }

        .sidebar-link.active i {
            color: #1a2e5a !important;
        }

        .sidebar-section {
            color: rgba(245, 166, 35, 0.7);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 0 12px;
            margin-top: 16px;
            margin-bottom: 4px;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 1px 4px rgba(26, 46, 90, 0.06), 0 1px 2px rgba(26, 46, 90, 0.04);
            border: 1px solid #edf0f7;
        }

        .stat-card {
            background: white;
            border-radius: 14px;
            border: 1px solid #edf0f7;
            box-shadow: 0 1px 4px rgba(26, 46, 90, 0.06);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(26, 46, 90, 0.1);
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #1a2e5a 0%, #2d4190 100%);
            transition: all 0.2s;
        }

        .btn-primary:hover {
            box-shadow: 0 4px 15px rgba(26, 46, 90, 0.35);
            transform: translateY(-1px);
        }

        .btn-gold {
            background: linear-gradient(135deg, #f5a623 0%, #e08a0d 100%);
            transition: all 0.2s;
        }

        .btn-gold:hover {
            box-shadow: 0 4px 15px rgba(245, 166, 35, 0.4);
            transform: translateY(-1px);
        }

        /* Badges */
        .badge-layak {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-tidak-layak {
            background: #fee2e2;
            color: #dc2626;
        }

        .badge-menunggu {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .badge-disetujui {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .badge-ditolak {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        /* Form */
        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #1a2e5a;
            box-shadow: 0 0 0 3px rgba(26, 46, 90, 0.12);
        }

        /* Table */
        .table-row:hover {
            background: #f8f9fd;
        }

        /* Misc */
        .progress-bar {
            height: 8px;
            border-radius: 99px;
            background: #e2e8f0;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 99px;
            background: linear-gradient(90deg, #1a2e5a, #f5a623);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide {
            animation: slideIn 0.35s ease;
        }

        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #c8d0e7;
            border-radius: 3px;
        }

        .mobile-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            z-index: 30;
        }
    </style>
    @stack('styles')
</head>

<body class="h-full">
    <div class="flex h-screen overflow-hidden">
        <div class="mobile-overlay" id="mobileOverlay" onclick="closeSidebar()"></div>

        {{-- SIDEBAR --}}
        <aside
            class="sidebar fixed inset-y-0 left-0 z-40 w-64 flex flex-col lg:relative lg:translate-x-0 -translate-x-full shadow-2xl"
            id="sidebar">

            {{-- Logo --}}
            <div class="flex items-center gap-3 px-5 py-4 border-b border-white/10">
                <div class="w-14 h-8 p-1 rounded-lg bg-white flex items-center justify-center flex-shrink-0">
                    <img src="{{ asset('assets/logo.png') }}" alt="Mandiri Utama Finance">
                </div>
                <div>
                    <div class="font-bold text-white text-sm leading-tight">Mandiri Utama Finance</div>
                    <div class="text-xs text-gold-400">Sistem Kelayakan Kredit</div>
                </div>
                <button onclick="closeSidebar()" class="ml-auto lg:hidden text-white/50 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- User info --}}
            <div class="px-4 py-3 border-b border-white/10">
                <div class="flex items-center gap-3 p-2.5 rounded-xl bg-white/8"
                    style="background:rgba(255,255,255,0.08)">
                    <div
                        class="w-9 h-9 rounded-full bg-gold-500 flex items-center justify-center text-navy-800 font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <div class="font-semibold text-white text-sm truncate">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-gold-400">{{ auth()->user()->role_label }}</div>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-3 py-3 space-y-0.5 overflow-y-auto">

                @php $role = auth()->user()->role; @endphp

                {{-- ADMIN --}}
                @if($role === 'admin')
                    <div class="sidebar-section">Menu Utama</div>
                    <a href="{{ route('admin.dashboard') }}"
                        class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-gauge w-4 text-center"></i> Dashboard
                    </a>
                    <div class="sidebar-section">Manajemen</div>
                    <a href="{{ route('admin.users.index') }}"
                        class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users w-4 text-center"></i> Pengguna
                    </a>
                    <a href="{{ route('admin.parameter.index') }}"
                        class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm {{ request()->routeIs('admin.parameter.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-sliders w-4 text-center"></i> Parameter Fuzzy
                    </a>
                    <a href="{{ route('admin.rules.index') }}"
                        class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm {{ request()->routeIs('admin.rules.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-code-branch w-4 text-center"></i> Rule Fuzzy
                    </a>
                    <!-- <a href="{{ route('admin.setting-konversi.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm {{ request()->routeIs('admin.setting-konversi.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-calculator w-4 text-center"></i> Rumus Konversi
                    </a> -->
                    <div class="sidebar-section">Data</div>
                    <a href="{{ route('admin.analisis.index') }}"
                        class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm {{ request()->routeIs('admin.analisis.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-waveform w-4 text-center"></i> Hasil Analisis
                    </a>

                    {{-- KREDIT ANALIS --}}
                @elseif($role === 'analis')
                    <div class="sidebar-section">Menu Utama</div>
                    <a href="{{ route('analis.dashboard') }}"
                        class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm {{ request()->routeIs('analis.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-gauge w-4 text-center"></i> Dashboard
                    </a>
                    <div class="sidebar-section">Analisis</div>
                    <a href="{{ route('analis.analisis.create') }}"
                        class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm {{ request()->routeIs('analis.analisis.create') ? 'active' : '' }}">
                        <i class="fa-solid fa-plus-circle w-4 text-center"></i> Analisis Baru
                    </a>
                    <a href="{{ route('analis.analisis.index') }}"
                        class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm {{ request()->routeIs('analis.analisis.index') ? 'active' : '' }}">
                        <i class="fa-solid fa-clock-rotate-left w-4 text-center"></i> Riwayat Analisis
                    </a>

                    {{-- KEPALA CABANG --}}
                @elseif($role === 'kepala_cabang')
                    <div class="sidebar-section">Menu Utama</div>
                    <a href="{{ route('kepala_cabang.dashboard') }}"
                        class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm {{ request()->routeIs('kepala_cabang.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-gauge w-4 text-center"></i> Dashboard
                    </a>
                    <div class="sidebar-section">Persetujuan</div>
                    <a href="{{ route('kepala_cabang.approval.index') }}"
                        class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm {{ request()->routeIs('kepala_cabang.approval.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-stamp w-4 text-center"></i> Approval Analisis
                        @php $pending = \App\Models\HasilAnalisis::where('status_approval', 'menunggu')->count(); @endphp
                        @if($pending > 0)
                            <span
                                class="ml-auto bg-gold-500 text-navy-800 text-xs font-bold px-2 py-0.5 rounded-full">{{ $pending }}</span>
                        @endif
                    </a>

                    {{-- MARKETING --}}
                @elseif($role === 'marketing')
                    <div class="sidebar-section">Menu Utama</div>
                    <a href="{{ route('marketing.dashboard') }}"
                        class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm {{ request()->routeIs('marketing.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-gauge w-4 text-center"></i> Dashboard
                    </a>
                    <div class="sidebar-section">Data</div>
                    <a href="{{ route('marketing.analisis.index') }}"
                        class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm {{ request()->routeIs('marketing.analisis.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-waveform w-4 text-center"></i> Hasil Analisis
                    </a>
                @endif
            </nav>

            {{-- Logout --}}
            <div class="px-4 py-3 border-t border-white/10">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/60 hover:text-white hover:bg-white/10 text-sm transition-all">
                        <i class="fa-solid fa-right-from-bracket w-4 text-center"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            {{-- Navbar --}}
            <header
                class="bg-white border-b border-navy-100/50 px-4 lg:px-6 py-3 flex items-center gap-4 flex-shrink-0 shadow-sm">
                <button onclick="openSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-slate-100 text-slate-500">
                    <i class="fa-solid fa-bars"></i>
                </button>
                {{-- Breadcrumb --}}
                <div class="flex-1">
                    <h1 class="text-sm font-bold text-navy-800">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-xs text-slate-400">@yield('page-subtitle', '')</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block text-right">
                        <div class="text-xs font-semibold text-navy-700">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-slate-400">{{ now()->isoFormat('dddd, D MMM Y') }}</div>
                    </div>
                    <div
                        class="w-8 h-8 rounded-full bg-gold-500 flex items-center justify-center text-navy-800 font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </header>

            {{-- Flash Messages --}}
            <div class="px-4 lg:px-6 pt-4 space-y-2">
                @if(session('success'))
                    <div
                        class="flex items-center gap-3 p-3.5 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm animate-slide">
                        <i class="fa-solid fa-circle-check flex-shrink-0"></i>
                        <span>{{ session('success') }}</span>
                        <button onclick="this.parentElement.remove()" class="ml-auto text-green-500 hover:text-green-700"><i
                                class="fa-solid fa-xmark"></i></button>
                    </div>
                @endif
                @if(session('error'))
                    <div
                        class="flex items-center gap-3 p-3.5 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm animate-slide">
                        <i class="fa-solid fa-circle-xmark flex-shrink-0"></i>
                        <span>{{ session('error') }}</span>
                        <button onclick="this.parentElement.remove()" class="ml-auto text-red-500 hover:text-red-700"><i
                                class="fa-solid fa-xmark"></i></button>
                    </div>
                @endif
            </div>

            {{-- Content --}}
            <main class="flex-1 overflow-y-auto p-4 lg:p-6 space-y-5 animate-slide">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function openSidebar() { document.getElementById('sidebar').classList.remove('-translate-x-full'); document.getElementById('mobileOverlay').style.display = 'block'; }
        function closeSidebar() { document.getElementById('sidebar').classList.add('-translate-x-full'); document.getElementById('mobileOverlay').style.display = 'none'; }
        // setTimeout(() => { document.querySelectorAll('.animate-slide').forEach(el => { el.style.transition='opacity 0.5s'; el.style.opacity='0'; setTimeout(()=>el.remove(),500); }); }, 5000);
    </script>
    @stack('scripts')
</body>

</html>