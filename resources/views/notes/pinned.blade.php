<x-app-layout>
    <x-slot name="title">{{ __('messages.notes.pinned') }}</x-slot>
    <x-slot name="header">
        <h1 class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ __('messages.notes.pinned') }}</h1>
    </x-slot>

    @if ($notes->isEmpty())
        <div class="card flex flex-col items-center justify-center py-20 text-center">
            <div class="w-16 h-16 rounded-2xl bg-amber-50 dark:bg-amber-950/30 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                </svg>
            </div>
            <h3 class="text-base font-medium text-slate-900 dark:text-slate-100 mb-1">{{ __('messages.notes.no_pinned') }}</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
                {{ __('messages.notes.no_pinned_desc') }}
            </p>
            <a href="{{ route('notes.index') }}" class="btn-primary">{{ __('messages.notes.all_notes') }}</a>
        </div>
    @else
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
