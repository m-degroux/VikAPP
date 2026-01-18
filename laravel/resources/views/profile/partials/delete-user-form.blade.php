<div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm">
    <section class="space-y-6">
        <header class="flex items-center gap-2 mb-6 border-b border-gray-100 pb-4">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            <h2 class="text-xl font-bold text-gray-800">
                {{ __('Suppression de compte') }}
            </h2>
        </header>

        <p class="mt-1 text-sm text-gray-600 italic">
            {{ __('Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées. Veuillez télécharger les données que vous souhaitez conserver avant de procéder.') }}
        </p>

        <button 
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="bg-red-600 text-white px-6 py-2 rounded-full font-semibold hover:bg-red-700 transition-all shadow-sm"
        >
            {{ __('Supprimer le compte') }}
        </button>

        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
            <form method="post" action="{{ route('profile.destroy') }}" class="p-8 bg-white rounded-xl">
                @csrf
                @method('delete')

                <h2 class="text-lg font-bold text-gray-800">
                    {{ __('Êtes-vous sûr de vouloir supprimer votre compte ?') }}
                </h2>

                <p class="mt-3 text-sm text-gray-600">
                    {{ __('Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées. Veuillez saisir votre mot de passe pour confirmer la suppression définitive.') }}
                </p>

                <div class="mt-6">
                    <label for="password" class="form-label">{{ __('Mot de passe') }}</label>

                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="form-input mt-1 block w-full bg-yellow-50/30"
                        placeholder="{{ __('Saisissez votre mot de passe') }}"
                    />

                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close')" class="btn-outline px-6">
                        {{ __('Annuler') }}
                    </button>

                    <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-full font-bold hover:bg-red-700 transition-all">
                        {{ __('Confirmer la suppression') }}
                    </button>
                </div>
            </form>
        </x-modal>
    </section>
</div>