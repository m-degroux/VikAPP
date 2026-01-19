<x-guest-layout>
    <div class="form-section-container w-full max-w-md mx-auto">
        <h2 class="form-section-title">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-green" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            {{ __('Connexion') }}
        </h2>

        <x-auth-session-status class="mb-4" :status="session('status')"/>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="username" class="form-label">
                    {{ __('Pseudo') }}
                </label>
                <input id="username"
                       class="form-input"
                       type="text"
                       name="username"
                       value="{{ old('username') }}"
                       required
                       autofocus
                       autocomplete="username"/>
                <x-input-error :messages="$errors->get('username')" class="mt-2"/>
            </div>

            <div class="mb-6">
                <label for="password" class="form-label">
                    {{ __('Mot de passe') }}
                </label>
                <input id="password"
                       class="form-input"
                       type="password"
                       name="password"
                       required
                       autocomplete="current-password"/>
                <x-input-error :messages="$errors->get('password')" class="mt-2"/>
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Se souvenir de moi') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-center">
                <button type="submit" class="btn-primary w-full">
                    {{ __('Se connecter') }}
                </button>
            </div>
            <br>
            <a href="{{ route('register') }}" class="block w-full">
                <div class="flex items-center justify-center font-bold py-2 px-4 rounded transition-colors">
                    <span>M'inscrire</span>
                </div>
            </a>
        </form>
    </div>
</x-guest-layout>
