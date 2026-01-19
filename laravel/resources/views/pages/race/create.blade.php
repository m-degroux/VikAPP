@extends('layouts.structure')

@section('content')
    <div class="max-w-4xl mx-auto py-8">
        <div class="mb-8">
            <h1 class="title-section">Gestion des Courses</h1>
            <p class="subtitle">Créez une nouvelle épreuve pour votre raid</p>
        </div>

        @if ($errors->any())
            <div style="border:1px solid red; padding:10px; margin-bottom:10px;">
                <strong>Erreurs :</strong>
                <ul>
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div style="border:1px solid green; padding:10px; margin-bottom:10px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="form-section-container">
            <h2 class="form-section-title">Ajouter une course</h2>

            <form action="{{ route('races.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <div class="space-y-8">
                        {{-- Section 1 : Informations de base --}}
                        <div class="form-section-container">
                            <h2 class="form-section-title">
                                <span class="w-1.5 h-6 bg-brand-green inline-block rounded-full"></span>
                                Informations générales
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label class="form-label">Nom de la course</label>
                                    <input type="text" name="race_name" value="{{ old('race_name') }}" required
                                        class="form-input" placeholder="Ex: Grand Trail des Volcans">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="form-label">Responsable (Licencié)</label>
                                    <select name="user_id" required class="form-input">
                                        <option value="">-- Sélectionner un responsable --</option>
                                        @foreach ($licencies as $membre)
                                            <option value="{{ $membre->user_id ?? $membre->USER_ID }}"
                                                {{ old('user_id') == ($membre->user_id ?? $membre->USER_ID) ? 'selected' : '' }}>
                                                {{ strtoupper($membre->mem_name ?? ($membre->MEM_NAME ?? 'Nom inconnu')) }}
                                                {{ $membre->mem_firstname ?? $membre->MEM_FIRSTNAME }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="form-label">Type de course</label>
                                    <select name="type_id" required class="form-input">
                                        @foreach ($types as $type)
                                            <option value="{{ $type->type_id ?? $type->TYPE_ID }}">
                                                {{ $type->type_name ?? $type->TYPE_NAME }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="form-label">Longueur (km)</label>
                                    <input type="number" step="0.01" name="race_length"
                                        value="{{ old('race_length') }}" required class="form-input">
                                </div>
                            </div>
                        </div>



                        {{-- Section 2 : Dates et Durée --}}
                        <div class="form-section-container">
                            <h2 class="form-section-title">Dates & Durée</h2>


                            <fieldset class="mb-6 rounded-md border bg-gray-100 p-5 shadow-sm">
                                <legend class="px-2 text-sm font-semibold text-gray-800">Dates du Raid :</legend>

                                <div class="mt-4 space-y-6">
                                    <div>
                                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                            <div>
                                                <label for="raid_start_date"
                                                    class="mb-1 block text-sm font-medium text-gray-700">Début
                                                    :</label>
                                                <input id="raid_start_date" name="raid_start_date" type="date"
                                                    class="block w-full rounded-md border bg-gray-200 px-3 py-2 text-sm"
                                                    value="{{ \Carbon\Carbon::parse($raid->start)->format('Y-m-d') }}"
                                                    disabled>
                                                @error('raid_start_date')
                                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label for="raid_end_date"
                                                    class="mb-1 block text-sm font-medium text-gray-700">Fin
                                                    :</label>
                                                <input id="raid_end_date" name="raid_end_date" type="date"
                                                    class="block w-full rounded-md border bg-gray-200 px-3 py-2 text-sm"
                                                    value="{{ \Carbon\Carbon::parse($raid->end)->format('Y-m-d') }}"
                                                    disabled>
                                                @error('raid_end_date')
                                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <p class="mb-2 text-sm font-semibold text-gray-800">Inscriptions :</p>
                                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                            <div>
                                                <label for="raid_reg_start_date"
                                                    class="mb-1 block text-sm font-medium text-gray-700">Ouverture
                                                    :</label>
                                                <input id="raid_reg_start_date" name="raid_reg_start_date" type="date"
                                                    class="block w-full rounded-md border bg-gray-200 px-3 py-2 text-sm"
                                                    value="{{ \Carbon\Carbon::parse($raid->start_register)->format('Y-m-d') }}"
                                                    disabled>
                                                @error('raid_reg_start_date')
                                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label for="raid_reg_end_date"
                                                    class="mb-1 block text-sm font-medium text-gray-700">Clôture
                                                    :</label>
                                                <input id="raid_reg_end_date" name="raid_reg_end_date" type="date"
                                                    class="block w-full rounded-md border bg-gray-200 px-3 py-2 text-sm"
                                                    value="{{ \Carbon\Carbon::parse($raid->end_register)->format('Y-m-d') }}"
                                                    disabled>
                                                @error('raid_reg_end_date')
                                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="form-label">Début de la course</label>
                                    <input type="datetime-local" name="race_start_date"
                                        value="{{ old('race_start_date') }}" required class="form-input">
                                </div>
                                <div>
                                    <label class="form-label">Fin de la course</label>
                                    <input type="datetime-local" name="race_end_date" value="{{ old('race_end_date') }}"
                                        required class="form-input">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="form-label">Prix repas (€)</label>
                                        <input type="number" step="0.01" name="race_meal_price"
                                            value="{{ old('race_meal_price') }}" class="form-input">
                                    </div>
                                    <div>
                                        <label class="form-label">Réduction (%)</label>
                                        <div class="relative">
                                            <input type="number" step="0.01" name="race_reduction"
                                                value="{{ old('race_reduction', 0) }}" class="form-input pr-10"
                                                placeholder="Ex: 5.5">
                                            <div
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Section 3 : Limites et Participants --}}
                        <div class="form-section-container">
                            <h2 class="form-section-title">Capacité & Équipes</h2>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="form-label">Min Part.</label>
                                    <input type="number" name="race_min_part" value="{{ old('race_min_part', 1) }}"
                                        class="form-input">
                                </div>
                                <div>
                                    <label class="form-label">Max Part.</label>
                                    <input type="number" name="race_max_part" value="{{ old('race_max_part', 100) }}"
                                        class="form-input">
                                </div>
                                <div>
                                    <label class="form-label">Min Équipes</label>
                                    <input type="number" name="race_min_team" value="{{ old('race_min_team', 1) }}"
                                        class="form-input">
                                </div>
                                <div>
                                    <label class="form-label">Max Équipes</label>
                                    <input type="number" name="race_max_team" value="{{ old('race_max_team', 50) }}"
                                        class="form-input">
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="form-label">Nombre max de personnes par équipe</label>
                                <input type="number" name="race_max_part_per_team"
                                    value="{{ old('race_max_part_per_team', 2) }}" class="form-input">
                            </div>
                        </div>

                        {{-- Section 4 : Tarifs par catégorie d'âge --}}
                        <div class="form-section-container">
                            <h2 class="form-section-title">
                                <span class="w-1.5 h-6 bg-brand-green inline-block rounded-full"></span>
                                Tarifs par catégorie d'âge
                            </h2>
                            <p class="subtitle mb-4">Cochez les catégories d'âge disponibles pour cette course et fixez
                                leur prix.</p>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                @foreach ($ageCategories as $cat)
                                    <div
                                        class="bg-gray-50 p-4 rounded-lg border border-gray-200 flex flex-col justify-between">
                                        <div class="flex items-center mb-3">
                                            {{-- Case à cocher pour activer la catégorie --}}
                                            <input type="checkbox" name="selected_ages[]" value="{{ $cat->age_id }}"
                                                id="age_{{ $cat->age_id }}"
                                                class="w-4 h-4 text-brand-green border-gray-300 rounded focus:ring-brand-green">
                                            <label for="age_{{ $cat->age_id }}"
                                                class="form-label mb-0 ml-2 cursor-pointer text-brand-dark">
                                                @if ($cat->age_min && $cat->age_max)
                                                    {{ $cat->age_min }} - {{ $cat->age_max }} ans
                                                @elseif($cat->age_min)
                                                    + {{ $cat->age_min }} ans
                                                @else
                                                    Jusqu'à {{ $cat->age_max }} ans
                                                @endif
                                            </label>
                                        </div>

                                        <div class="relative">
                                            <input type="number" step="0.01" name="prices[{{ $cat->age_id }}]"
                                                value="{{ old('prices.' . $cat->age_id) }}" class="form-input pr-8"
                                                placeholder="Prix en €">
                                            <div
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">€</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <input type="hidden" name="raid_id" value="{{ $selectedRaidId }}">
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit" class="btn-primary">
                        Enregistrer la course
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script pour gérer l'activation/désactivation des catégories d'âge --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sélectionne toutes les cartes contenant une catégorie
            const cards = document.querySelectorAll('.js-age-card');

            cards.forEach(function(card) {
                const checkbox = card.querySelector('.js-age-checkbox');
                const priceInput = card.querySelector('.js-price-input');

                if (checkbox && priceInput) {
                    // Fonction pour mettre à jour l'input de prix de CETTE carte
                    function updateCardState() {
                        if (checkbox.checked) {
                            priceInput.disabled = false;
                            priceInput.classList.remove('bg-gray-200');
                            priceInput.classList.add('bg-white');
                        } else {
                            priceInput.disabled = true;
                            priceInput.classList.add('bg-gray-200');
                            priceInput.classList.remove('bg-white');
                            // Optionnel : vider la valeur si décoché ? 
                            // priceInput.value = ''; 
                        }
                    }

                    // Initialisation au chargement
                    updateCardState();

                    // Écouteur sur changement
                    checkbox.addEventListener('change', updateCardState);
                }
            });
        });
    </script>
@endsection