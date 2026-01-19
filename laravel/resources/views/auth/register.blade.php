<x-guest-layout>
    <div class="px-[15%] py-8">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12">
                
                <div>
                    <div class="mt-4">
                        <x-input-label for="mem_name" :value="__('Nom : ')" />
                        <x-text-input type="text" class="block mt-1 w-full" id="mem_name" name="mem_name" :value="old('mem_name')" required />
                        <x-input-error :messages="$errors->get('mem_name')" class="mt-2" />
                    </div>
                    
                    <div class="mt-4">
                        <x-input-label for="mem_firstname" :value="__('Prénom : ')" />
                        <x-text-input type="text" class="block mt-1 w-full" id="mem_firstname" name="mem_firstname" :value="old('mem_firstname')" required />
                        <x-input-error :messages="$errors->get('mem_firstname')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="mem_birthdate" :value="__('Date de naissance : ')" />
                        <x-text-input type="date" class="block mt-1 w-full" id="mem_birthdate" name="mem_birthdate" :value="old('mem_birthdate')" required />
                        <x-input-error :messages="$errors->get('mem_birthdate')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="mem_phone" :value="__('Téléphone : ')" />
                        <x-text-input type="tel" class="block mt-1 w-full" id="mem_phone" name="mem_phone" :value="old('mem_phone')" required />
                        <x-input-error :messages="$errors->get('mem_phone')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="mem_email" :value="__('Email')" />
                        <x-text-input id="mem_email" class="block mt-1 w-full" type="email" name="mem_email" :value="old('mem_email')" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('mem_email')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="user_username" :value="__('Pseudo')" />
                        <x-text-input id="user_username" class="block mt-1 w-full" type="text" name="user_username" :value="old('user_username')" required autofocus />
                        <x-input-error :messages="$errors->get('user_username')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <div class="mt-4">
                        <x-input-label for="mem_adress" :value="__('Adresse Postale : ')" />
                        <x-text-input type="text" class="block mt-1 w-full" id="mem_adress" name="mem_adress" :value="old('mem_adress')" required />
                        <x-input-error :messages="$errors->get('mem_adress')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="cp" :value="__('Code Postal : ')" />
                        <x-text-input type="text" class="block mt-1 w-full" id="cp" name="cp" :value="old('cp')" required />
                        <x-input-error :messages="$errors->get('mem_adress')" class="mt-2" />
                    </div>

                    <div x-data="{ isLicencie: '{{ old('is_licencie', 'non') }}' }" class="mt-4">
                        <x-input-label :value="__('Êtes-vous licencié ?')" />
                        <div class="flex items-center mt-2 space-x-6">
                            <label class="inline-flex items-center">
                                <input type="radio" name="is_licencie" value="oui" x-model="isLicencie" class="text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-600">{{ __('Oui') }}</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="is_licencie" value="non" x-model="isLicencie" class="text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-600">{{ __('Non') }}</span>
                            </label>
                        </div>

                        <div x-show="isLicencie === 'oui'" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="mt-4 p-4 bg-gray-50 rounded-lg">
                            
                            <div class="mb-4">
                                <x-input-label for="club_id" :value="__('Votre club')" />
                                <select id="club_id" name="club_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    @foreach($clubs as $club)
                                        <option value="{{ $club->club_id }}"
                                            {{ (old('club_id') == $club->id) ? 'selected' : '' }}>
                                            {{ $club->club_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('club_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="mem_default_licence" :value="__('Numéro de Licence (FFCO)')" />
                                <x-text-input type="text" id="mem_default_licence" class="block mt-1 w-full" name="mem_default_licence" :value="old('mem_default_licence')" placeholder="Ex: 1403958" />
                                <x-input-error :messages="$errors->get('mem_licence')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <x-input-label for="user_password" :value="__('Mot de passe')" />
                        <x-text-input id="user_password" class="block mt-1 w-full" type="password" name="user_password" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('user_password')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="user_password_confirmation" :value="__('Confirmer le mot de passe')" />
                        <x-text-input id="user_password_confirmation" class="block mt-1 w-full" type="password" name="user_password_confirmation" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('user_password_confirmation')" class="mt-2" />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-8 border-t pt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('Déjà inscrit ?') }}
                </a>
                <x-primary-button class="ms-4">
                    {{ __("S'inscrire") }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>