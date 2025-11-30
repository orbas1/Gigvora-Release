<x-guest-layout>
    <div class="min-h-screen bg-[var(--gv-color-neutral-50)]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="gv-card space-y-6">
                <div class="space-y-2">
                    <p class="gv-eyebrow">{{ get_phrase('Reset password') }}</p>
                    <h1 class="gv-heading text-2xl">{{ get_phrase('We’ll send you a reset link') }}</h1>
                    <p class="gv-muted">{{ get_phrase('Enter the email you use for Gigvora and we’ll send you instructions to reset your password.') }}</p>
                </div>

                @if(session('status'))
                    <div class="gv-section bg-[var(--gv-color-success)]/5 border-[var(--gv-color-success)] text-[var(--gv-color-success)]">
                        <x-auth-session-status :status="session('status')" />
                    </div>
                @endif

                <x-auth-validation-errors class="mb-4" :errors="$errors" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <div>
                        <x-label for="email" :value="get_phrase('Email')" />
                        <x-input id="email" class="mt-1" type="email" name="email" :value="old('email')" required autofocus placeholder="{{ get_phrase('name@company.com') }}" />
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                        <x-button class="justify-center w-full sm:w-auto">
                            {{ get_phrase('Send reset link') }}
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