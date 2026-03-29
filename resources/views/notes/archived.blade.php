<x-app-layout>
    <x-slot name="title">{{ __('messages.notes.archive') }}</x-slot>
    <x-slot name="header">
        <h1 class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ __('messages.notes.archive') }}</h1>
    </x-slot>

    @if ($notes->isEmpty())
        <div class="card flex flex-col items-center justify-center py-20 text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-zinc-800 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
            </div>
            <h3 class="text-base font-medium text-slate-900 dark:text-slate-100 mb-1">{{ __('messages.notes.no_archived') }}</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('messages.notes.no_archived_desc') }}</p>
        </div>
    @else
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
            {{ $notes->total() }} archived note{{ $notes->total() !== 1 ? 's' : '' }} — restore them to access again.
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach ($notes as $note)
                @include('notes._card', ['note' => $note])
            @endforeach
        </div>

        @if ($notes->hasPages())
            <div class="mt-6">{{ $notes->links() }}</div>
        @endif
    @endif
</x-app-layout>
