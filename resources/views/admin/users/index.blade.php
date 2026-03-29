<x-app-layout>
    <x-slot name="title">{{ __('messages.admin.users_title') }}</x-slot>
    <x-slot name="header">
        <h1 class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ __('messages.admin.users_title') }}</h1>
    </x-slot>

    {{-- Page-level component manages the ban/suspend modals --}}
    <div x-data="{
        modal: { open: false, type: '', userId: null, userName: '' },
        openBan(id, name)     { this.modal = { open: true, type: 'ban',     userId: id, userName: name }; },
        openSuspend(id, name) { this.modal = { open: true, type: 'suspend', userId: id, userName: name }; },
        close()               { this.modal.open = false; }
    }">

        {{-- Ban modal --}}
        <template x-if="modal.type === 'ban' && modal.open">
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100">
                <div @click.outside="close()"
                     class="w-full max-w-md bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-800 shadow-xl p-6"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100">
                    <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100 mb-1">
                        {{ __('messages.admin.ban_user_btn') }}: <span x-text="modal.userName"></span>
                    </h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-5">
                        {{ __('messages.admin.ban_modal_desc') }}
                    </p>
                    <form method="POST"
                          :action="'/admin/users/' + modal.userId + '/ban'"
                          class="space-y-4">
                        @csrf @method('PATCH')
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                                {{ __('messages.admin.ban_reason_label') }} <span class="font-normal text-slate-400">{{ __('messages.admin.ban_reason_hint') }}</span>
                            </label>
                            <input type="text" name="reason" maxlength="500"
                                   placeholder="{{ __('messages.admin.ban_reason_placeholder') }}"
                                   class="form-input text-sm">
                        </div>
                        <div class="flex gap-2 justify-end">
                            <button type="button" @click="close()" class="btn-secondary text-sm">{{ __('messages.admin.cancel') }}</button>
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                                {{ __('messages.admin.confirm_ban_btn') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        {{-- Suspend modal --}}
        <template x-if="modal.type === 'suspend' && modal.open">
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100">
                <div @click.outside="close()"
                     class="w-full max-w-md bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-800 shadow-xl p-6"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100">
                    <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100 mb-1">
                        {{ __('messages.admin.suspend_user_btn') }}: <span x-text="modal.userName"></span>
                    </h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-5">
                        {{ __('messages.admin.suspend_modal_desc') }}
                    </p>
                    <form method="POST"
                          :action="'/admin/users/' + modal.userId + '/suspend'"
                          class="space-y-4">
                        @csrf @method('PATCH')
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">{{ __('messages.admin.suspend_duration') }}</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach ([1 => '1 day', 3 => '3 days', 7 => '7 days', 14 => '14 days', 30 => '30 days'] as $days => $label)
                                    <label class="flex items-center gap-1.5 cursor-pointer">
                                        <input type="radio" name="days" value="{{ $days }}" {{ $days === 7 ? 'checked' : '' }}
                                               class="text-indigo-600 focus:ring-indigo-500 dark:bg-zinc-800">
                                        <span class="text-sm text-slate-700 dark:text-slate-300">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex gap-2 justify-end">
                            <button type="button" @click="close()" class="btn-secondary text-sm">{{ __('messages.admin.cancel') }}</button>
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors">
                                {{ __('messages.admin.confirm_suspend_btn') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        {{-- Search + filters --}}
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-3 mb-5">
            <div class="relative flex-1 min-w-48 max-w-sm">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="search" name="search" value="{{ request('search') }}"
                       placeholder="{{ __('messages.admin.search_placeholder') }}"
                       class="form-input pl-9 py-2 text-sm">
            </div>
            <select name="status" onchange="this.form.submit()" class="form-input py-2 text-sm w-auto">
                <option value="">{{ __('messages.admin.filter_all') }}</option>
                <option value="active"    {{ request('status') === 'active'    ? 'selected' : '' }}>{{ __('messages.admin.filter_active') }}</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>{{ __('messages.admin.filter_suspended') }}</option>
                <option value="banned"    {{ request('status') === 'banned'    ? 'selected' : '' }}>{{ __('messages.admin.filter_banned') }}</option>
            </select>
            <p class="self-center text-sm text-slate-500 dark:text-slate-400 ml-auto">
                {{ $users->total() }} {{ Str::plural('user', $users->total()) }}
            </p>
        </form>

        <div class="card p-0 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-800/50">
                            <th class="text-left px-4 py-3 font-medium text-slate-500 dark:text-slate-400">{{ __('messages.admin.col_user') }}</th>
                            <th class="text-left px-4 py-3 font-medium text-slate-500 dark:text-slate-400 hidden sm:table-cell">{{ __('messages.admin.col_notes') }}</th>
                            <th class="text-left px-4 py-3 font-medium text-slate-500 dark:text-slate-400 hidden md:table-cell">{{ __('messages.admin.col_joined') }}</th>
                            <th class="text-left px-4 py-3 font-medium text-slate-500 dark:text-slate-400 hidden lg:table-cell">{{ __('messages.admin.col_last_login') }}</th>
                            <th class="text-left px-4 py-3 font-medium text-slate-500 dark:text-slate-400">{{ __('messages.admin.col_status') }}</th>
                            <th class="w-10 px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                        @forelse ($users as $u)
                            <tr class="hover:bg-slate-50 dark:hover:bg-zinc-800/40 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 text-xs font-semibold flex-shrink-0 select-none">
                                            {{ strtoupper(substr($u->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-1.5">
                                                <p class="font-medium text-slate-800 dark:text-slate-200 truncate">{{ $u->name }}</p>
                                                @if ($u->is_admin)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-400">{{ __('messages.admin.admin_badge') }}</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-slate-400 dark:text-slate-500 truncate">{{ $u->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-400 hidden sm:table-cell">{{ $u->notes_count }}</td>
                                <td class="px-4 py-3 text-slate-500 dark:text-slate-400 hidden md:table-cell whitespace-nowrap">{{ $u->created_at->format('M j, Y') }}</td>
                                <td class="px-4 py-3 text-slate-500 dark:text-slate-400 hidden lg:table-cell whitespace-nowrap">
                                    {{ $u->last_login_at ? $u->last_login_at->diffForHumans() : '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    @if ($u->isBanned())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400">{{ __('messages.admin.status_banned') }}</span>
                                    @elseif ($u->isSuspended())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400">
                                            {{ __('messages.admin.status_suspended') }} · {{ $u->suspended_until->diffForHumans() }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400">{{ __('messages.admin.status_active') }}</span>
                                    @endif
                                </td>

                                {{-- Actions dropdown --}}
                                <td class="px-4 py-3">
                                    <div x-data="{ open: false, top: 0, right: 0 }" class="flex justify-end">
                                        <button x-ref="trigger"
                                                @click.stop="
                                                    if (!open) {
                                                        const r = $refs.trigger.getBoundingClientRect();
                                                        top = r.bottom + 4;
                                                        right = window.innerWidth - r.right;
                                                    }
                                                    open = !open;
                                                "
                                                class="flex items-center justify-center w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-zinc-800 transition-colors">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <circle cx="5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="19" cy="12" r="1.5"/>
                                            </svg>
                                        </button>

                                        <template x-teleport="body">
                                        <div x-show="open"
                                             @click.outside="open = false"
                                             :style="`position:fixed;top:${top}px;right:${right}px;z-index:9999`"
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                             class="w-44 bg-white dark:bg-zinc-900 rounded-xl border border-slate-200 dark:border-zinc-700 shadow-lg py-1"
                                             style="display:none">

                                            <a href="{{ route('admin.users.show', $u) }}"
                                               class="flex items-center gap-2 px-3 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-zinc-800 transition-colors">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                {{ __('messages.admin.view_profile') }}
                                            </a>

                                            @if (!$u->is(auth()->user()))
                                                <div class="border-t border-slate-100 dark:border-zinc-800 my-1"></div>

                                                @if ($u->isBanned())
                                                    <form method="POST" action="{{ route('admin.users.unban', $u) }}">
                                                        @csrf @method('PATCH')
                                                        <button type="submit"
                                                                class="flex w-full items-center gap-2 px-3 py-2 text-sm text-emerald-600 dark:text-emerald-400 hover:bg-slate-50 dark:hover:bg-zinc-800 transition-colors">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                            {{ __('messages.admin.unban_user') }}
                                                        </button>
                                                    </form>
                                                @else
                                                    <button type="button"
                                                            @click="open = false; openBan({{ $u->id }}, '{{ addslashes($u->name) }}')"
                                                            class="flex w-full items-center gap-2 px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-slate-50 dark:hover:bg-zinc-800 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                                        {{ __('messages.admin.ban_user') }}
                                                    </button>
                                                @endif

                                                @if ($u->isSuspended())
                                                    <form method="POST" action="{{ route('admin.users.unsuspend', $u) }}">
                                                        @csrf @method('PATCH')
                                                        <button type="submit"
                                                                class="flex w-full items-center gap-2 px-3 py-2 text-sm text-emerald-600 dark:text-emerald-400 hover:bg-slate-50 dark:hover:bg-zinc-800 transition-colors">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                                                            {{ __('messages.admin.lift_suspension') }}
                                                        </button>
                                                    </form>
                                                @elseif (!$u->isBanned())
                                                    <button type="button"
                                                            @click="open = false; openSuspend({{ $u->id }}, '{{ addslashes($u->name) }}')"
                                                            class="flex w-full items-center gap-2 px-3 py-2 text-sm text-amber-600 dark:text-amber-400 hover:bg-slate-50 dark:hover:bg-zinc-800 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                        {{ __('messages.admin.suspend_user') }}
                                                    </button>
                                                @endif

                                                <div class="border-t border-slate-100 dark:border-zinc-800 my-1"></div>

                                                <form method="POST" action="{{ route('admin.users.destroy', $u) }}"
                                                      x-data
                                                      @submit.prevent="$store.confirm.ask({
                                                          title: '{{ __('messages.admin.delete_user') }}: {{ addslashes($u->name) }}',
                                                          message: '{{ __('messages.admin.danger_desc') }}',
                                                          confirmLabel: '{{ __('messages.admin.delete_account_btn') }}',
                                                          danger: true,
                                                          onConfirm: () => $el.submit()
                                                      })">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                            class="flex w-full items-center gap-2 px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-slate-50 dark:hover:bg-zinc-800 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        {{ __('messages.admin.delete_account_btn') }}
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-sm text-slate-400 dark:text-slate-500">{{ __('messages.admin.no_results') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="px-4 py-3 border-t border-slate-100 dark:border-zinc-800">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
