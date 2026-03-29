<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'] }
                }
            }
        }
    </script>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-12px); }
        }
        @keyframes ping-slow {
            0%   { transform: scale(1);    opacity: .5; }
            80%  { transform: scale(1.6);  opacity: 0; }
            100% { transform: scale(1.6);  opacity: 0; }
        }
        @keyframes fade-up {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .anim-float    { animation: float 3.8s ease-in-out infinite; }
        .anim-ping     { animation: ping-slow 2.4s cubic-bezier(0,.2,.8,1) infinite; }
        .anim-fade-up  { animation: fade-up .55s ease both; }
        .anim-delay-1  { animation-delay: .1s; }
        .anim-delay-2  { animation-delay: .2s; }
        .anim-delay-3  { animation-delay: .32s; }
        .anim-delay-4  { animation-delay: .44s; }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 dark:bg-zinc-950 text-slate-900 dark:text-slate-100 min-h-screen flex flex-col">

    {{-- ── Top bar ──────────────────────────────────────────────── --}}
    <header class="flex items-center justify-between px-6 h-16 bg-white dark:bg-zinc-900 border-b border-slate-200 dark:border-zinc-800 flex-shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 select-none">
            <div class="flex items-center justify-center w-8 h-8 bg-indigo-600 rounded-lg">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <span class="text-base font-semibold">{{ config('app.name') }}</span>
        </a>

        <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-red-50 dark:bg-red-950/60
                     text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800 tracking-wider uppercase">
            Error 403
        </span>
    </header>

    {{-- ── Main ─────────────────────────────────────────────────── --}}
    <main class="flex-1 flex items-center justify-center px-4 py-16">
        <div class="w-full max-w-md flex flex-col items-center text-center gap-0">

            {{-- Illustration --}}
            <div class="relative flex items-center justify-center mb-10 anim-fade-up">
                {{-- Outer ping ring --}}
                <span class="absolute inline-flex w-40 h-40 rounded-full bg-red-400/20 dark:bg-red-400/10 anim-ping"></span>
                {{-- Soft glow --}}
                <span class="absolute w-44 h-44 rounded-full bg-red-500/10 blur-2xl pointer-events-none"></span>
                {{-- Card --}}
                <div class="relative anim-float flex items-center justify-center w-36 h-36 rounded-3xl
                            bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800
                            shadow-2xl shadow-slate-300/50 dark:shadow-black/60">
                    <svg class="w-16 h-16 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.4"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    {{-- ✕ badge --}}
                    <span class="absolute -top-2.5 -right-2.5 flex items-center justify-center w-8 h-8 rounded-full
                                 bg-red-500 border-2 border-slate-50 dark:border-zinc-950 shadow-md">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </span>
                </div>
            </div>

            {{-- Heading --}}
            <h1 class="text-3xl sm:text-4xl font-bold tracking-tight mb-3 anim-fade-up anim-delay-1">
                Access Denied
            </h1>

            {{-- Sub-message --}}
            <p class="text-slate-500 dark:text-slate-400 text-base leading-relaxed max-w-xs mb-10 anim-fade-up anim-delay-2">
                {{ $exception->getMessage() ?: "You don't have permission to view this resource. It may belong to someone else." }}
            </p>

            {{-- ── Action cards ── --}}
            <div class="w-full grid grid-cols-1 sm:grid-cols-2 gap-3 mb-5 anim-fade-up anim-delay-3">

                {{-- Go Back — primary --}}
                <button onclick="history.back()"
                        class="group relative overflow-hidden flex items-center gap-4 w-full px-5 py-4 rounded-2xl
                               bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800
                               text-white shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50
                               transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
                               dark:focus:ring-offset-zinc-950">
                    {{-- Shine sweep --}}
                    <span class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-700
                                 bg-gradient-to-r from-transparent via-white/10 to-transparent pointer-events-none"></span>
                    <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-white/20 flex-shrink-0
                                 transition-transform duration-200 group-hover:-translate-x-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </span>
                    <span class="text-left">
                        <span class="block font-semibold text-sm leading-tight">Go Back</span>
                        <span class="block text-xs text-indigo-200 mt-0.5">Previous page</span>
                    </span>
                </button>

                {{-- Dashboard — secondary --}}
                <a href="{{ route('dashboard') }}"
                   class="group flex items-center gap-4 w-full px-5 py-4 rounded-2xl
                          bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800
                          hover:border-slate-300 dark:hover:border-zinc-700
                          hover:bg-slate-50 dark:hover:bg-zinc-800
                          text-slate-700 dark:text-slate-300
                          shadow-sm hover:shadow-md transition-all duration-200
                          focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 dark:focus:ring-offset-zinc-950">
                    <span class="flex items-center justify-center w-10 h-10 rounded-xl
                                 bg-slate-100 dark:bg-zinc-800 group-hover:bg-slate-200 dark:group-hover:bg-zinc-700
                                 flex-shrink-0 transition-colors duration-200">
                        <svg class="w-5 h-5 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </span>
                    <span class="text-left">
                        <span class="block font-semibold text-sm leading-tight">Dashboard</span>
                        <span class="block text-xs text-slate-400 dark:text-slate-500 mt-0.5">Back to home</span>
                    </span>
                </a>
            </div>

            {{-- Tertiary link --}}
            <p class="text-sm text-slate-400 dark:text-slate-600 anim-fade-up anim-delay-4">
                Or go to your
                <a href="{{ route('notes.index') }}"
                   class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300
                          underline underline-offset-2 decoration-indigo-300 dark:decoration-indigo-700
                          hover:decoration-indigo-500 transition-colors">
                    notes
                </a>
            </p>

        </div>
    </main>

    {{-- ── Footer ───────────────────────────────────────────────── --}}
    <footer class="py-5 text-center text-xs text-slate-400 dark:text-slate-600
                   border-t border-slate-200 dark:border-zinc-800 flex-shrink-0">
        &copy; {{ date('Y') }} {{ config('app.name') }} &mdash; All rights reserved.
    </footer>

</body>
</html>
