<!-- Header - Version Améliorée avec Avatar Dropdown -->
<header id="app-header" class="fixed top-0 z-50 w-full backdrop-blur-lg bg-gray-900/30 shadow-sm transition-all duration-300 dark:bg-gray-900/80">
    <div class="mx-auto max-w-7xl px-6">
        <div class="flex h-20 items-center justify-between">
            
            <!-- Logo -->
            @php
                $homeUrl = url('/');
                $onHome = url()->current() === $homeUrl;
                $base = $onHome ? '' : $homeUrl;
            @endphp
            <a href="{{ $homeUrl }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Offitrade Logo" class="h-10 w-auto drop-shadow-md" />
            </a>

            <!-- Navigation Desktop -->
            <nav class="hidden md:flex gap-8 text-base font-medium text-white dark:text-gray-100">
                <a href="{{ $base }}#accueil" class="relative hover:text-[#4f6ba3] transition after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-[#4f6ba3] after:transition-all hover:after:w-full">Accueil</a>
                <a href="{{ $base }}#about" class="relative hover:text-[#4f6ba3] transition after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-[#4f6ba3] after:transition-all hover:after:w-full">À propos</a>
                <a href="{{ $base }}#services" class="relative hover:text-[#4f6ba3] transition after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-[#4f6ba3] after:transition-all hover:after:w-full">Services</a>
                <a href="{{ $base }}#blog" class="relative hover:text-[#4f6ba3] transition after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-[#4f6ba3] after:transition-all hover:after:w-full">Blog</a>
                <a href="{{ $base }}#faq" class="relative hover:text-[#4f6ba3] transition after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-[#4f6ba3] after:transition-all hover:after:w-full">FAQ</a>
                <a href="{{ $base }}#contact" class="relative hover:text-[#4f6ba3] transition after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-[#4f6ba3] after:transition-all hover:after:w-full">Contact</a>
            </nav>

            <!-- Actions & Mobile Menu Button -->
            <div class="flex items-center gap-4">
                <!-- Desktop Actions -->
                <div class="hidden md:flex items-center gap-4">
                    @auth
                        <div class="relative" id="user-dropdown-container">
                            <!-- Avatar Button -->
                            <button id="user-menu-button" class="flex items-center focus:outline-none" aria-haspopup="true" aria-expanded="false">
                                @if (Auth::user()->avatar)
                                    <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full border-2 border-transparent hover:border-[#4f6ba3] transition object-cover shadow-md">
                                @else
                                    <span class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-700 text-gray-200 font-bold text-lg border-2 border-transparent hover:border-[#4f6ba3] transition shadow-md">
                                        @php
                                            $name = Auth::user()->name;
                                            $initials = strtoupper(substr($name, 0, 1) . (strpos($name, ' ') ? substr(strstr($name, ' '), 1, 1) : ''));
                                        @endphp
                                        {{ $initials }}
                                    </span>
                                @endif
                                <svg class="w-4 h-4 ml-1 text-white dark:text-gray-100" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div id="user-menu" class="hidden absolute right-0 mt-3 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-2 z-50 ring-1 ring-black ring-opacity-5">
                                @if (Auth::user()?->hasAnyRole(['super_admin', 'admin']))<!-- $user && $user->isSuperAdmin(); -->
                                    <a href="{{ route('filament.admin.pages.dashboard') }}" target="_blank" rel="noopener noreferrer" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        Admin Panel
                                    </a>
                                @endif
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Profil</a>
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="rounded-lg border border-[#4f6ba3] px-4 py-2 text-sm font-medium text-white transition hover:bg-[#4f6ba3] hover:text-white dark:text-gray-200 dark:border-gray-200">
                            Connexion
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="rounded-lg bg-[#4f6ba3] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-gray-700 dark:bg-blue-600 dark:hover:bg-blue-700">
                                Inscription
                            </a>
                        @endif
                    @endauth

                    <!-- Toggle Dark/Light Mode -->
                    <button class="theme-toggle" aria-label="Changer de thème">
                        <svg class="theme-icon-light h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <svg class="theme-icon-dark hidden h-5 w-5 text-gray-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-toggle" aria-label="Ouvrir le menu" class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-600 shadow-sm transition hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-[#4f6ba3] md:hidden">
                    <svg id="menu-icon-open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg id="menu-icon-close" class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
    <div id="mobile-menu" class="pointer-events-none absolute left-0 w-full origin-top scale-95 transform opacity-0 transition duration-200 ease-out md:hidden">
            <div class="grid grid-cols-1 gap-y-2 rounded-xl bg-white p-4 shadow-xl ring-1 ring-gray-900/5 dark:bg-gray-800">
        <a href="{{ $base }}#accueil" class="block rounded-lg px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:text-[#4f6ba3]">Accueil</a>
        <a href="{{ $base }}#about" class="block rounded-lg px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:text-[#4f6ba3]">À propos</a>
        <a href="{{ $base }}#services" class="block rounded-lg px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:text-[#4f6ba3]">Services</a>
                <!-- ... autres liens ... -->

                <!-- Actions Mobile -->
                <div class="mt-4 border-t pt-4 space-y-2 border-gray-200 dark:border-gray-600">
                    @auth
                        @if (Auth::user()->role === 'admin')
                            <a href="{{ route('filament.admin.pages.dashboard') }}" target="_blank" class="block w-full rounded-lg bg-gray-800 dark:bg-blue-600 px-5 py-2.5 text-center text-sm font-semibold text-white shadow-md transition hover:bg-gray-700">Admin Panel</a>
                        @endif
                        <a href="{{ route('profile.edit') }}" class="block w-full rounded-lg bg-gray-100 px-5 py-2.5 text-center text-sm font-semibold text-gray-800 transition hover:bg-gray-200 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">Profil</a>
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="block w-full rounded-lg bg-red-500/10 px-5 py-2.5 text-center text-sm font-semibold text-red-600 transition hover:bg-red-500/20 dark:text-red-400">Déconnexion</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block w-full rounded-lg bg-gray-100 px-5 py-2.5 text-center text-sm font-semibold text-gray-800 transition hover:bg-gray-200 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">Connexion</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="block w-full rounded-lg bg-gray-800 px-5 py-2.5 text-center text-sm font-semibold text-white shadow-md transition hover:bg-gray-700 dark:bg-blue-600 dark:hover:bg-blue-700">Inscription</a>
                        @endif
                    @endauth
                    
                    <!-- Toggle Dark/Light Mode Mobile -->
                    <div class="flex justify-center pt-4">
                        <button class="theme-toggle" aria-label="Changer de thème">
                            <svg class="theme-icon-light h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <svg class="theme-icon-dark hidden h-6 w-6 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
  document.addEventListener('DOMContentLoaded', () => {

    // --- Gestion du Menu Mobile ---
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const openIcon = document.getElementById('menu-icon-open');
    const closeIcon = document.getElementById('menu-icon-close');

    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', () => {
            const isExpanded = mobileMenuToggle.getAttribute('aria-expanded') === 'true';
            mobileMenuToggle.setAttribute('aria-expanded', !isExpanded);
            
            mobileMenu.classList.toggle('opacity-0');
            mobileMenu.classList.toggle('scale-95');
            mobileMenu.classList.toggle('pointer-events-none');
            
            openIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        });
    }

    // --- Gestion du Thème (Dark/Light Mode) ---
    const themeToggles = document.querySelectorAll('.theme-toggle');
    const html = document.documentElement;

    const isDark = localStorage.getItem('theme') === 'dark' || 
                   (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);

    function applyTheme(isDark) {
        html.classList.toggle('dark', isDark);
        themeToggles.forEach(btn => {
            const lightIcon = btn.querySelector('.theme-icon-light');
            const darkIcon = btn.querySelector('.theme-icon-dark');
            lightIcon.classList.toggle('hidden', isDark);
            darkIcon.classList.toggle('hidden', !isDark);
        });
    }

    applyTheme(isDark);

    themeToggles.forEach(btn => {
        btn.addEventListener('click', () => {
            const newThemeIsDark = !html.classList.contains('dark');
            localStorage.setItem('theme', newThemeIsDark ? 'dark' : 'light');
            applyTheme(newThemeIsDark);
        });
    });

    // --- Effet de l'en-tête au défilement ---
    const header = document.getElementById('app-header');
    if (header) {
        window.addEventListener('scroll', () => {
            header.classList.toggle('shadow-lg', window.scrollY > 10);
        });
    }

    // --- Gestion du Dropdown Utilisateur ---
    const userDropdownContainer = document.getElementById('user-dropdown-container');
    if (userDropdownContainer) {
        const userMenuButton = document.getElementById('user-menu-button');
        const userMenu = document.getElementById('user-menu');

        userMenuButton.addEventListener('click', (event) => {
            event.stopPropagation();
            const isHidden = userMenu.classList.toggle('hidden');
            userMenuButton.setAttribute('aria-expanded', !isHidden);
        });

        document.addEventListener('click', (event) => {
            if (!userDropdownContainer.contains(event.target) && !userMenu.classList.contains('hidden')) {
                userMenu.classList.add('hidden');
                userMenuButton.setAttribute('aria-expanded', 'false');
            }
        });
    }

  });
</script>
