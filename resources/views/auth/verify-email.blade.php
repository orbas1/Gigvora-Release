<x-guest-layout>
    <div class="min-h-screen bg-[var(--gv-color-neutral-50)]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="gv-card space-y-6 text-center">
                <img class="mx-auto max-w-xs" src="{{ asset('assets/frontend/images/login.png') }}" alt="{{ get_phrase('Verification illustration') }}">
                <div class="space-y-2">
                    <p class="gv-eyebrow">{{ get_phrase('Verify your email') }}</p>
                    <h1 class="gv-heading text-2xl">{{ get_phrase('One last step before you start') }}</h1>
                    <p class="gv-muted">{{ get_phrase('We just sent a verification link to your inbox. Click it to activate your Gigvora account. Didn’t receive anything? Request a new link below.') }}</p>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="gv-section bg-[var(--gv-color-success)]/5 border-[var(--gv-color-success)] text-[var(--gv-color-success)]">
                        {{ get_phrase('A new verification link has been sent to your email address.') }}
                    </div>
                @endif

                <div class="grid gap-3 sm:grid-cols-2">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <x-button class="w-full justify-center">
                            {{ get_phrase('Resend verification email') }}
                        </x-button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="gv-btn gv-btn-ghost w-full justify-center">
                            {{ get_phrase('Log out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
