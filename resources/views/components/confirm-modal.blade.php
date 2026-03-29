{{-- Global confirm modal — driven by Alpine.store('confirm') --}}
<div
    x-data
    x-show="$store.confirm.open"
    x-trap.inert.noscroll="$store.confirm.open"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="display:none"
    @keydown.escape.window="$store.confirm.dismiss()"
>
    {{-- Backdrop --}}
    <div
        x-show="$store.confirm.open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="$store.confirm.dismiss()"
        class="absolute inset-0 bg-black/50 backdrop-blur-sm"
    ></div>

    {{-- Dialog --}}
    <div
        x-show="$store.confirm.open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
        class="relative w-full max-w-sm bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-800 shadow-xl p-6"
    >
        {{-- Icon --}}
        <div class="flex items-center justify-center w-12 h-12 rounded-full mx-auto mb-4"
             :class="$store.confirm.danger
                 ? 'bg-red-100 dark:bg-red-900/30'
                 : 'bg-amber-100 dark:bg-amber-900/30'">
            {{-- Danger icon --}}
            <svg x-show="$store.confirm.danger" class="w-6 h-6 text-red-600 dark:text-red-400"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            {{-- Warning icon --}}
            <svg x-show="!$store.confirm.danger" class="w-6 h-6 text-amber-600 dark:text-amber-400"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        {{-- Text --}}
        <h3 class="text-base font-semibold text-center text-slate-900 dark:text-slate-100 mb-2"
            x-text="$store.confirm.title"></h3>
        <p class="text-sm text-center text-slate-500 dark:text-slate-400 mb-6"
           x-text="$store.confirm.message"></p>

        {{-- Buttons --}}
        <div class="flex items-center gap-3">
            <button
                type="button"
                @click="$store.confirm.dismiss()"
                class="flex-1 btn-secondary"
            >Cancel</button>
            <button
                type="button"
                @click="$store.confirm.resolve()"
                class="flex-1 px-4 py-2 rounded-xl text-sm font-medium text-white transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-zinc-900"
                :class="$store.confirm.danger
                    ? 'bg-red-600 hover:bg-red-700 focus:ring-red-500'
                    : 'bg-amber-500 hover:bg-amber-600 focus:ring-amber-400'"
                x-text="$store.confirm.confirmLabel"
            ></button>
        </div>
    </div>
</div>
