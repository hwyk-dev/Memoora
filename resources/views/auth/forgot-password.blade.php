<x-guest-layout>
    <x-slot name="title">Forgot Password</x-slot>

    <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-1">Forgot password?</h2>
    <p class="text-sm text-slate-500 dark:text-slate-400 mb-8">Enter your email and we'll send you a reset link.</p>

    @if (session('status'))
        <div class="mb-6 px-4 py-3 bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 rounded-xl text-sm text-emerald-700 dark:text-emerald-400">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   required autofocus
                   class="form-input @error('email') border-red-500 dark:border-red-500 @enderror">
            @error('email')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-primary w-full">Send reset link</button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
        <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 font-medium hover:underline">Back to sign in</a>
    </p>
</x-guest-layout>
