<div class="card group flex flex-col gap-3 relative">
    {{-- Header --}}
    <div class="flex items-start justify-between gap-2">
        <a href="{{ route('notes.edit', $note) }}"
           class="flex-1 min-w-0 font-semibold text-slate-900 dark:text-slate-100 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors line-clamp-2 leading-snug">
            {{ $note->title }}
        </a>

        {{-- Actions dropdown --}}
        <div x-data="{ open: false }" class="relative flex-shrink-0">
            <button @click="open = !open" @click.outside="open = false"
                    class="flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-zinc-800 transition-colors opacity-0 group-hover:opacity-100 focus:opacity-100">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <circle cx="5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="19" cy="12" r="1.5"/>
                </svg>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-1 w-44 bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl shadow-lg py-1 z-10"
                 style="display:none">
                <a href="{{ route('notes.edit', $note) }}"
                   class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-zinc-700/50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    {{ __('messages.notes.edit') }}
                </a>

                <form method="POST" action="{{ route('notes.pin', $note) }}">
                    @csrf @method('PATCH')
                    <button type="submit"
                            class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-zinc-700/50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                        </svg>
                        {{ $note->is_pinned ? __('messages.notes.unpin') : __('messages.notes.pin') }}
                    </button>
                </form>

                <form method="POST" action="{{ route('notes.archive', $note) }}">
                    @csrf @method('PATCH')
                    <button type="submit"
                            class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-zinc-700/50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                        {{ $note->is_archived ? __('messages.notes.unarchive') : __('messages.notes.archive_action') }}
                    </button>
                </form>

                <div class="border-t border-slate-100 dark:border-zinc-700 my-1"></div>

                <form method="POST" action="{{ route('notes.destroy', $note) }}"
                      x-data
                      @submit.prevent="$store.confirm.ask({
                          title: '{{ __('messages.notes.delete_confirm_title') }}',
                          message: '{{ __('messages.notes.delete_confirm_message') }}',
                          confirmLabel: '{{ __('messages.notes.delete_confirm_label') }}',
                          onConfirm: () => $el.submit()
                      })">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        {{ __('messages.notes.delete') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Drawing thumbnail --}}
    @if ($note->drawing)
        <a href="{{ route('notes.edit', $note) }}" class="block rounded-lg overflow-hidden border border-slate-100 dark:border-zinc-800 flex-shrink-0">
            <img src="{{ $note->drawing }}" alt="Drawing" class="w-full h-28 object-cover object-top">
        </a>
    @endif

    {{-- Rich text content preview --}}
    @if ($note->content)
        <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-3 leading-relaxed flex-1">
            {{ Str::limit(strip_tags($note->content), 160) }}
        </p>
    @elseif (!$note->drawing)
        <p class="text-sm text-slate-400 dark:text-slate-600 italic flex-1">No content</p>
    @endif

    {{-- Footer --}}
    <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-zinc-800 text-xs text-slate-400 dark:text-slate-500">
        <span>{{ $note->updated_at->diffForHumans() }}</span>
        <div class="flex items-center gap-2">
            @if ($note->drawing)
                <span class="flex items-center gap-1 text-violet-500 dark:text-violet-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                    Drawing
                </span>
            @endif
            @if ($note->is_pinned)
                <span class="flex items-center gap-1 text-amber-500 dark:text-amber-400 font-medium">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                    </svg>
                    {{ __('messages.notes.pinned_label') }}
                </span>
            @endif
        </div>
    </div>
</div>
