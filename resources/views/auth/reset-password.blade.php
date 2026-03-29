<x-guest-layout>
    <x-slot name="title">Reset Password</x-slot>

    <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-1">Reset your password</h2>
    <p class="text-sm text-slate-500 dark:text-slate-400 mb-8">Enter a new password for your account.</p>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
                   required autofocus autocomplete="username"
                   class="form-input @error('email') border-red-500 dark:border-red-500 @enderror">
            @error('email')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">New password</label>
            <input id="password" type="password" name="password"
                   required autocomplete="new-password"
                   class="form-input @error('password') border-red-500 dark:border-red-500 @enderror">
            @error('password')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Confirm new password</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   required autocomplete="new-password"
                   class="form-input @error('password_confirmation') border-red-500 dark:border-red-500 @enderror">
            @error('password_confirmation')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-primary w-full">Reset password</button>
    </form>
</x-guest-layout>
