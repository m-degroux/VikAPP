<div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm max-w-xl mx-auto">
    <section>
        <header class="flex items-center gap-2 mb-6 border-b border-gray-100 pb-4">
            <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
            </svg>
            <h2 class="text-xl font-bold text-gray-800">
                {{ __('Changement de mot de passe') }}
            </h2>
        </header>

        <form method="post" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            @method('put')

            <div>
                <label for="update_password_current_password" class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">
                    {{ __('Mot de passe actuel') }}
                </label>
                <input id="update_password_current_password" name="current_password" type="password" 
                    class="form-input w-full bg-yellow-50/50 border-gray-300 focus:border-brand-green focus:ring-brand-green/20" autocomplete="current-password" />
                @if($errors->updatePassword->has('current_password'))
                    <p class="text-red-600 text-[11px] font-bold uppercase mt-1">
                        {{ $errors->updatePassword->first('current_password') }}
                    </p>
                @endif
            </div>

            <div>
                <label for="update_password_password" class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">
                    {{ __('Nouveau mot de passe') }}
                </label>
                <input id="update_password_password" name="password" type="password" 
                    class="form-input w-full bg-yellow-50/50 border-gray-300 focus:border-brand-green focus:ring-brand-green/20" autocomplete="new-password" />
                @if($errors->updatePassword->has('password'))
                    <p class="text-red-600 text-[11px] font-bold uppercase mt-1">
                        {{ $errors->updatePassword->first('password') }}
                    </p>
                @endif
            </div>

            <div>
                <label for="update_password_password_confirmation" class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">
                    {{ __('Confirmer le mot de passe') }}
                </label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                    class="form-input w-full bg-yellow-50/50 border-gray-300 focus:border-brand-green focus:ring-brand-green/20" autocomplete="new-password" />
            </div>

            <div class="pt-4">
                <button type="submit" class="btn-primary w-full py-3 rounded-full text-base shadow-sm hover:shadow-md transition-all">
                    {{ __('Mettre à jour') }}
                </button>

                @if (session('status') === 'password-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm font-medium text-green-600 italic mt-3 text-center">
                        {{ __('Sauvegardé avec succès.') }}
                    </p>
                @endif
            </div>
        </form>
    </section>
</div>