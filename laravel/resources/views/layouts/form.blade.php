@extends('layouts.app')

@section('header')
    {{-- On applique ta classe title-section ici --}}
    <h2 class="title-section leading-tight">
        @yield('form-title', 'Formulaire')
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="admin-table-container"> {{-- On utilise ta classe de conteneur admin --}}

                <form method="POST" action="@yield('action')" enctype="multipart/form-data">
                    @csrf

                    @if ($errors->any())
                        <div class="mb-6 p-4 rounded-md bg-red-50 border-l-4 border-red-500">
                            <h3 class="text-sm font-bold text-red-800 uppercase italic">Erreurs :</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="space-y-6">
                        @yield('fields')
                    </div>

                    {{-- Modification du bouton ici --}}
                    <div class="mt-8 pt-5 border-t border-gray-200 text-right">
                        <button type="submit" class="btn-primary">
                            @yield('submit-label', 'Envoyer')
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
