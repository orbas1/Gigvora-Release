<x-guest-layout>
    <div class="min-h-screen bg-[var(--gv-color-neutral-50)]">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid gap-10 lg:grid-cols-2 items-center">
                <div class="hidden lg:block">
                    <div class="gv-card h-full flex flex-col justify-center text-center space-y-4">
                        <p class="gv-eyebrow">{{ get_phrase('Join Gigvora') }}</p>
                        <h1 class="gv-heading text-3xl">{{ get_phrase('Create your account') }}</h1>
                        <p class="gv-muted">{{ get_phrase('Start collaborating across feed, jobs, gigs, live events, and utilities from day one.') }}</p>
                        <img class="mx-auto max-w-sm" src="{{ asset('assets/frontend/images/login.png') }}" alt="{{ get_phrase('Signup illustration') }}">
                    </div>
                </div>

                <div class="gv-card space-y-6">
                    <div>
                        <p class="gv-eyebrow">{{ get_phrase('Sign up') }}</p>
                        <h2 class="gv-heading text-2xl">{{ get_phrase('Tell us about you') }}</h2>
                    </div>

                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form action="{{ route('register') }}" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" name="timezone" id="timezone" value="">

                        <div>
                            <x-label for="name" :value="get_phrase('Full name')" />
                            <x-input id="name" class="mt-1" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="{{ get_phrase('Your full name') }}" />
                        </div>

                        <div>
                            <x-label for="email" :value="get_phrase('Email')" />
                            <x-input id="email" class="mt-1" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="{{ get_phrase('name@company.com') }}" />
                        </div>

                        <div>
                            <x-label for="password" :value="get_phrase('Password')" />
                            <x-input id="password" class="mt-1" type="password" name="password" required autocomplete="new-password" placeholder="{{ get_phrase('Create a password') }}" />
                        </div>

                        <div>
                            <x-label for="password_confirmation" :value="get_phrase('Confirm password')" />
                            <x-input id="password_confirmation" class="mt-1" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="{{ get_phrase('Re-enter password') }}" />
                        </div>

                        <div class="flex items-start gap-2">
                            <input type="checkbox" class="mt-1 rounded border-gray-300 text-[var(--gv-color-primary-600)] focus:ring-[var(--gv-color-primary-600)]" name="check1" id="terms_check">
                            <label class="text-sm text-[var(--gv-color-neutral-600)]" for="terms_check">
                                {{ get_phrase('I accept the') }}
                                <a href="{{ route('term.view') }}" class="gv-link">{{ get_phrase('Terms & Conditions') }}</a>
                            </label>
                        </div>

                        <div class="space-y-3">
                            <x-button class="w-full justify-center">
                                {{ get_phrase('Create account') }}
                            </x-button>
                            <p class="text-sm text-center text-[var(--gv-color-neutral-500)]">
                                {{ get_phrase('Already have an account?') }}
                                <a class="gv-link" href="{{ route('login') }}">{{ get_phrase('Log in') }}</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const tzField = document.getElementById('timezone');
                if (tzField && Intl && Intl.DateTimeFormat) {
                    tzField.value = Intl.DateTimeFormat().resolvedOptions().timeZone ?? '';
                }
            });
        </script>
    @endpush
</x-guest-layout>