<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'e-SPMI') }} — @yield('title', 'Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <meta name="description" content="Sistem Informasi Penjaminan Mutu Internal (e-SPMI) STTNI">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- ApexCharts CDN for dashboard charts -->
    @stack('head-scripts')
</head>
<body class="h-full bg-slate-50 font-sans antialiased" x-data="{ sidebarOpen: false }">

<!-- ─── Mobile sidebar overlay ─────────────────────────────────────────────── -->
<div x-show="sidebarOpen"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-40 bg-slate-900/60 lg:hidden"
     @click="sidebarOpen = false">
</div>

<!-- ─── Sidebar ─────────────────────────────────────────────────────────────── -->
<aside class="fixed inset-y-0 left-0 z-50 flex flex-col w-72 bg-gradient-to-b from-slate-900 to-slate-800 shadow-2xl
              transform transition-transform duration-300 ease-in-out
              lg:translate-x-0"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

    {{-- Logo & App Name --}}
    <div class="flex items-center gap-3 h-16 px-6 border-b border-slate-700/50">
        <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-white p-1 flex items-center justify-center shadow-lg overflow-hidden">
            <img src="{{ asset('logo.png') }}" alt="Logo STTNI" class="w-full h-full object-contain">
        </div>
        <div class="min-w-0">
            <p class="text-white font-bold text-sm leading-tight truncate">e-SPMI STTNI</p>
            <p class="text-slate-400 text-xs truncate">Penjaminan Mutu Internal</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                  {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-700/60 hover:text-white' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        {{-- ── PPEPP: PENETAPAN ──────────────────────────────────────────── --}}
        <div class="pt-3 pb-1">
            <p class="px-3 text-xs font-semibold uppercase tracking-widest text-slate-500">Penetapan</p>
        </div>

        <a href="{{ route('dokumen-mutu.index') }}"
           class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                  {{ request()->routeIs('dokumen-mutu.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-700/60 hover:text-white' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Dokumen Mutu
        </a>

        <a href="{{ route('standar-mutu.index') }}"
           class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                  {{ request()->routeIs('standar-mutu.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-700/60 hover:text-white' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            Standar Mutu
        </a>

        {{-- ── PPEPP: PELAKSANAAN & EVALUASI ───────────────────────────────── --}}
        <div class="pt-3 pb-1">
            <p class="px-3 text-xs font-semibold uppercase tracking-widest text-slate-500">Pelaksanaan & Evaluasi</p>
        </div>

        <a href="{{ route('evaluasi-diri.index') }}"
           class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                  {{ request()->routeIs('evaluasi-diri.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-700/60 hover:text-white' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Evaluasi Diri
        </a>

        {{-- ── PPEPP: PENGENDALIAN & PENINGKATAN ───────────────────────────── --}}
        <div class="pt-3 pb-1">
            <p class="px-3 text-xs font-semibold uppercase tracking-widest text-slate-500">Pengendalian & Peningkatan</p>
        </div>

        <a href="{{ route('audit-internal.index') }}"
           class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                  {{ request()->routeIs('audit-internal.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-700/60 hover:text-white' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            Audit Internal (AMI)
        </a>

        {{-- ── Admin Only ───────────────────────────────────────────────────── --}}
        @role('superadmin')
        <div class="pt-3 pb-1">
            <p class="px-3 text-xs font-semibold uppercase tracking-widest text-slate-500">Administrasi</p>
        </div>

        <a href="{{ route('users.index') }}"
           class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                  {{ request()->routeIs('users.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-700/60 hover:text-white' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            Pengguna
        </a>

        <a href="{{ route('program-studi.index') }}"
           class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                  {{ request()->routeIs('program-studi.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-700/60 hover:text-white' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            Program Studi
        </a>

        <a href="{{ route('fakultas.index') }}"
           class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                  {{ request()->routeIs('fakultas.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-700/60 hover:text-white' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
            </svg>
            Fakultas
        </a>
        @endrole

    </nav>

    {{-- User Profile (bottom) --}}
    <div class="border-t border-slate-700/50 p-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center flex-shrink-0">
                <span class="text-white text-xs font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-white text-xs font-semibold truncate">{{ auth()->user()->name }}</p>
                <p class="text-slate-400 text-xs truncate">
                    {{ auth()->user()->getRoleNames()->implode(', ') }}
                </p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Keluar"
                        class="text-slate-400 hover:text-white transition-colors duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- ─── Main content wrapper ─────────────────────────────────────────────────── -->
<div class="lg:pl-72 flex flex-col min-h-screen">

    <!-- Top bar -->
    <header class="sticky top-0 z-30 flex items-center gap-4 h-16 bg-white border-b border-slate-200 px-4 lg:px-8 shadow-sm">
        <!-- Mobile burger -->
        <button @click="sidebarOpen = !sidebarOpen"
                class="lg:hidden p-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <!-- Breadcrumb / Page Title -->
        <div class="flex-1 min-w-0">
            @yield('breadcrumb')
        </div>

        <!-- Right side info -->
        <div class="flex items-center gap-3">
            <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg border border-slate-200 shadow-sm">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span id="live-date-time">{{ now()->timezone('Asia/Jakarta')->isoFormat('dddd, D MMMM YYYY') }}</span>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    <div class="px-4 lg:px-8 pt-4">
        @if(session('success'))
            <div id="flash-success"
                 class="flex items-center gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm mb-4"
                 x-data x-init="setTimeout(() => $el.remove(), 5000)">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="flex-1">{{ session('success') }}</span>
                <button @click="$el.closest('[id]').remove()" class="text-emerald-500 hover:text-emerald-700">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div id="flash-error"
                 class="flex items-center gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm mb-4"
                 x-data x-init="setTimeout(() => $el.remove(), 6000)">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span class="flex-1">{{ session('error') }}</span>
            </div>
        @endif
    </div>

    <!-- Page Content -->
    <main class="flex-1 px-4 lg:px-8 pb-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto py-4 px-8 border-t border-slate-200 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} {{ config('app.name') }} — STTNI · Sistem Informasi Penjaminan Mutu Internal
    </footer>
</div>

@stack('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateTimeSpan = document.getElementById('live-date-time');
    if (!dateTimeSpan) return;

    const now = new Date();
    const options = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric'
    };
    const formatter = new Intl.DateTimeFormat('id-ID', options);
    dateTimeSpan.textContent = formatter.format(now);
});
</script>
</body>
</html>
