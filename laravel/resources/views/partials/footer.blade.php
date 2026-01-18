<footer class="bg-brand-green text-white pt-12 pb-6">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
        
        <div class="flex flex-col h-full">
            <div class="flex items-center gap-2 mb-4">
                <img src="{{ asset('img/logo_white.png') }}" alt="Logo Vik'App" class="w-12 h-12 object-contain">
                
                <h4 class="text-xl font-bold uppercase tracking-wider">Vik'App</h4>
            </div>
            <p class="text-sm text-white/80 mb-6 leading-relaxed">
                Vik'App est une application développée par des étudiants du BUT Informatique de l'Université de Caen Normandie. Réalisée dans le cadre de la SAÉ du semestre 3, elle vise à moderniser la gestion des courses pour le club Vik'azim.
            </p>
        </div>

        <div class="flex flex-col h-full">
            <h4 class="text-lg font-bold mb-4 border-b border-white/20 pb-2 inline-block">Navigation</h4>
            <ul class="space-y-2 text-sm text-white/90">
                <li><a href="/" class="hover:text-white hover:underline decoration-2 underline-offset-4 transition">Accueil</a></li>
                <li><a href="/raid" class="hover:text-white hover:underline decoration-2 underline-offset-4 transition">Les Raids & Courses</a></li>
            </ul>
            
            <h4 class="text-lg font-bold mt-6 mb-4 border-b border-white/20 pb-2 inline-block">Espace Membre</h4>
            <ul class="space-y-2 text-sm text-white/90">
                @if(!auth()->guard('web')->check() && !auth()->guard('admin')->check())
                    <li><a href="/login" class="hover:text-white hover:underline decoration-2 underline-offset-4 transition">Connexion</a></li>
                    <li><a href="/register" class="hover:text-white hover:underline decoration-2 underline-offset-4 transition">Inscription</a></li>
                @endif
                @auth('web')
                    <li><a href="/profile" class="hover:text-white hover:underline decoration-2 underline-offset-4 transition">Profil</a></li>
                    <li><a href="/runner" class="hover:text-white hover:underline decoration-2 underline-offset-4 transition">Espace Coureur</a></li>
                @endauth
                
            </ul>
        </div>

        <div class="flex flex-col h-full">
            <h4 class="text-lg font-bold mb-4 border-b border-white/20 pb-2 inline-block">Nous contacter</h4>
            <div class="space-y-4 text-sm">
                <p class="flex flex-col">
                    <span class="font-semibold text-white/70 text-xs uppercase">Adresse</span>
                    <span>35 Av. de Thiès<br>14000 Caen</span>
                </p>
                <p class="flex flex-col">
                    <span class="font-semibold text-white/70 text-xs uppercase">Email</span>
                    <a href="mailto:association@vikazim.fr" class="underline hover:text-white/80">association@vikazim.fr</a>
                </p>
                <p class="flex flex-col">
                    <span class="font-semibold text-white/70 text-xs uppercase">Téléphone</span>
                    <span>+33 2 31 56 78 90</span>
                </p>
            </div>
        </div>

        <div class="flex flex-col h-full">
            <h4 class="text-lg font-bold mb-4 border-b border-white/20 pb-2 inline-block">Nous trouver</h4>
            <div class="flex-grow rounded-lg overflow-hidden bg-gray-200 shadow-lg relative min-h-[200px]">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2607.0491337207523!2d-0.36471192313857187!3d49.19962747663153!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x480a42b398c55e93%3A0x6ccbbad805fe9f03!2s35%20Av.%20de%20Thi%C3%A8s%2C%2014000%20Caen!5e0!3m2!1sfr!2sfr!4v1767606118304!5m2!1sfr!2sfr" 
                    class="absolute inset-0 w-full h-full border-0" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>

    <div class="border-t border-white/20 pt-6 mt-6">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-white/60">
            <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                <span>© 2025 Vik'App. Tous droits réservés.</span>
                <a href="/legal" class="hover:text-white transition">Mentions Légales</a>
                <a href="/legal" class="hover:text-white transition">Politique de Confidentialité (RGPD)</a>
                <a href="/legal" class="hover:text-white transition">CGU</a>
            </div>
            <div class="text-center md:text-right">
                <p>Réalisé dans le cadre de la <span class="font-semibold text-white/80">SAÉ S3 - BUT Informatique</span></p>
                <p>Université de Caen Normandie</p>
            </div>
        </div>
    </div>
</footer>