<x-app-layout>
    <x-slot name="title">{{ __('messages.notes.all_notes') }}</x-slot>
    <x-slot name="header">
        <h1 class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ __('messages.notes.all_notes') }}</h1>
    </x-slot>

    {{-- Toolbar --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        {{-- Search --}}
        <form method="GET" action="{{ route('notes.index') }}" class="flex-1">
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="search" name="search" value="{{ $search ?? '' }}"
                       placeholder="{{ __('messages.notes.search_placeholder') }}"
                       class="form-input pl-10 pr-10">
                @if ($search)
                    <a href="{{ route('notes.index') }}"
                       class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                @endif
            </div>
        </form>

        <a href="{{ route('notes.create') }}" class="btn-primary flex items-center gap-1.5 whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('messages.notes.new_note') }}
        </a>
    </div>

    {{-- Search result header --}}
    @if ($search)
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
            {{ $notes->total() }} result{{ $notes->total() !== 1 ? 's' : '' }} for
            <span class="font-medium text-slate-900 dark:text-slate-100">"{{ $search }}"</span>
        </p>
    @endif

    {{-- Notes grid --}}
    @if ($notes->isEmpty())
        <div class="card flex flex-col items-center justify-center py-20 text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-zinc-800 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            @if ($search)
                <h3 class="text-base font-medium text-slate-900 dark:text-slate-100 mb-1">{{ __('messages.notes.no_notes') }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">Try a different search term.</p>
            @else
                <h3 class="text-base font-medium text-slate-900 dark:text-slate-100 mb-1">{{ __('messages.notes.no_notes') }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">{{ __('messages.notes.no_notes_desc') }}</p>
                <a href="{{ route('notes.create') }}" class="btn-primary">{{ __('messages.dashboard.create_note') }}</a>
            @endif
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach ($notes as $note)
                @include('notes._card', ['note' => $note])
            @endforeach
        </div>

        {{-- Pagination --}}
        @if ($notes->hasPages())
            <div class="mt-6">
                {{ $notes->links() }}
            </div>
        @endif
    @endif
</x-app-layout>
