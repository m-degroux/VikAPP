<div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm">
    <section class="color-black">
        <header class="flex items-center gap-2 mb-6 border-b border-gray-100 pb-4">
            <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <h2 class="text-xl font-bold text-brand-dark">
                {{ __('Informations de votre profil') }}
            </h2>
        </header>

        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-4">
            @csrf
            @method('patch')

            @include('partials.forms.input', [
                'name' => 'mem_username',
                'label' => 'Nom d\'utilisateur',
                'value' => $user->user_username,
            ])

            <div class="grid grid-cols-2 gap-4">
                @include('partials.forms.input', [
                    'name' => 'mem_firstname',
                    'label' => 'Prénom',
                    'value' => $user->mem_firstname
                ])

                @include('partials.forms.input', [
                    'name' => 'mem_name',
                    'label' => 'Nom',
                    'value' => $user->mem_name
                ])
            </div>

            @include('partials.forms.input', [
                'name' => 'mem_email',
                'label' => 'Email',
                'value' => $user->mem_email
            ])

            <div class="grid grid-cols-2 gap-4">
                @include('partials.forms.input', [
                    'name' => 'mem_birthdate',
                    'label' => 'Date de naissance',
                    'type' => 'date',
                    'value' => $user->mem_birthdate
                ])

                @include('partials.forms.input', [
                    'name' => 'mem_phone',
                    'label' => 'Téléphone',
                    'value' => $user->mem_phone,
                ])
            </div>

            @include('partials.forms.input', [
                'name' => 'mem_adress',
                'label' => 'Adresse',
                'value' => $user->mem_adress,
            ])

            <div class="grid grid-cols-2 gap-4">
                @include('partials.forms.input', [
                    'name' => 'mem_default_licence',
                    'label' => 'Licence',
                    'value' => $user->mem_default_licence,
                ])

                @if (!empty($clubs))
                    @include('partials.forms.select', [
                        'name' => 'club_id',
                        'label' => 'Votre Club',
                        'value' => $user->club_id,
                        'options' => $clubs
                    ])
                @endif
            </div>

            <div class="flex items-center gap-4 mt-8">
                <button type="submit" class="btn-primary w-full sm:w-auto px-10 py-3 rounded-full shadow-sm">
                    {{ __('Sauvegarder les modifications') }}
                </button>

                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm font-medium text-green-600 italic">{{ __('Sauvegardé !') }}</p>
                @endif
            </div>
        </form>
    </section>
</div>