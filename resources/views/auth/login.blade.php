<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6 text-center">
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-clinic-100 text-clinic-700 border border-clinic-200/50 mb-3 uppercase tracking-wider">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Facturation
        </div>
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">{{ __('Connexion') }}</h2>
        <p class="text-sm text-gray-500 mt-1.5">Accédez au portail de facturation de votre clinique</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Username or Email -->
        <div>
            <x-input-label for="login" :value="__('Nom d\'utilisateur ou E-mail')" />
            <div class="relative rounded-xl shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/>
                    </svg>
                </div>
                <input id="login" class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 border {{ $errors->has('login') ? 'border-red-500' : 'border-gray-300' }} rounded-xl text-gray-900 text-sm focus:outline-none focus:border-clinic-500 focus:bg-white transition-all shadow-sm"
                       type="text" name="login" value="{{ old('login') }}" required autofocus autocomplete="username" placeholder="exemple@myclinic.com ou admin" />
            </div>
            <x-input-error :messages="$errors->get('login')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center mb-1.5">
                <x-input-label for="password" :value="__('Mot de passe')" class="mb-0" />
                @if (Route::has('password.request'))
                    <a class="text-xs font-semibold text-clinic-600 hover:text-clinic-700 transition-colors" href="{{ route('password.request') }}">
                        {{ __('Mot de passe oublié ?') }}
                    </a>
                @endif
            </div>
            <div class="relative rounded-xl shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <input id="password" class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }} rounded-xl text-gray-900 text-sm focus:outline-none focus:border-clinic-500 focus:bg-white transition-all shadow-sm"
                       type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" class="h-4.5 w-4.5 rounded border-gray-300 text-clinic-600 focus:ring-clinic-500 shadow-sm transition-colors cursor-pointer" name="remember">
            <label for="remember_me" class="ms-2 text-sm text-gray-600 select-none cursor-pointer">{{ __('Se souvenir de moi') }}</label>
        </div>

        <div class="mt-2">
            <button type="submit" class="w-full inline-flex items-center justify-center px-5 py-3 bg-clinic-600 border border-transparent rounded-xl font-semibold text-sm text-white tracking-wide hover:bg-clinic-700 focus:bg-clinic-700 active:bg-clinic-800 focus:outline-none focus:ring-2 focus:ring-clinic-500 focus:ring-offset-2 transition duration-150 shadow-lg shadow-clinic-500/20 hover:shadow-clinic-500/30 gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                {{ __('Se connecter') }}
            </button>
        </div>
    </form>

    <!-- Features Highlight -->
    <div class="mt-6 pt-5 border-t border-gray-200">
        <div class="grid grid-cols-2 gap-3 text-left">
            <div class="flex items-start gap-2">
                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-gray-700">Factures & Encaissements</h4>
                    <p class="text-[10px] text-gray-500 leading-tight">Émission et suivi des paiements patients</p>
                </div>
            </div>
            <div class="flex items-start gap-2">
                <div class="w-7 h-7 rounded-lg bg-clinic-100/50 text-clinic-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-gray-700">Catalogue Médical</h4>
                    <p class="text-[10px] text-gray-500 leading-tight">Actes de soins et pharmacie clinique</p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>


