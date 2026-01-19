<header class="border-b border-gray-100 bg-white" x-data="{ open: false }">
    <nav class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center text-[15px] uppercase tracking-wider font-bold text-gray-700">

        <div class="text-lg font-black tracking-tighter">Vik'app</div>

        <ul class="hidden md:flex items-center gap-6">
            <li><a href="{{ url('/') }}" class="hover:text-brand-green">Accueil</a></li>
            <li><a href="{{ route('raid.index') }}" class="hover:text-brand-green">Raids</a></li>

            {{-- ========================================================= --}}
            {{-- CASE 1: ADMINISTRATOR (Table VIK_ADMIN)                   --}}
            {{-- Accessing through the 'admin' guard                       --}}
            {{-- ========================================================= --}}
            @auth('admin')
                @php $user = Auth::guard('admin')->user(); @endphp
            
                <li><a href="{{ route('clubs.create') }}" class="hover:text-brand-green">Créer un club</a></li>
                <li><a href="{{ route('manage.clubs.index') }}" class="hover:text-brand-green">Gérer les clubs</a></li>
            @endauth

            {{-- ========================================================= --}}
            {{-- CASE 2: MEMBER (Table VIK_MEMBER)                         --}}
            {{-- Accessing through the default 'web' guard                 --}}
            {{-- ========================================================= --}}
            @auth('web')
                @php $user = Auth::guard('web')->user(); @endphp
                @if ($user)
                    {{-- ROLE: CLUB MANAGER --}}
                    {{-- Permission: Can manage club details and create Raids --}}
                    @if($user->managedClub()->exists())
                        <li><a href="{{ route('manage.clubs.edit', ['club' => $user->managedClub?->club_id]) }}" class="hover:text-brand-green border-l pl-4 border-gray-300">Club</a></li>
                        <li><a href="{{ route('raids.create') }}" class="hover:text-brand-green border-l pl-4 border-gray-300">Créer un Raid</a></li>
                    @endif


                    {{-- ROLE: RAID ORGANIZER --}}
                    {{-- Permission: Can manage assigned Raids and create Races within them --}}
                    @if($user->managedRaids()->exists())
                        <li><a href="{{ route('manage.raids.index') }}" class="hover:text-brand-green">Mes Raids</a></li>
                    @endif


                    {{-- ROLE: RACE MANAGER --}}
                    {{-- Permission: Can manage race specifics (participants, results) --}}
                    @if($user->managedRaces()->exists())
                        <li><a href="{{ route('manage.races.index') }}" class="hover:text-brand-green border-l pl-4 border-gray-300">Gérer mes courses</a></li>
                    @endif
                @endif
            @endauth

            {{-- ========================================================= --}}
            {{-- ACCOUNT DROPDOWN (Profile, Runner Space, Logout/Login)    --}}
            {{-- ========================================================= --}}
            <li>
                <div class="relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="hover:text-brand-green flex items-center gap-1 border-l pl-4 border-gray-300">
                                Mon compte
                            </button>
                        </x-slot>

                        <x-slot name="content">

                            {{-- Actions for authenticated members --}}
                            @auth('web')
                                @php $user = Auth::guard('web')->user(); @endphp
                                @if ($user)
                                <x-dropdown-link :href="route('profile.edit')">{{ __('Profil') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('runner.index')">{{ __('Espace coureur') }}</x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Se déconnecter') }}
                                    </x-dropdown-link>
                                </form>
                                @endif
                            @endauth

                            {{-- Actions for authenticated admins --}}
                            @auth('admin')
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('admin.logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Se déconnecter') }}
                                    </x-dropdown-link>
                                </form>
                            @endauth

                            {{-- Actions for guests --}}
                            @if(!auth()->guard('web')->check() && !auth()->guard('admin')->check())
                                <x-dropdown-link :href="route('login')">{{ __('Se connecter') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('register')">{{ __('S\'inscrire') }}</x-dropdown-link>
                            @endif
                        </x-slot>
                    </x-dropdown>
                </div>
            </li>
        </ul>

        <div class="md:hidden flex items-center">
            <button @click="open = !open" class="text-gray-700 hover:text-brand-green focus:outline-none p-2">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </nav>

    <div x-show="open"
         x-collapse
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="md:hidden border-t border-gray-100 bg-gray-50"
         style="display: none;">

        <div class="px-6 py-4 space-y-4 text-[14px] font-bold uppercase tracking-wider">

            <a href="{{ url('/') }}" class="block hover:text-brand-green">Accueil</a>
            <a href="{{ route('raid.index') }}" class="block hover:text-brand-green">Raids</a>

            {{-- OPTIONS ADMIN --}}
            @auth('admin')
                <div class="pt-2 border-t border-gray-200 space-y-3">
                    <a href="{{ route('clubs.create') }}" class="block hover:text-brand-green">Créer un club</a>
                    <a href="{{ route('manage.clubs.index') }}" class="block hover:text-brand-green">Gérer les clubs</a>
                </div>
            @endauth

            {{-- OPTIONS MEMBER --}}
            @auth('web')
                @php $user = Auth::guard('web')->user(); @endphp
                @if ($user)
                <div class="pt-2 border-t border-gray-200 space-y-3">

                    @if($user->managedClub()->exists())
                        <a href="{{ route('manage.clubs.edit', ['club' => $user->managedClub?->club_id]) }}" class="block hover:text-brand-green">Mon Club</a>
                        <a href="{{ route('raids.create') }}" class="block hover:text-brand-green">Créer un Raid</a>
                    @endif

                    @if($user->managedRaids()->exists())
                        <a href="{{ route('manage.raids.index') }}" class="block hover:text-brand-green">Mes Raids</a>
                    @endif

                    @if($user->managedRaces()->exists())
                        <a href="{{ route('manage.races.index') }}" class="block hover:text-brand-green text-brand-green">Gérer mes courses</a>
                    @endif

                    <div class="pt-2 border-t border-gray-200">
                        <a href="{{ route('profile.edit') }}" class="block py-1 text-gray-500 normal-case font-medium">Mon Profil</a>
                        <a href="{{ route('runner.index') }}" class="block py-1 text-gray-500 normal-case font-medium">Espace coureur</a>
                        <form method="POST" action="{{ route('logout') }}" class="mt-2">
                            @csrf
                            <button type="submit" class="text-red-600 font-black uppercase">Se déconnecter</button>
                        </form>
                    </div>
                </div>
                @endif
            @endauth

            {{-- CAS NON CONNECTÉ --}}
            @if(!auth()->guard('web')->check() && !auth()->guard('admin')->check())
                <div class="pt-2 border-t border-gray-200 space-y-3">
                    <a href="{{ route('login') }}" class="block hover:text-brand-green">Se connecter</a>
                    <a href="{{ route('register') }}" class="block hover:text-brand-green">S'inscrire</a>
                </div>
            @endif

        </div>
    </div>
</header>
