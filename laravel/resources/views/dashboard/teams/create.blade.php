@extends('layouts.structure')

@section('content')
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="title-section">Inscription à la course</h1>

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


        <form method="post" action="{{ route('teams.store') }}">
            @csrf

            <input type="hidden" name="race_id" value="{{ request('run') }}">

            <div class="space-y-8">
                <fieldset class="border border-gray-300 p-6 rounded-sm relative mb-8">
                    <legend class="px-2 text-sm font-semibold text-gray-700">Étape A : L'Équipe</legend>
                    <div class="space-y-4 mt-2">
                        <div>
                            <label class="block font-bold text-brand-dark mb-1">Nom de l'équipe :</label>
                            <input type="text" name="team_name" placeholder="Ex: Les Lynx Normands"
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-brand-green focus:border-brand-green">
                        </div>
                    </div>
                </fieldset>

                <div class="space-y-8">
                    <fieldset class="border border-gray-300 p-6 rounded-sm relative">
                        <legend class="px-2 text-sm font-semibold text-gray-700">Étape B : Les Participants</legend>
                        <div id="participants-container" class="space-y-8">
                            <fieldset class="participant-block border border-gray-300 p-6 rounded-sm relative">
                                <h3 class="participant-title text-xl font-bold text-brand-dark mb-4">Participant n°1</h3>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block font-bold text-brand-dark mb-1">Pseudo :</label>
                                        <input type="text" name="participants[1][pseudo]" placeholder="Ex: Charlou64"
                                            class="w-full border border-gray-300 rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block font-bold text-brand-dark mb-1">Numéro de licencié :</label>
                                        <input type="text" name="participants[1][license]" placeholder="Ex: 12345678"
                                            class="w-full border border-gray-300 rounded px-3 py-2">
                                    </div>
                                    <p>OU</p>
                                    <div>
                                        <label class="block font-bold text-brand-dark mb-1">Numéro PPS :</label>
                                        <input type="text" name="participants[1][pps]" placeholder="Ex: PPS1234567AB"
                                            class="w-full border border-gray-300 rounded px-3 py-2">
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </fieldset>
                </div>

                <div class="mt-6 flex gap-4">
                    <button type="button" id="add-participant" class="btn-outline">
                        + Ajouter un participant
                    </button>
                    <button type="button" id="remove-participant"
                        class="border border-red-500 text-red-500 px-6 py-2 rounded-full font-medium transition hover:bg-red-500 hover:text-white text-sm hidden">
                        - Supprimer le dernier participant
                    </button>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="btn-primary">
                    Valider l'inscription
                </button>
            </div>
        </form>
    </div>

    <script>
        const container = document.getElementById('participants-container');
        const addButton = document.getElementById('add-participant');
        const removeButton = document.getElementById('remove-participant');

        function updateRemoveButtonVisibility() {
            const count = container.querySelectorAll(':scope > fieldset.participant-block').length;
            removeButton.classList.toggle('hidden', count <= 1);
        }

        addButton.addEventListener('click', function() {
            const blocks = container.querySelectorAll(':scope > fieldset.participant-block');
            if (blocks.length >= 9) return;

            const participantCount = blocks.length + 1;

            const firstBlock = blocks[0];
            const newBlock = firstBlock.cloneNode(true);

            // Titre
            newBlock.querySelector('.participant-title').innerText = `Participant n°${participantCount}`;

            // IMPORTANT : renommer + reset tous les champs (input/select/textarea)
            newBlock.querySelectorAll('input, select, textarea').forEach(el => {
                // reset valeur
                if (el.tagName === 'SELECT') {
                    el.selectedIndex = 0; // revient sur "-- Choisir --"
                } else if (el.type === 'checkbox' || el.type === 'radio') {
                    el.checked = false;
                } else {
                    el.value = '';
                }

                // renommer l'index dans le name
                if (el.name) {
                    el.name = el.name.replace(/\[\d+\]/, `[${participantCount}]`);
                }
            });

            // enlever le legend cloné
            const legend = newBlock.querySelector('legend');
            if (legend) legend.remove();

            container.appendChild(newBlock);
            updateRemoveButtonVisibility();
        });

        removeButton.addEventListener('click', function() {
            const blocks = container.querySelectorAll(':scope > fieldset.participant-block');
            if (blocks.length > 1) {
                container.removeChild(blocks[blocks.length - 1]);
            }
            updateRemoveButtonVisibility();
        });

        updateRemoveButtonVisibility();
    </script>
@endsection
