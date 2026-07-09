<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') &mdash; Mandiri Utama Finance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #dddddd; min-height: 100vh; }
        .bg-pattern { background-image: radial-gradient(circle at 1px 1px, rgba(245,166,35,0.08) 1px, transparent 0); background-size: 32px 32px; }
        .glass { background: rgba(255,255,255,0.97); }
        .btn-primary { background: linear-gradient(135deg, #1a2e5a 0%, #2d4190 100%); transition: all 0.2s; }
        .btn-primary:hover { box-shadow: 0 8px 24px rgba(26,46,90,0.4); transform: translateY(-1px); }
        /* @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} } */
        .float { animation: float 5s ease-in-out infinite; }
        input:focus { outline:none; border-color:#1a2e5a; box-shadow:0 0 0 3px rgba(26,46,90,0.12); }
    </style>
</head>
<body class="bg-pattern flex items-center justify-center p-4 min-h-screen">
    {{-- Decorative blobs --}}
    <div class="fixed top-0 left-0 w-96 h-96 rounded-full opacity-20 blur-3xl pointer-events-none" style="background:radial-gradient(circle, #1a2e5a, transparent)"></div>
    <div class="fixed bottom-0 right-0 w-80 h-80 rounded-full opacity-15 blur-3xl pointer-events-none" style="background:radial-gradient(circle, #f5a623, transparent)"></div>

    <div class="w-full max-w-md float">
        {{-- Logo --}}
        {{-- <div class="text-center mb-7">
            <div class="text-2xl font-bold text-white">Mandiri Utama Finance</div>
            <div class="text-sm mt-1" style="color:#f5a623">Sistem Penentu Kelayakan Kredit</div>
        </div> --}}

        {{-- Card --}}
        <div class="glass rounded-2xl shadow-2xl p-8">
            @yield('content')
        </div>

        <p class="text-center text-xs mt-5" style="color:rgb(255, 255, 255)">
            &copy; {{ date('Y') }} PT Mandiri Utama Finance
        </p>
    </div>
</body>
</html>
