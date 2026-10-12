<section>
    <header class="flex items-start gap-3">
        <span class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-primary-fixed text-on-primary-fixed">
            <x-icon name="user-round" class="size-[20px]" />
        </span>
        <div class="min-w-0">
            <h2 class="text-title-md font-bold text-on-surface">
                {{ __('Profile Information') }}
            </h2>

            <p class="mt-1 text-body-md text-secondary">
                {{ __("Update your account's profile information and email address.") }}
            </p>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="mt-2 text-body-md text-on-surface-variant">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="rounded-md text-body-md text-primary-container underline hover:opacity-80 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-body-md text-tertiary">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex flex-wrap items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-body-md text-tertiary"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
