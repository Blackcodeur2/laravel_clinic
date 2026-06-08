<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-slate-900">{{ __('Mot de passe oublié ?') }}</h2>
        <p class="text-sm text-slate-500 mt-1 leading-relaxed">
            {{ __('Indiquez votre adresse e-mail et nous vous enverrons un lien de réinitialisation de mot de passe.') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full">
                {{ __('Envoyer le lien de réinitialisation') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-6 text-center text-sm text-slate-500 border-t border-slate-100 pt-6">
        <a href="{{ route('login') }}" class="font-semibold text-clinic-600 hover:text-clinic-700 transition-colors">
            {{ __('Retour à la connexion') }}
        </a>
    </div>
</x-guest-layout>

