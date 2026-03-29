<x-app-layout>
    <x-slot name="title">{{ __('messages.dashboard.title') }}</x-slot>
    <x-slot name="header">
        <h1 class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ __('messages.dashboard.title') }}</h1>
    </x-slot>

    {{-- Stats row --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="card flex items-center gap-4">
            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $totalNotes }}</p>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('messages.dashboard.total_notes') }}</p>
            </div>
        </div>

        <div class="card flex items-center gap-4">
            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $pinnedCount }}</p>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('messages.dashboard.pinned_notes') }}</p>
            </div>
        </div>

        <div class="card flex items-center gap-4">
            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $archivedCount }}</p>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('messages.dashboard.archived_notes') }}</p>
            </div>
        </div>
    </div>

    {{-- Recent notes --}}
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ __('messages.dashboard.recent_notes') }}</h2>
        <a href="{{ route('notes.create') }}" class="btn-primary text-sm px-4 py-2 flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('messages.dashboard.new_note') }}
        </a>
    </div>

    @if ($recentNotes->isEmpty())
        <div class="card flex flex-col items-center justify-center py-16 text-center">
            <div class="w-16 h-16 rounded-2xl bg-indigo-50 dark:bg-indigo-950/50 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-base font-medium text-slate-900 dark:text-slate-100 mb-1">{{ __('messages.dashboard.no_notes_title') }}</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">{{ __('messages.dashboard.no_notes_desc') }}</p>
            <a href="{{ route('notes.create') }}" class="btn-primary">{{ __('messages.dashboard.create_note') }}</a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach ($recentNotes as $note)
                @include('notes._card', ['note' => $note])
            @endforeach
        </div>

        @if ($totalNotes > 6)
            <div class="mt-6 text-center">
                <a href="{{ route('notes.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
                    {{ __('messages.dashboard.view_all', ['count' => $totalNotes]) }}
                </a>
            </div>
        @endif
    @endif
</x-app-layout>
