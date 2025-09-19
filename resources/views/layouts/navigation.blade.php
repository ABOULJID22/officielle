<!-- Header - Version Améliorée -->
<header id="app-header" class="fixed top-0 z-50 w-full  backdrop-blur-lg bg-gray-900/30 shadow-sm transition-all duration-300 dark:bg-gray-900/80">
    <div class="mx-auto max-w-7xl px-6">
        <div class="flex h-20 items-center justify-between">
            
            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Offitrade Logo" class="h-10 w-auto drop-shadow-md" />
            </a>

            <!-- Navigation Desktop -->
            <nav class="hidden md:flex gap-8 text-base font-medium text-white dark:text-gray-100">
                <a href="{{ url('/') }}" class="relative hover:text-[#4f6ba3] transition after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-[#4f6ba3] after:transition-all hover:after:w-full">{{ __('site.nav.home') }}</a>
                <a href="{{ url('/') }}#about" class="relative hover:text-[#4f6ba3] transition after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-[#4f6ba3] after:transition-all hover:after:w-full">{{ __('site.nav.about') }}</a>
                <a href="{{ url('/') }}#services" class="relative hover:text-[#4f6ba3] transition after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-[#4f6ba3] after:transition-all hover:after:w-full">{{ __('site.nav.services') }}</a>
                <a href="{{ url('/') }}#blog" class="relative hover:text-[#4f6ba3] transition after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-[#4f6ba3] after:transition-all hover:after:w-full">{{ __('site.nav.blog') }}</a>
                <a href="{{ url('/') }}#faq" class="relative hover:text-[#4f6ba3] transition after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-[#4f6ba3] after:transition-all hover:after:w-full">{{ __('site.nav.faq') }}</a>
                <a href="{{ url('/') }}#contact" class="relative hover:text-[#4f6ba3] transition after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-[#4f6ba3] after:transition-all hover:after:w-full">{{ __('site.nav.contact') }}</a>
            </nav>

            <!-- Actions & Mobile Menu Button -->
            <div class="flex items-center gap-4">
                <!-- Desktop Actions -->
                <div class="hidden md:flex items-center gap-4">
                    @php $current = app()->getLocale(); @endphp

                    <!-- Theme toggle (first) -->
                    <button class="theme-toggle" aria-label="{{ __('site.aria.toggle_theme') }}">
    <svg class="theme-icon-light h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 
              6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 
              0l-.707.707M6.343 17.657l-.707.707M16 
              12a4 4 0 11-8 0 4 4 0 018 0z"/>
    </svg>
    <svg class="theme-icon-dark hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M20.354 15.354A9 9 0 018.646 
              3.646 9.003 9.003 0 0012 21a9.003 
              9.003 0 008.354-5.646z"/>
    </svg>
          </button>

                    <!-- Language select (second) -->
                    <label for="lang-select" class="sr-only">{{ __('site.aria.language') }}</label>
                    <div class="relative">
                        <select id="lang-select" class="ml-1 appearance-none rounded bg-white/10 text-white dark:bg-gray-900 dark:text-gray-100 text-sm px-2 pr-8 py-1 focus:outline-none focus:ring-2 focus:ring-[#4f6ba3] transition-colors" onchange="window.location.href='{{ url('/locale') }}/'+this.value;">
                            <option value="fr" {{ $current === 'fr' ? 'selected' : '' }}>FR</option>
                            <option value="en" {{ $current === 'en' ? 'selected' : '' }}>EN</option>
                        </select>
                        <svg class="pointer-events-none absolute right-2 top-1/2 h-4 w-4 -translate-y-1/2 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    @if (Route::has('login'))
                        @auth
                            <div class="relative" id="user-dropdown-container">
                                <button id="user-menu-button" class="flex items-center focus:outline-none" aria-haspopup="true" aria-expanded="false">
                                    <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full border-2 border-transparent hover:border-[#4f6ba3] transition object-cover shadow-md">
                                </button>
                                <div id="user-menu" class="hidden absolute right-0 mt-3 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-2 z-50 ring-1 ring-black ring-opacity-5">
                                    @if (Auth::user()?->hasAnyRole(['super_admin', 'admin']))
                                        <a href="{{ route('filament.admin.pages.dashboard') }}" target="_blank" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">{{ __('site.auth.admin_panel') }}</a>
                                    @endif
                                    @if (Auth::user()?->hasAnyRole('client'))
                                        <a href="{{ route('filament.admin.pages.dashboard') }}" target="_blank" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">{{ __('site.auth.my_space') }}</a>
                                    @endif
                                    @if (Auth::user()?->hasRole('user') && !Auth::user()?->hasRole('client'))
                                        <a href="{{ route('pharmacist.request.create') }}" class="block px-4 py-2 text-sm text-yellow-700 dark:text-yellow-300 hover:bg-gray-100 dark:hover:bg-gray-700">Demander un profil Pharmacien</a>
                                    @endif
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">{{ __('site.auth.profile') }}</a>
                                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">{{ __('site.auth.logout') }}</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="rounded-lg border border-[#4f6ba3] px-4 py-2 text-sm font-medium text-white transition hover:bg-[#4f6ba3] hover:text-white dark:text-gray-200 dark:border-gray-200">{{ __('site.auth.login') }}</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="rounded-lg bg-[#4f6ba3] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-gray-700 dark:bg-blue-600 dark:hover:bg-blue-700">{{ __('site.auth.register') }}</a>
                            @endif
                        @endauth
                    @endif
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
                          <a href="{{ url('/') }}#accueil" class="block rounded-lg px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:text-[#4f6ba3]">{{ __('site.nav.home') }}</a>
                          <a href="{{ url('/') }}#about" class="block rounded-lg px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:text-[#4f6ba3]">{{ __('site.nav.about') }}</a>
                          <a href="{{ url('/') }}#services" class="block rounded-lg px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:text-[#4f6ba3]">{{ __('site.nav.services') }}</a>
                          <a href="{{ url('/') }}#blog" class="block rounded-lg px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:text-[#4f6ba3]">{{ __('site.nav.blog') }}</a>
                          <a href="{{ url('/') }}#faq" class="block rounded-lg px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:text-[#4f6ba3]">{{ __('site.nav.faq') }}</a>
                          <a href="{{ url('/') }}#contact" class="block rounded-lg px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:text-[#4f6ba3]">{{ __('site.nav.contact') }}</a>
                          @auth
                              @if (Auth::user()?->hasRole('user') && !Auth::user()?->hasRole('client'))
                                  <a href="{{ route('pharmacist.request.create') }}" class="block rounded-lg px-4 py-2 text-base font-medium text-yellow-700 hover:bg-yellow-50 dark:text-yellow-300">Demander un profil Pharmacien</a>
                              @endif
                          @endauth
                          
                          <!-- Actions -->
                          <div class="mt-4 border-t pt-4 space-y-3 border-[#4f6ba3] text-white dark:border-gray-600">
                              @php $current = app()->getLocale(); @endphp
                              <!-- Theme + Language controls -->
                              <div class="flex items-center gap-3">
                                  <button class="theme-toggle" aria-label="{{ __('site.aria.toggle_theme') }}">
                                      <svg class="theme-icon-light h-5 w-5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                      </svg>
                                      <svg class="theme-icon-dark hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                      </svg>
                                  </button>
                                  <label for="lang-select-mobile" class="sr-only">{{ __('site.aria.language') }}</label>
                                  <div class="relative">
                                      <select id="lang-select-mobile" class="appearance-none rounded bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-white text-sm px-2 pr-8 py-1 focus:outline-none focus:ring-2 focus:ring-[#4f6ba3]" onchange="window.location.href='{{ url('/locale') }}/'+this.value;">
                                          <option value="fr" {{ $current === 'fr' ? 'selected' : '' }}>FR</option>
                                          <option value="en" {{ $current === 'en' ? 'selected' : '' }}>EN</option>
                                      </select>
                                      <svg class="pointer-events-none absolute right-2 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-700 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                      </svg>
                                  </div>
                              </div>
                              @if (Route::has('login'))
                                  @auth
                                      @if (Auth::user()?->hasAnyRole(['super_admin','admin']))
                                          <a href="{{ route('filament.admin.pages.dashboard') }}" class="block w-full rounded-lg bg-[#4f6ba3]  px-5 py-2.5 text-center text-sm font-semibold text-white shadow-md transition hover:bg-[#425a8a]">{{ __('site.auth.admin_panel') }}</a>
                                      @endif
                                      @if (Auth::user()?->hasAnyRole('client'))
                                          <a href="{{ route('filament.admin.pages.dashboard') }}" class="block w-full rounded-lg bg-[#4f6ba3]  px-5 py-2.5 text-center text-sm font-semibold text-white shadow-md transition hover:bg-[#425a8a]">{{ __('site.auth.my_space') }}</a>
                                      @endif
                                  @else
                                      <a href="{{ route('login') }}" class="block w-full rounded-lg bg-gray-100 px-5 py-2.5 text-center text-sm font-semibold text-gray-800 transition hover:bg-gray-200 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">{{ __('site.auth.login') }}</a>
                                      @if (Route::has('register'))
                                          <a href="{{ route('register') }}" class="block w-full rounded-lg bg-gray-800 px-5 py-2.5 text-center text-sm font-semibold text-white shadow-md transition hover:bg-gray-700 dark:bg-blue-600 dark:hover:bg-blue-700">{{ __('site.auth.register') }}</a>
                                      @endif
                                  @endauth
                              @endif

                              <!-- Toggle Dark/Light Mode -->
          <button class="theme-toggle" aria-label="Changer de thème">
              <svg class="theme-icon-light h-5 w-5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 
                        6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 
                        0l-.707.707M6.343 17.657l-.707.707M16 
                        12a4 4 0 11-8 0 4 4 0 018 0z"/>
              </svg>
              <svg class="theme-icon-dark hidden h-5 w-5 "  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20.354 15.354A9 9 0 018.646 
                        3.646 9.003 9.003 0 0012 21a9.003 
                        9.003 0 008.354-5.646z"/>
              </svg>
          </button>                </div>
            </div>
        </div>
    </div>

  <script>
       
    document.addEventListener('DOMContentLoaded', () => {
        // Mobile menu handled globally in resources/js/app.js

        // --- Gestion du Thème (Dark/Light Mode) ---
        const themeToggles = document.querySelectorAll('.theme-toggle');
        const darkIcon = document.getElementById('theme-icon-dark');
        const html = document.documentElement;
         const isDark = localStorage.getItem('theme') === 'dark' || 
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);

        if (isDark) {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }
        function updateIcons() {
            themeToggles.forEach(btn => {
                const lightIcon = btn.querySelector('.theme-icon-light');
                const darkIcon = btn.querySelector('.theme-icon-dark');
                if (html.classList.contains('dark')) {
                    lightIcon.classList.add('hidden');
                    darkIcon.classList.remove('hidden');
                } else {
                    lightIcon.classList.remove('hidden');
                    darkIcon.classList.add('hidden');
                }
            });
        }
        // Initialiser les icônes
        updateIcons();

        // Ajouter le listener sur TOUS les boutons
        themeToggles.forEach(btn => {
            btn.addEventListener('click', () => {
                html.classList.toggle('dark');
                localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
                updateIcons();
            });
        });
        // --- Effet de l'en-tête au défilement (Optionnel) ---
        const header = document.getElementById('app-header');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 10) {
                header.classList.add('shadow-md');
            } else {
                header.classList.remove('shadow-md');
            }
        });
    });

  </script>
</header>
