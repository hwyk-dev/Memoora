<x-app-layout>
    <x-slot name="title">{{ __('messages.settings.title') }}</x-slot>
    <x-slot name="header">
        <h1 class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ __('messages.settings.title') }}</h1>
    </x-slot>

    <div class="max-w-2xl space-y-6">

        {{-- Profile information --}}
        <div class="card">
            <h2 class="text-base font-semibold text-slate-900 dark:text-slate-100 mb-1">{{ __('messages.settings.profile_title') }}</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">{{ __('messages.settings.profile_desc') }}</p>

            <form method="POST" action="{{ route('settings.profile.update') }}" class="space-y-5">
                @csrf @method('PATCH')

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('messages.settings.full_name') }}</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}"
                           required autocomplete="name"
                           class="form-input @error('name') border-red-500 dark:border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('messages.settings.email') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}"
                           required autocomplete="username"
                           class="form-input @error('email') border-red-500 dark:border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="btn-primary">{{ __('messages.settings.save_changes') }}</button>
                    @if (session('status') === 'profile-updated')
                        <span class="text-sm text-emerald-600 dark:text-emerald-400">{{ __('messages.settings.saved') }}</span>
                    @endif
                </div>
            </form>
        </div>

        {{-- Update password --}}
        <div class="card">
            <h2 class="text-base font-semibold text-slate-900 dark:text-slate-100 mb-1">{{ __('messages.settings.password_title') }}</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">{{ __('messages.settings.password_desc') }}</p>

            <form method="POST" action="{{ route('settings.password.update') }}" class="space-y-5">
                @csrf @method('PATCH')

                <div>
                    <label for="current_password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('messages.settings.current_password') }}</label>
                    <input id="current_password" type="password" name="current_password"
                           autocomplete="current-password"
                           class="form-input @error('current_password', 'updatePassword') border-red-500 dark:border-red-500 @enderror">
                    @error('current_password', 'updatePassword')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('messages.settings.new_password') }}</label>
                    <input id="password" type="password" name="password"
                           autocomplete="new-password"
                           class="form-input @error('password', 'updatePassword') border-red-500 dark:border-red-500 @enderror">
                    @error('password', 'updatePassword')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('messages.settings.confirm_new_password') }}</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                           autocomplete="new-password"
                           class="form-input @error('password_confirmation', 'updatePassword') border-red-500 dark:border-red-500 @enderror">
                    @error('password_confirmation', 'updatePassword')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-primary">{{ __('messages.settings.update_password') }}</button>
            </form>
        </div>

        {{-- Language --}}
        <div class="card">
            <h2 class="text-base font-semibold text-slate-900 dark:text-slate-100 mb-1">{{ __('messages.settings.language_title') }}</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">{{ __('messages.settings.language_desc') }}</p>

            <form method="POST" action="{{ route('settings.locale.update') }}" class="space-y-4">
                @csrf @method('PATCH')
                <div class="flex flex-wrap gap-3">
                    @foreach (['en' => __('messages.languages.en'), 'ja' => __('messages.languages.ja'), 'my' => __('messages.languages.my')] as $code => $label)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="locale" value="{{ $code }}"
                                   {{ $user->locale === $code ? 'checked' : '' }}
                                   class="text-indigo-600 focus:ring-indigo-500 dark:bg-zinc-800">
                            <span class="text-sm text-slate-700 dark:text-slate-300">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" class="btn-primary">{{ __('messages.settings.save_language') }}</button>
                    @if (session('status') === 'locale-updated')
                        <span class="text-sm text-emerald-600 dark:text-emerald-400">{{ __('messages.settings.saved') }}</span>
                    @endif
                </div>
            </form>
        </div>

        {{-- Delete account --}}
        <div class="card border-red-200 dark:border-red-900/50">
            <h2 class="text-base font-semibold text-red-700 dark:text-red-400 mb-1">{{ __('messages.settings.delete_title') }}</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
                {{ __('messages.settings.delete_desc') }}
            </p>

            <div x-data="{ open: false }">
                <button @click="open = true" type="button"
                        class="px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 border border-red-300 dark:border-red-700 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                    {{ __('messages.settings.delete_btn') }}
                </button>

                {{-- Confirmation modal --}}
                <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" style="display:none"
                     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100">
                    <div @click.outside="open = false"
                         class="w-full max-w-md bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-800 shadow-xl p-6"
                         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100">
                        <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100 mb-2">{{ __('messages.settings.delete_confirm_title') }}</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-5">
                            {{ __('messages.settings.delete_confirm_desc') }}
                        </p>
                        <form method="POST" action="{{ route('settings.destroy') }}" class="space-y-4">
                            @csrf @method('DELETE')
                            <div>
                                <label for="delete_password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('messages.auth.password') }}</label>
                                <input id="delete_password" type="password" name="password"
                                       class="form-input @error('password', 'userDeletion') border-red-500 dark:border-red-500 @enderror">
                                @error('password', 'userDeletion')
                                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex items-center gap-3 justify-end">
                                <button type="button" @click="open = false" class="btn-secondary">{{ __('messages.settings.cancel') }}</button>
                                <button type="submit"
                                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                                    {{ __('messages.settings.delete_confirm_btn') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
