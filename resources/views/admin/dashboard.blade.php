<x-app-layout>
    <x-slot name="title">Admin — Overview</x-slot>
    <x-slot name="header">
        <h1 class="text-base font-semibold text-slate-900 dark:text-slate-100">Admin Overview</h1>
    </x-slot>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="card flex items-center gap-4">
            <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($stats['total_users']) }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">Total Users</p>
            </div>
        </div>

        <div class="card flex items-center gap-4">
            <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $stats['users_today'] }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">New Today</p>
            </div>
        </div>

        <div class="card flex items-center gap-4">
            <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($stats['total_notes']) }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">Total Notes</p>
            </div>
        </div>

        <div class="card flex items-center gap-4">
            <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-violet-100 dark:bg-violet-900/40 text-violet-600 dark:text-violet-400 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $stats['notes_today'] }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">Notes Today</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- Recent sign-ups --}}
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-slate-900 dark:text-slate-100">Recent Sign-ups</h2>
                <a href="{{ route('admin.users.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">View all →</a>
            </div>

            @if ($recentUsers->isEmpty())
                <p class="text-sm text-slate-400 dark:text-slate-500 py-4 text-center">No users yet.</p>
            @else
                <div class="space-y-3">
                    @foreach ($recentUsers as $u)
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 text-xs font-semibold flex-shrink-0 select-none">
                                {{ strtoupper(substr($u->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200 truncate">{{ $u->name }}</p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 truncate">{{ $u->email }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $u->notes_count }} notes</p>
                                <p class="text-xs text-slate-400 dark:text-slate-500">{{ $u->created_at->diffForHumans() }}</p>
                            </div>
                            <a href="{{ route('admin.users.show', $u) }}" class="flex-shrink-0 text-slate-300 dark:text-slate-600 hover:text-indigo-500 dark:hover:text-indigo-400 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Recent notes --}}
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-slate-900 dark:text-slate-100">Recent Notes</h2>
            </div>

            @if ($recentNotes->isEmpty())
                <p class="text-sm text-slate-400 dark:text-slate-500 py-4 text-center">No notes yet.</p>
            @else
                <div class="space-y-3">
                    @foreach ($recentNotes as $note)
                        <div class="flex items-start gap-3">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-500 dark:text-slate-400 text-xs font-semibold flex-shrink-0 mt-0.5 select-none">
                                {{ strtoupper(substr($note->user->name ?? '?', 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200 truncate">{{ $note->title }}</p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 truncate">
                                    by {{ $note->user->name ?? 'deleted user' }} · {{ $note->created_at->diffForHumans() }}
                                </p>
                            </div>
                            @if ($note->is_pinned)
                                <span class="flex-shrink-0 inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400">
                                    Pinned
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
