@extends('layouts.structure')
@section('title', 'Gestion - ' . $race->race_name)
@section('content')

    {{-- Hero Section: Reusable component displaying the race name and its current management purpose --}}
    @include('partials.hero-section', ['pageTitle' => $race->race_name, 'pageSubTitle' => 'Gestion des émargements et justificatifs', 'imageUrl' => asset('img/heroSection/header.jpg')])

    <div class="admin-table-container p-6">
        <table class="admin-table w-full border-collapse">
            <thead>
                <tr class="bg-gray-800 text-white">
                    <th class="p-3 text-left">Équipe / Participants</th>
                    <th class="p-3 text-left">Paiement</th>
                    <th class="p-3 text-left">Médical / Licence</th>
                    <th class="p-3 text-center">Présence</th>
                    <th class="p-3 text-left">N° Dossard</th>
                    <th class="p-3 text-left">Action</th>
                </tr>
            </thead>
            <tbody>

            {{-- Loop through teams grouped by team_id --}}
            @foreach($members as $teamId => $teamMembers)
                @php 
                    $teamName = $teamMembers->first()->team_name ?? 'Sans Équipe'; 
                @endphp

                {{-- Team Header Row: Displays team ID, name, and the "Validate Team" action --}}
                <tr class="row-team bg-gray-200 font-bold border-t-2 border-gray-400">
                    <td class="uppercase text-gray-900 py-3 px-4">
                        <span class="bg-blue-600 text-white px-2 py-1 rounded mr-2 text-xs">ID {{ $teamId ?: 'N/A' }}</span>
                        {{ $teamName }}
                    </td>
                    <td colspan="3"></td>
                    {{-- Automatic bib number generation (static logic for example) --}}
                    <td class="text-green-700 font-mono">Dossard #{{ 1000 + (int) ($teamId ?? 0) }}</td>
                    <td>
                        <form action="{{ route('manage.races.update', $race->race_id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="team_id" value="{{ $teamId }}">
                            <button type="submit" class="btn-table-action uppercase text-xs bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded shadow-sm">
                                Valider Équipe
                            </button>
                        </form>
                    </td>
                </tr>

                {{-- Participant Rows: Displays individual member details and status checkboxes --}}
                @foreach($teamMembers as $member)
                    <tr class="row-participant border-b hover:bg-gray-50">
                        <td class="pl-12 py-3">
                            <span class="text-gray-400 mr-2">└</span>
                            {{ strtoupper($member->mem_name) }} {{ $member->mem_firstname }}
                        </td>
                        <td>
                            {{-- AJAX-enabled checkbox for individual payment status --}}
                            <label class="flex items-center gap-2 text-sm cursor-pointer">
                                <input type="checkbox" 
                                    class="ajax-update rounded border-gray-300 text-blue-600" 
                                    data-user="{{ $member->user_id }}" 
                                    data-field="jrace_payement_valid"
                                    {{ ($member->jrace_payement_valid ?? 0) == 1 ? 'checked' : '' }}>
                                
                                <span class="status-label {{ ($member->jrace_payement_valid ?? 0) == 1 ? 'text-green-600' : 'text-red-500' }}">
                                    {{ ($member->jrace_payement_valid ?? 0) == 1 ? 'Réglé' : 'À Régler' }}
                                </span>
                            </label>
                        </td>
                        <td>
                            {{-- Proof of registration check: License number or PPS code --}}
                            @if(!empty($member->jrace_licence_num))
                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded border border-blue-200">
                                    Licence : {{ $member->jrace_licence_num }}
                                </span>
                            @elseif(!empty($member->jrace_pps))
                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded border border-green-200">
                                    PPS : {{ $member->jrace_pps }}
                                </span>
                            @else
                                <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded border border-red-200">
                                    Justificatif Manquant
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            {{-- AJAX-enabled checkbox for physical presence on-site --}}
                            <input type="checkbox" 
                                class="ajax-update w-5 h-5 rounded border-gray-300 text-green-600" 
                                data-user="{{ $member->user_id }}" 
                                data-field="jrace_presence_valid"
                                {{ ($member->jrace_presence_valid ?? 0) == 1 ? 'checked' : '' }}>
                        </td>
                        <td class="text-gray-400 text-sm italic">— participant</td>
                        <td class="text-gray-400 text-sm">—</td>
                    </tr>
                @endforeach

                {{-- Visual spacing between teams --}}
                <tr class="h-4 bg-white"><td colspan="6"></td></tr> 
            @endforeach
            </tbody>
        </table>
    </div>

    {{-- Results Import Section: Upload CSV to update team rankings and times --}}
    <div class="admin-table-container mb-8">
        <h3 class="form-section-title">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
            </svg>
            Importer les résultats de la course
        </h3>
        
        <form action="{{ route('manage.races.importCSV', $race->race_id) }}" method="POST" enctype="multipart/form-data" class="flex items-end gap-4">
            @csrf
            <div class="flex-1">
                <label for="csv_file" class="form-label">Fichier CSV des résultats</label>
                <input type="file" name="csv_file" id="csv_file" accept=".csv, .txt" class="form-input" required>
            </div>
            
            <button type="submit" class="btn-primary">
                Lancer l'importation
            </button>
        </form>
        
        <p class="subtitle mt-2 text-xs">
            Note : Le système recherchera les équipes par leur nom exact pour mettre à jour les points et le temps.
        </p>
    </div>

    {{-- AJAX script to handle status updates (Payment/Presence) without page refresh --}}
    <script>
        document.querySelectorAll('.ajax-update').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const userId = this.dataset.user;
                const field = this.dataset.field;
                const value = this.checked ? 1 : 0;
                const label = this.parentElement.querySelector('.status-label');

                fetch("{{ route('manage.races.update', $race->race_id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        // Laravel method spoofing for PUT request via AJAX
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        field: field,
                        value: value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && label) {
                        // Visual UI update for the payment status label
                        if (field === 'jrace_payement_valid') {
                            label.innerText = value === 1 ? 'Réglé' : 'À Régler';
                            label.className = 'status-label ' + (value === 1 ? 'text-green-600' : 'text-red-500');
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>
@endsection