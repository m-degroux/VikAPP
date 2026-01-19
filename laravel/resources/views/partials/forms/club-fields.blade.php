<div class="grid grid-cols-1 gap-6">
    {{-- Club Name field --}}
    <div>
        <label class="block text-sm font-bold text-gray-700">NOM DU CLUB</label>
        <input type="text" name="club_name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>

    {{-- Club Address (City) field --}}
    <div>
        <label class="block text-sm font-bold text-gray-700">ADRESSE (VILLE)</label>
        <input type="text" name="club_adress" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>

    {{-- Club Manager Selection: Dropdown populated with licensed members --}}
    <div>
        <label class="block text-sm font-medium text-gray-700">Responsable du club</label>
        <select name="user_id" required 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
            <option value="">-- Choisir un licencié --</option>
            @foreach($licencies as $membre)
                <option value="{{ $membre->user_id ?? $membre->USER_ID }}">
                    {{-- Handling naming conventions: prioritizing lowercase properties with uppercase fallback --}}
                    {{ strtoupper($membre->mem_name ?? $membre->MEM_NAME ?? 'Nom inconnu') }} 
                    {{ $membre->mem_firstname ?? $membre->MEM_FIRSTNAME ?? 'Prénom inconnu' }} 
                    (Licence : {{ $membre->mem_default_licence ?? $membre->MEM_DEFAULT_LICENCE }})
                </option>
            @endforeach
        </select>
        <p class="text-xs text-gray-500 mt-1 italic">* Seuls les membres possédant un numéro de licence apparaissent ici.</p>
    </div>
</div>