@extends('layouts.structure')

@section('title', 'VIKAPP - Informations Légales')

@section('content')

    {{-- Reusing the hero section from the homepage for visual consistency across the platform --}}
    @include('partials.herosection', [
        'pageTitle' => 'Informations Légales', 
        'pageSubTitle' => 'Mentions légales, CGU et Politique de confidentialité.', 
        'imageUrl' => asset('img/heroSection/header.jpg') 
    ])

    <section class="max-w-6xl mx-auto px-6 py-12">
        
        {{-- Quick Navigation Table of Contents using anchor links --}}
        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-12">
            <h3 class="font-bold text-lg mb-4">Sommaire</h3>
            <ul class="space-y-2 text-sm text-brand-green font-medium">
                <li><a href="#mentions" class="hover:underline">1. Mentions Légales</a></li>
                <li><a href="#cgu" class="hover:underline">2. Conditions Générales d'Utilisation (CGU)</a></li>
                <li><a href="#rgpd" class="hover:underline">3. Politique de Confidentialité (RGPD)</a></li>
            </ul>
        </div>

        {{-- 1. LEGAL NOTICE (Mentions Légales) --}}
        <div id="mentions" class="mb-16">
            <p class="subtitle italic">Éditeur & Hébergeur</p>
            <h2 class="title-section mb-6">1. Mentions Légales</h2>
            
            <div class="text-gray-600 leading-relaxed text-sm space-y-4">
                <p>
                    <strong>Propriétaire du site :</strong><br>
                    Association Vik'azim (Projet étudiant fictif)<br>
                    Département Informatique - IUT Grand Ouest Normandie<br>
                    Campus 3, 14200 Hérouville-Saint-Clair
                </p>

                <p>
                    <strong>Édition et Réalisation :</strong><br>
                    Site réalisé dans le cadre de la SAÉ S3 "Application de gestion de courses d'orientations" (2025-2026) par les étudiants du BUT Informatique.
                </p>

                <p>
                    <strong>Hébergement :</strong><br>
                    Université de Caen Normandie / DSI<br>
                    Esplanade de la Paix, 14000 Caen<br>
                    Téléphone : +33 2 31 56 55 00
                </p>
            </div>
        </div>

        <hr class="border-gray-200 my-12">

        {{-- 2. TERMS OF USE (CGU) --}}
        <div id="cgu" class="mb-16">
            <p class="subtitle italic">Règles d'utilisation</p>
            <h2 class="title-section mb-6">2. Conditions Générales d'Utilisation</h2>

            <div class="text-gray-600 leading-relaxed text-sm space-y-4">
                <h3 class="font-bold text-gray-800 text-lg">2.1 Objet</h3>
                <p>
                    Les présentes CGU ont pour objet de définir les modalités de mise à disposition des services du site Vik'App. L'accès au site par le visiteur signifie son acceptation des présentes conditions.
                </p>

                <h3 class="font-bold text-gray-800 text-lg">2.2 Accès aux services</h3>
                <p>
                    Le site permet d'accéder aux services suivants : consultation des raids, inscription aux courses, gestion des résultats.
                    L'accès aux fonctionnalités d'inscription nécessite la création d'un compte (Coureur, Responsable de club ou Organisateur).
                </p>

                <h3 class="font-bold text-gray-800 text-lg">2.3 Responsabilité</h3>
                <p>
                    L'utilisateur est responsable de la confidentialité de ses identifiants. Les informations saisies (notamment pour les certificats médicaux) doivent être exactes. Vik'azim ne saurait être tenu responsable en cas d'erreur de saisie impactant la validité d'une inscription.
                </p>
            </div>
        </div>

        <hr class="border-gray-200 my-12">

        {{-- 3. PRIVACY POLICY / GDPR (RGPD) --}}
        <div id="rgpd" class="mb-16">
            <p class="subtitle italic">Protection des données</p>
            <h2 class="title-section mb-6">3. Politique de Confidentialité (RGPD)</h2>

            <div class="text-gray-600 leading-relaxed text-sm space-y-4">
                <p>
                    Conformément au Règlement Général sur la Protection des Données (RGPD), Vik'App s'engage à assurer la protection, la confidentialité et la sécurité des données personnelles de ses utilisateurs.
                </p>

                <h3 class="font-bold text-gray-800 text-lg">3.1 Données collectées</h3>
                <p>
                    Dans le cadre de l'inscription aux courses, nous collectons les données suivantes :
                </p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Identité : Nom, prénom, date de naissance.</li>
                    <li>Contact : Adresse postale, email, téléphone.</li>
                    <li>Sportives : Numéro de licence, certificats médicaux ou questionnaires de santé.</li>
                </ul>

                <h3 class="font-bold text-gray-800 text-lg">3.2 Finalité du traitement</h3>
                <p>
                    Ces données sont nécessaires pour :
                </p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>La gestion des inscriptions et des équipes.</li>
                    <li>La validation des certificats médicaux (obligation légale pour les compétitions).</li>
                    <li>L'établissement des classements et la transmission des résultats à la Fédération (FFCO).</li>
                </ul>

                <h3 class="font-bold text-gray-800 text-lg">3.3 Vos droits</h3>
                <p>
                    Vous disposez d'un droit d'accès, de rectification et de suppression de vos données. Pour exercer ce droit, vous pouvez nous contacter via le formulaire de contact ou à l'adresse : <a href="mailto:association@vikazim.fr" class="underline text-brand-green">association@vikazim.fr</a>.
                </p>
            </div>
        </div>

    </section>

@endsection