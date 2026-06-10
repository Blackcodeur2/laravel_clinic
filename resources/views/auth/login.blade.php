<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6 text-center">
        <h2 class="text-3xl font-bold text-gray-900 tracking-tight">{{ __('Connexion') }}</h2>
        <p class="text-sm text-gray-500 mt-1.5">Accédez à votre espace MyClinic</p>
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
                <input id="login" class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-clinic-500 focus:bg-white transition-all shadow-sm @error('login') border-red-500 @enderror" 
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
                <input id="password" class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-clinic-500 focus:bg-white transition-all shadow-sm @error('password') border-red-500 @enderror"
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
</x-guest-layout>


