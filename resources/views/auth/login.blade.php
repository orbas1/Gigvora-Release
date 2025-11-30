<x-guest-layout>
    <div class="min-h-screen bg-[var(--gv-color-neutral-50)]">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid gap-10 lg:grid-cols-2 items-center">
                <div class="hidden lg:block">
                    <div class="gv-card h-full flex flex-col justify-center text-center space-y-4">
                        <p class="gv-eyebrow">{{ get_phrase('Welcome back') }}</p>
                        <h1 class="gv-heading text-3xl">{{ get_phrase('Access your Gigvora workspace') }}</h1>
                        <p class="gv-muted">{{ get_phrase('Collaborate across feed, jobs, gigs, and live events without leaving one dashboard.') }}</p>
                        <img class="mx-auto max-w-sm" src="{{ asset('assets/frontend/images/login.png') }}" alt="{{ get_phrase('Login illustration') }}">
                    </div>
                </div>

                <div class="gv-card space-y-6">
                    <div>
                        <p class="gv-eyebrow">{{ get_phrase('Log in') }}</p>
                        <h2 class="gv-heading text-2xl">{{ get_phrase('Enter your credentials') }}</h2>
                    </div>

                    @if($message = Session::get('error_message'))
                        <div class="gv-section bg-[var(--gv-color-warning)]/5 border-[var(--gv-color-warning)] text-[var(--gv-color-warning)] space-y-1">
                            <strong>{{ get_phrase('Public sign ups are disabled') }}</strong>
                            <p>{{ get_phrase('Please contact the site administrator to enable your account.') }}</p>
                        </div>
                    @endif

                    <x-auth-session-status class="mb-4" :status="session('status')" />
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <div>
                            <x-label for="email" :value="get_phrase('Email')" />
                            <x-input id="email" class="mt-1" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="{{ get_phrase('name@company.com') }}" />
                        </div>

                        <div>
                            <div class="flex items-center justify-between">
                                <x-label for="password" :value="get_phrase('Password')" />
                                @if (Route::has('password.request'))
                                    <a class="gv-link text-sm" href="{{ route('password.request') }}">
                                        {{ get_phrase('Forgot password?') }}
                                    </a>
                                @endif
                            </div>
                            <x-input id="password" class="mt-1" type="password" name="password" required autocomplete="current-password" placeholder="{{ get_phrase('••••••••') }}" />
                        </div>

                        <div class="flex items-center gap-2">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[var(--gv-color-primary-600)] focus:ring-[var(--gv-color-primary-600)]" name="remember">
                            <label for="remember_me" class="text-sm text-[var(--gv-color-neutral-600)]">{{ get_phrase('Remember me') }}</label>
                        </div>

                        <div class="space-y-3">
                            <x-button class="w-full justify-center">
                                {{ get_phrase('Log in') }}
                            </x-button>
                            @if (Route::has('register'))
                                <p class="text-sm text-center text-[var(--gv-color-neutral-500)]">
                                    {{ get_phrase('Need an account?') }}
                                    <a class="gv-link" href="{{ route('register') }}">{{ get_phrase('Sign up') }}</a>
                                </p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

