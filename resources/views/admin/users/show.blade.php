<x-app-layout>
    <x-slot name="title">Admin — {{ $user->name }}</x-slot>
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
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-400">
                            Admin
                        </span>
                    @endif
                    @if ($user->firebase_uid)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400">
                            Google
                        </span>
                    @endif
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                <div class="mt-2 flex flex-wrap gap-x-5 gap-y-1 text-xs text-slate-400 dark:text-slate-500">
                    <span>Joined {{ $user->created_at->format('M j, Y') }}</span>
                    <span>Last login: {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'never' }}</span>
                    @if ($user->email_verified_at)
                        <span class="text-emerald-600 dark:text-emerald-400">✓ Email verified</span>
                    @else
                        <span class="text-amber-500 dark:text-amber-400">Email unverified</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Note stats --}}
        <div class="grid grid-cols-3 gap-4">
            <div class="card text-center">
                <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $stats['total'] }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Total Notes</p>
            </div>
            <div class="card text-center">
                <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $stats['pinned'] }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pinned</p>
            </div>
            <div class="card text-center">
                <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $stats['archived'] }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Archived</p>
            </div>
        </div>

        {{-- Recent notes --}}
        <div class="card">
            <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100 mb-4">Recent Notes</h3>

            @if ($recentNotes->isEmpty())
                <p class="text-sm text-slate-400 dark:text-slate-500 text-center py-6">This user has no notes.</p>
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
                                    <span class="text-xs text-amber-500 dark:text-amber-400">Pinned</span>
                                @endif
                                @if ($note->is_archived)
                                    <span class="text-xs text-slate-400 dark:text-slate-500">Archived</span>
                                @endif
                                <span class="text-xs text-slate-400 dark:text-slate-500 whitespace-nowrap">{{ $note->created_at->format('M j') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Danger zone --}}
        @if (!$user->is(auth()->user()))
            <div class="card border-red-200 dark:border-red-900/50">
                <h3 class="text-sm font-semibold text-red-700 dark:text-red-400 mb-1">Danger Zone</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                    Permanently delete this account and all their notes. This cannot be undone.
                </p>
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                      x-data
                      @submit.prevent="$store.confirm.ask({
                          title: 'Delete {{ addslashes($user->name) }}?',
                          message: 'This will permanently delete their account and all {{ $stats['total'] }} notes.',
                          confirmLabel: 'Delete account',
                          danger: true,
                          onConfirm: () => $el.submit()
                      })">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                        Delete account
                    </button>
                </form>
            </div>
        @endif

    </div>
</x-app-layout>
