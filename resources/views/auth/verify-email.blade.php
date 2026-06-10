<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900">{{ __('Vérifiez votre email') }}</h2>
        <p class="text-sm text-gray-400 mt-1 leading-relaxed">
            {{ __('Merci pour votre inscription ! Avant de commencer, veuillez valider votre adresse email en cliquant sur le lien que nous venons de vous envoyer.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-sm font-medium text-emerald-700 flex items-start gap-2.5">
            <i class="bi bi-envelope-check-fill text-lg mt-0.5"></i>
            <div>
                {{ __('Un nouveau lien de vérification a été envoyé à l\'adresse email fournie lors de l\'inscription.') }}
            </div>
        </div>
    @endif

    <div class="mt-6 flex flex-col sm:flex-row items-center gap-4 justify-between">
        <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
            @csrf
            <x-primary-button class="w-full sm:w-auto">
                {{ __('Renvoyer l\'email') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
            @csrf
            <button type="submit" class="w-full text-center text-sm font-semibold text-gray-400 hover:text-gray-900 transition-colors py-2 px-4 rounded-xl border border-gray-200 hover:bg-gray-200">
                {{ __('Se déconnecter') }}
            </button>
        </form>
    </div>
</x-guest-layout>

