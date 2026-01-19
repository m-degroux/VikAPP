@extends('layouts.structure')

@section('content')
    {{-- Conteneur pour limiter la largeur du formulaire --}}
    <div class="max-w-4xl mx-auto px-4 mt-10">

        <h1 class="title-section">Gestion des Clubs</h1>
        <p class="subtitle mb-6">Administrez les clubs et leurs responsables licenciés.</p>

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

        {{-- 1. Appel du composant formulaire --}}
        @include('components.form.form-base', [
            'formTitle' => 'Ajouter un club',
            'action' => '/create/club/store',
            'submitLabel' => 'Valider la création du club',
            'fields' => view('partials.forms.club-fields', ['licencies' => $licencies])->render(),
        ])
    </div>
@endsection
