<x-app-layout>
    <x-slot name="title">Admin — Users</x-slot>
    <x-slot name="header">
        <h1 class="text-base font-semibold text-slate-900 dark:text-slate-100">User Management</h1>
    </x-slot>

    {{-- Search + count --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-5">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex-1 max-w-sm">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="search" name="search" value="{{ request('search') }}"
                       placeholder="Search name or email…"
                       class="form-input pl-9 py-2 text-sm">
            </div>
        </form>
        <p class="text-sm text-slate-500 dark:text-slate-400 flex-shrink-0">
            {{ $users->total() }} {{ Str::plural('user', $users->total()) }}
        </p>
    </div>

    <div class="card p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-800/50">
                        <th class="text-left px-4 py-3 font-medium text-slate-500 dark:text-slate-400">User</th>
                        <th class="text-left px-4 py-3 font-medium text-slate-500 dark:text-slate-400 hidden sm:table-cell">Notes</th>
                        <th class="text-left px-4 py-3 font-medium text-slate-500 dark:text-slate-400 hidden md:table-cell">Joined</th>
                        <th class="text-left px-4 py-3 font-medium text-slate-500 dark:text-slate-400 hidden lg:table-cell">Last Login</th>
                        <th class="text-left px-4 py-3 font-medium text-slate-500 dark:text-slate-400">Role</th>
                        <th class="px-4 py-3"></th>
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
                                        <p class="font-medium text-slate-800 dark:text-slate-200 truncate">{{ $u->name }}</p>
                                        <p class="text-xs text-slate-400 dark:text-slate-500 truncate">{{ $u->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-400 hidden sm:table-cell">{{ $u->notes_count }}</td>
                            <td class="px-4 py-3 text-slate-500 dark:text-slate-400 hidden md:table-cell whitespace-nowrap">
                                {{ $u->created_at->format('M j, Y') }}
                            </td>
                            <td class="px-4 py-3 text-slate-500 dark:text-slate-400 hidden lg:table-cell whitespace-nowrap">
                                {{ $u->last_login_at ? $u->last_login_at->diffForHumans() : '—' }}
                            </td>
                            <td class="px-4 py-3">
                                @if ($u->is_admin)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-400">
                                        Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 dark:bg-zinc-800 text-slate-500 dark:text-slate-400">
                                        User
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.show', $u) }}"
                                       class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
                                        View
                                    </a>
                                    @if (!$u->is(auth()->user()))
                                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}"
                                              x-data
                                              @submit.prevent="$store.confirm.ask({
                                                  title: 'Delete {{ addslashes($u->name) }}?',
                                                  message: 'This will permanently delete their account and all their notes.',
                                                  confirmLabel: 'Delete',
                                                  danger: true,
                                                  onConfirm: () => $el.submit()
                                              })">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="text-xs text-red-500 dark:text-red-400 hover:underline font-medium">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-sm text-slate-400 dark:text-slate-500">
                                No users found.
                            </td>
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
</x-app-layout>
