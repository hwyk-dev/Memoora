<x-app-layout>
    <x-slot name="title">{{ __('messages.admin.user_detail_title', ['name' => $user->name]) }}</x-slot>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.users.index') }}" class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ $user->name }}</h1>
        </div>
    </x-slot>

    <div class="max-w-3xl space-y-6">

        {{-- Profile card --}}
        <div class="card flex items-start gap-4">
            <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 text-xl font-bold flex-shrink-0 select-none">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ $user->name }}</h2>
                    @if ($user->is_admin)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-400">{{ __('messages.admin.admin_badge') }}</span>
                    @endif
                    @if ($user->firebase_uid)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400">{{ __('messages.admin.google') }}</span>
                    @endif
                    @if ($user->isBanned())
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400">{{ __('messages.admin.status_banned') }}</span>
                    @elseif ($user->isSuspended())
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400">{{ __('messages.admin.status_suspended') }}</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400">{{ __('messages.admin.status_active') }}</span>
                    @endif
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                <div class="mt-2 flex flex-wrap gap-x-5 gap-y-1 text-xs text-slate-400 dark:text-slate-500">
                    <span>{{ __('messages.admin.joined', ['date' => $user->created_at->format('M j, Y')]) }}</span>
                    <span>{{ __('messages.admin.last_login', ['date' => $user->last_login_at ? $user->last_login_at->diffForHumans() : __('messages.admin.never')]) }}</span>
                    @if ($user->email_verified_at)
                        <span class="text-emerald-600 dark:text-emerald-400">✓ {{ __('messages.admin.email_verified') }}</span>
                    @else
                        <span class="text-amber-500 dark:text-amber-400">{{ __('messages.admin.email_unverified') }}</span>
                    @endif
                </div>

                {{-- Ban/suspend info --}}
                @if ($user->isBanned() && $user->ban_reason)
                    <p class="mt-2 text-xs text-red-600 dark:text-red-400">{{ __('messages.admin.ban_reason_label2', ['reason' => $user->ban_reason]) }}</p>
                @elseif ($user->isSuspended())
                    <p class="mt-2 text-xs text-amber-600 dark:text-amber-400">
                        {{ __('messages.admin.suspended_until', ['date' => $user->suspended_until->format('M j, Y g:i A')]) }}
                    </p>
                @endif
            </div>
        </div>

        {{-- Note stats --}}
        <div class="grid grid-cols-3 gap-4">
            <div class="card text-center">
                <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $stats['total'] }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('messages.admin.note_stats_total') }}</p>
            </div>
            <div class="card text-center">
                <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $stats['pinned'] }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('messages.admin.note_stats_pinned') }}</p>
            </div>
            <div class="card text-center">
                <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $stats['archived'] }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('messages.admin.note_stats_archived') }}</p>
            </div>
        </div>

        {{-- Recent notes --}}
        <div class="card">
            <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100 mb-4">{{ __('messages.admin.recent_notes_title') }}</h3>

            @if ($recentNotes->isEmpty())
                <p class="text-sm text-slate-400 dark:text-slate-500 text-center py-6">{{ __('messages.admin.no_user_notes') }}</p>
            @else
                <div class="space-y-1">
                    @foreach ($recentNotes as $note)
                        <div class="flex items-start gap-3 py-2.5 border-b border-slate-100 dark:border-zinc-800 last:border-0">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200 truncate">{{ $note->title }}</p>
                                @if ($note->content)
                                    <p class="text-xs text-slate-400 dark:text-slate-500 truncate mt-0.5">{{ Str::limit(strip_tags($note->content), 80) }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                @if ($note->is_pinned)
                                    <span class="text-xs text-amber-500 dark:text-amber-400">{{ __('messages.admin.note_stats_pinned') }}</span>
                                @endif
                                @if ($note->is_archived)
                                    <span class="text-xs text-slate-400 dark:text-slate-500">{{ __('messages.admin.note_stats_archived') }}</span>
                                @endif
                                <span class="text-xs text-slate-400 dark:text-slate-500 whitespace-nowrap">{{ $note->created_at->format('M j') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        @if (!$user->is(auth()->user()))

            {{-- Moderation controls --}}
            <div class="card" x-data="{ banOpen: false, suspendOpen: false }">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100 mb-4">{{ __('messages.admin.moderation_title') }}</h3>

                <div class="flex flex-wrap gap-3">

                    {{-- Ban / Unban --}}
                    @if ($user->isBanned())
                        <form method="POST" action="{{ route('admin.users.unban', $user) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-secondary text-sm">
                                {{ __('messages.admin.unban_user_btn') }}
                            </button>
                        </form>
                    @else
                        <button @click="banOpen = !banOpen" type="button"
                                class="px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 border border-red-300 dark:border-red-700 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                            {{ __('messages.admin.ban_user_btn') }}
                        </button>
                    @endif

                    {{-- Suspend / Unsuspend --}}
                    @if ($user->isSuspended())
                        <form method="POST" action="{{ route('admin.users.unsuspend', $user) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-secondary text-sm">
                                {{ __('messages.admin.lift_suspension_btn') }}
                            </button>
                        </form>
                    @elseif (!$user->isBanned())
                        <button @click="suspendOpen = !suspendOpen" type="button"
                                class="px-4 py-2 text-sm font-medium text-amber-600 dark:text-amber-400 border border-amber-300 dark:border-amber-700 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-colors">
                            {{ __('messages.admin.suspend_user_btn') }}
                        </button>
                    @endif

                </div>

                {{-- Ban form --}}
                <div x-show="banOpen" x-transition class="mt-4 pt-4 border-t border-slate-100 dark:border-zinc-800" style="display:none">
                    <form method="POST" action="{{ route('admin.users.ban', $user) }}" class="space-y-3">
                        @csrf @method('PATCH')
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                                {{ __('messages.admin.ban_reason_label') }} <span class="text-slate-400 font-normal">{{ __('messages.admin.ban_reason_hint') }}</span>
                            </label>
                            <input type="text" name="reason" maxlength="500"
                                   placeholder="{{ __('messages.admin.ban_reason_placeholder') }}"
                                   class="form-input text-sm">
                        </div>
                        <div class="flex gap-2">
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                                {{ __('messages.admin.confirm_ban_btn') }}
                            </button>
                            <button type="button" @click="banOpen = false" class="btn-secondary text-sm">{{ __('messages.admin.cancel') }}</button>
                        </div>
                    </form>
                </div>

                {{-- Suspend form --}}
                <div x-show="suspendOpen" x-transition class="mt-4 pt-4 border-t border-slate-100 dark:border-zinc-800" style="display:none">
                    <form method="POST" action="{{ route('admin.users.suspend', $user) }}" class="space-y-3">
                        @csrf @method('PATCH')
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('messages.admin.suspend_duration') }}</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach ([1 => '1 day', 3 => '3 days', 7 => '7 days', 14 => '14 days', 30 => '30 days'] as $days => $label)
                                    <label class="flex items-center gap-1.5 cursor-pointer">
                                        <input type="radio" name="days" value="{{ $days }}" {{ $days === 7 ? 'checked' : '' }}
                                               class="text-indigo-600 focus:ring-indigo-500 dark:bg-zinc-800">
                                        <span class="text-sm text-slate-700 dark:text-slate-300">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('days')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex gap-2">
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors">
                                {{ __('messages.admin.confirm_suspend_btn') }}
                            </button>
                            <button type="button" @click="suspendOpen = false" class="btn-secondary text-sm">{{ __('messages.admin.cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Danger zone --}}
            <div class="card border-red-200 dark:border-red-900/50">
                <h3 class="text-sm font-semibold text-red-700 dark:text-red-400 mb-1">{{ __('messages.admin.danger_zone') }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                    {{ __('messages.admin.danger_desc') }}
                </p>
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                      x-data
                      @submit.prevent="$store.confirm.ask({
                          title: '{{ __('messages.admin.delete_user') }}: {{ addslashes($user->name) }}',
                          message: '{{ __('messages.admin.delete_user_confirm_msg', ['count' => $stats['total']]) }}',
                          confirmLabel: '{{ __('messages.admin.delete_account_btn') }}',
                          danger: true,
                          onConfirm: () => $el.submit()
                      })">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                        {{ __('messages.admin.delete_account_btn') }}
                    </button>
                </form>
            </div>

        @endif

    </div>
</x-app-layout>
