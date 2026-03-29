<x-guest-layout>
    <x-slot name="title">Create Account</x-slot>

    <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-1">{{ __('messages.auth.create_account_title') }}</h2>
    <p class="text-sm text-slate-500 dark:text-slate-400 mb-8">{{ __('messages.auth.create_account_subtitle') }}</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('messages.auth.full_name') }}</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                   required autofocus autocomplete="name"
                   class="form-input @error('name') border-red-500 dark:border-red-500 @enderror">
            @error('name')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('messages.auth.email') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   required autocomplete="username"
                   class="form-input @error('email') border-red-500 dark:border-red-500 @enderror">
            @error('email')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('messages.auth.password') }}</label>
            <div x-data="{ show: false }" class="relative">
                <input id="password" :type="show ? 'text' : 'password'" name="password"
                       required autocomplete="new-password"
                       class="form-input pr-10 @error('password') border-red-500 dark:border-red-500 @enderror">
                <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors"
                        :title="show ? 'Hide password' : 'Show password'">
                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('messages.auth.confirm_password') }}</label>
            <div x-data="{ show: false }" class="relative">
                <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation"
                       required autocomplete="new-password"
                       class="form-input pr-10 @error('password_confirmation') border-red-500 dark:border-red-500 @enderror">
                <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors"
                        :title="show ? 'Hide password' : 'Show password'">
                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            @error('password_confirmation')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-primary w-full">{{ __('messages.auth.create_account') }}</button>
    </form>

    <div class="mt-6">
        <div class="relative flex items-center">
            <div class="flex-grow border-t border-slate-200 dark:border-zinc-700"></div>
            <span class="mx-3 text-xs text-slate-400 dark:text-slate-500">{{ __('messages.auth.or') }}</span>
            <div class="flex-grow border-t border-slate-200 dark:border-zinc-700"></div>
        </div>
        <button type="button" onclick="googleSignIn(this)"
                class="mt-4 flex w-full items-center justify-center gap-3 rounded-xl border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-zinc-700 transition-colors disabled:opacity-60 disabled:cursor-not-allowed">
            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            {{ __('messages.auth.continue_with_google') }}
        </button>
    </div>

    <p class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
        {{ __('messages.auth.already_account') }}
        <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 font-medium hover:underline">{{ __('messages.auth.sign_in_link') }}</a>
    </p>
</x-guest-layout>
