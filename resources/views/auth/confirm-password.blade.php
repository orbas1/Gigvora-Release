<x-guest-layout>
    <div class="min-h-screen bg-[var(--gv-color-neutral-50)] flex items-center justify-center px-4 py-12">
        <div class="gv-card w-full max-w-lg space-y-6">
            <div class="space-y-2 text-center">
                <p class="gv-eyebrow">{{ get_phrase('Confirm password') }}</p>
                <h1 class="gv-heading text-2xl">{{ get_phrase('Secure area confirmation') }}</h1>
                <p class="gv-muted">{{ get_phrase('Please re-enter your password to continue to this secure section of Gigvora.') }}</p>
            </div>

            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
                @csrf

                <div>
                    <x-label for="password" :value="get_phrase('Password')" />
                    <x-input id="password" class="mt-1" type="password" name="password" required autocomplete="current-password" placeholder="{{ get_phrase('Current password') }}" />
                </div>

                <div class="flex justify-end">
                    <x-button>
                        {{ get_phrase('Confirm') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
