<x-guest-layout>
    <div class="min-h-screen bg-[var(--gv-color-neutral-50)]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="gv-card space-y-6">
                <div class="space-y-2">
                    <p class="gv-eyebrow">{{ get_phrase('Reset password') }}</p>
                    <h1 class="gv-heading text-2xl">{{ get_phrase('Choose a new password') }}</h1>
                    <p class="gv-muted">{{ get_phrase('Create a secure password to keep your Gigvora account protected.') }}</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />
                <x-auth-validation-errors class="mb-4" :errors="$errors" />

                <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div>
                        <x-label for="email" :value="get_phrase('Email')" />
                        <x-input id="email" class="mt-1" type="email" name="email" :value="old('email', $request->email)" required autofocus />
                    </div>

                    <div>
                        <x-label for="password" :value="get_phrase('Password')" />
                        <x-input id="password" class="mt-1" type="password" name="password" required placeholder="{{ get_phrase('Create a new password') }}" />
                    </div>

                    <div>
                        <x-label for="password_confirmation" :value="get_phrase('Confirm password')" />
                        <x-input id="password_confirmation" class="mt-1" type="password" name="password_confirmation" required placeholder="{{ get_phrase('Re-enter password') }}" />
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                        <x-button class="justify-center w-full sm:w-auto">
                            {{ get_phrase('Reset password') }}
                        </x-button>
                        <a class="gv-btn gv-btn-ghost justify-center w-full sm:w-auto" href="{{ route('login') }}">
                            {{ get_phrase('Back to login') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>