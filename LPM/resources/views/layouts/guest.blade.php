<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'e-SPMI') }} – STTNI</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
<div class="min-h-screen flex">

    <!-- Left panel — branding -->
    <div class="hidden lg:flex flex-col justify-between w-1/2 bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 p-12 relative overflow-hidden">

        <!-- Background decoration -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-indigo-500/10 blur-3xl"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-purple-500/10 blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 rounded-full bg-indigo-600/5 blur-2xl"></div>
        </div>

        <!-- Logo & App title -->
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl bg-white p-1 flex items-center justify-center shadow-lg overflow-hidden">
                    <img src="{{ asset('logo.png') }}" alt="Logo STTNI" class="w-full h-full object-contain">
                </div>
                <div>
                    <p class="text-white font-bold text-lg leading-tight">e-SPMI</p>
                    <p class="text-indigo-300 text-xs">STTNI</p>
                </div>
            </div>
        </div>

        <!-- Center content -->
        <div class="relative z-10 flex-1 flex flex-col justify-center">
            <h1 class="text-4xl font-bold text-white leading-tight mb-4">
                Sistem Informasi<br>
                <span class="text-indigo-400">Penjaminan Mutu</span><br>
                Internal
            </h1>
            <p class="text-slate-300 text-base leading-relaxed max-w-sm">
                Platform digital untuk mendukung siklus PPEPP dan Audit Mutu Internal (AMI)
                sesuai standar SN-Dikti.
            </p>

            <!-- PPEPP Phase indicators -->
            <div class="mt-10 space-y-3">
                @foreach([
                    ['P', 'Penetapan', 'Kebijakan & Standar Mutu'],
                    ['P', 'Pelaksanaan', 'Evaluasi Diri Program Studi'],
                    ['E', 'Evaluasi', 'Audit & Verifikasi Temuan'],
                    ['P', 'Pengendalian', 'CAPA & Tindak Lanjut'],
                    ['P', 'Peningkatan', 'Dashboard & Analitik']
                ] as [$letter, $phase, $desc])
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-indigo-600/50 border border-indigo-500/30 flex items-center justify-center flex-shrink-0">
                        <span class="text-indigo-200 text-xs font-bold">{{ $letter }}</span>
                    </div>
                    <div>
                        <p class="text-slate-200 text-sm font-semibold leading-none">{{ $phase }}</p>
                        <p class="text-slate-400 text-xs mt-0.5">{{ $desc }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Footer -->
        <div class="relative z-10 text-slate-500 text-xs">
            &copy; {{ date('Y') }} STTNI — Lembaga Penjaminan Mutu
        </div>
    </div>

    <!-- Right panel — auth form -->
    <div class="flex-1 flex flex-col justify-center items-center p-8 bg-slate-50">
        <div class="w-full max-w-md">

            <!-- Mobile logo -->
            <div class="lg:hidden flex items-center gap-2 mb-8">
                <div class="w-8 h-8 rounded-lg bg-white p-0.5 flex items-center justify-center shadow overflow-hidden">
                    <img src="{{ asset('logo.png') }}" alt="Logo STTNI" class="w-full h-full object-contain">
                </div>
                <span class="font-bold text-slate-800">e-SPMI STTNI</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/70 p-8">
                {{ $slot }}
            </div>
        </div>
    </div>

</div>
</body>
</html>
