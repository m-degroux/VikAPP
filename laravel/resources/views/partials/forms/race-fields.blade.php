@php
    /**
     * Logic to find the selected Raid and its start date.
     * We handle both lowercase/uppercase keys to support different database naming conventions.
     */
    $currentRaid = $raids->firstWhere('raid_id', $selectedRaidId) ?? $raids->firstWhere('RAID_ID', $selectedRaidId);
    
    /**
     * If the raid is found, format the dates for the 'datetime-local' input (Y-m-d\TH:i).
     */
    $defaultStartDate = $currentRaid 
        ? \Carbon\Carbon::parse($currentRaid->raid_start_date ?? $currentRaid->RAID_START_DATE)->format('Y-m-d\TH:i') 
        : '';
        
    $defaultEndDate = $currentRaid 
        ? \Carbon\Carbon::parse($currentRaid->raid_end_date ?? $currentRaid->RAID_END_DATE)->format('Y-m-d\TH:i') 
        : '';
@endphp

<div class="space-y-8">
    {{-- Section 1: Basic Information --}}
    <div class="form-section-container">
        <h2 class="form-section-title">
            <span class="w-1.5 h-6 bg-brand-green inline-block rounded-full"></span>
            Informations générales
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="form-label">Nom de la course</label>
                <input type="text" name="race_name" value="{{ old('race_name') }}" required class="form-input" placeholder="Ex: Grand Trail des Volcans">
            </div>

            <div class="md:col-span-2">
                {{-- Manager Selection: Populated with licensed members --}}
                <label class="form-label">Responsable (Licencié)</label>
                <select name="user_id" required class="form-input">
                    <option value="">-- Sélectionner un responsable --</option>
                    @foreach($licencies as $membre)
                        <option value="{{ $membre->user_id ?? $membre->USER_ID }}" {{ old('user_id') == ($membre->user_id ?? $membre->USER_ID) ? 'selected' : '' }}>
                            {{ strtoupper($membre->mem_name ?? $membre->MEM_NAME ?? 'Nom inconnu') }} {{ $membre->mem_firstname ?? $membre->MEM_FIRSTNAME }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">Type de course</label>
                <select name="type_id" required class="form-input">
                    @foreach($types as $type)
                        <option value="{{ $type->type_id ?? $type->TYPE_ID }}">{{ $type->type_name ?? $type->TYPE_NAME }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">Longueur (km)</label>
                <input type="number" step="0.01" name="race_length" value="{{ old('race_length') }}" required class="form-input">
            </div>
        </div>
    </div>

    {{-- Section 2: Dates, Duration and Annex Prices --}}
    <div class="form-section-container">
        <h2 class="form-section-title">Dates & Options</h2>
        {{-- Unified grid to maintain alignment across the section --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            {{-- Start Date: Pre-filled with the parent Raid's date --}}
            <div>
                <label class="form-label">Début de la course</label>
                <input type="datetime-local" name="race_start_date" 
                       value="{{ old('race_start_date', $defaultStartDate) }}" 
                       required class="form-input">
                <p class="text-xs text-gray-500 mt-1">Suggéré selon la date du raid.</p>
            </div>

            {{-- End Date --}}
            <div>
                <label class="form-label">Fin de la course</label>
                <input type="datetime-local" name="race_end_date" 
                       value="{{ old('race_end_date', $defaultEndDate) }}" 
                       required class="form-input">
            </div>

            {{-- Meal Price --}}
            <div>
                <label class="form-label">Prix repas (€)</label>
                <input type="number" step="0.01" name="race_meal_price" value="{{ old('race_meal_price') }}" class="form-input" placeholder="Ex: 12.00">
            </div>

            {{-- Discount Percentage --}}
            <div>
                <label class="form-label">Réduction (%)</label>
                <div class="relative">
                    <input type="number" step="0.01" name="race_reduction" 
                        value="{{ old('race_reduction', 0) }}" 
                        class="form-input pr-10" placeholder="Ex: 5.5">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 3: Limits and Participant Constraints --}}
    <div class="form-section-container">
        <h2 class="form-section-title">Capacité & Équipes</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <label class="form-label">Min Part.</label>
                <input type="number" name="race_min_part" value="{{ old('race_min_part', 1) }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Max Part.</label>
                <input type="number" name="race_max_part" value="{{ old('race_max_part', 100) }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Min Équipes</label>
                <input type="number" name="race_min_team" value="{{ old('race_min_team', 1) }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Max Équipes</label>
                <input type="number" name="race_max_team" value="{{ old('race_max_team', 50) }}" class="form-input">
            </div>
        </div>
        <div class="mt-4">
            <label class="form-label">Nombre max de personnes par équipe</label>
            <input type="number" name="race_max_part_per_team" value="{{ old('race_max_part_per_team', 2) }}" class="form-input">
        </div>
    </div>

    {{-- Section 4: Pricing by Age Category --}}
    <div class="form-section-container">
        <h2 class="form-section-title">
            <span class="w-1.5 h-6 bg-brand-green inline-block rounded-full"></span>
            Tarifs par catégorie d'âge
        </h2>
        <p class="subtitle mb-4">Cochez les catégories d'âge disponibles pour cette course et fixez leur prix.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($ageCategories as $cat)
                {{-- Category card with JS selectors for interactive state management --}}
                <div class="js-age-card bg-gray-50 p-4 rounded-lg border border-gray-200 flex flex-col justify-between transition-colors duration-300">
                    <div class="flex items-center mb-3">
                        {{-- JS Hook: Checkbox to enable category --}}
                        <input type="checkbox" name="selected_ages[]" value="{{ $cat->age_id }}" 
                            id="age_{{ $cat->age_id }}" 
                            class="js-age-checkbox w-4 h-4 text-brand-green border-gray-300 rounded focus:ring-brand-green">
                        <label for="age_{{ $cat->age_id }}" class="form-label mb-0 ml-2 cursor-pointer text-brand-dark">
                            @if($cat->age_min && $cat->age_max)
                                {{ $cat->age_min }} - {{ $cat->age_max }} ans
                            @elseif($cat->age_min)
                                + {{ $cat->age_min }} ans
                            @else
                                Jusqu'à {{ $cat->age_max }} ans
                            @endif
                        </label>
                    </div>
                    
                    <div class="relative">
                        {{-- JS Hook: Price input disabled unless category is checked --}}
                        <input type="number" step="0.01" 
                            name="prices[{{ $cat->age_id }}]" 
                            value="{{ old('prices.'.$cat->age_id) }}" 
                            class="js-price-input form-input pr-8 disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed" 
                            placeholder="Prix en €">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">€</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Hidden field to associate the race with the correct Raid --}}
    <input type="hidden" name="raid_id" value="{{ $selectedRaidId }}">
</div>