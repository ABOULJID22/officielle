<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Offitrade') }}</title>
    <!-- Favicon -->
            <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ $siteSettings?->favicon_path ? Storage::url($siteSettings->favicon_path) : asset('favicon.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
      body::before {
        content: "";
        position: fixed;
        inset: 0;
        background-image: url('/images/img1.jpg');
        background-size: cover;
        background-position: center;
        filter: blur(6px);
        z-index: -1;
      }
    </style>
  </head>
  <body class="font-sans text-gray-900 antialiased relative bg-gray-100 dark:bg-gray-900">

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
                <a href="{{ url('/') }}" class="relative hover:text-[#4f6ba3] transition after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-[#4f6ba3] after:transition-all hover:after:w-full">Accueil</a>
                <a href="{{ url('/') }}#about" class="relative hover:text-[#4f6ba3] transition after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-[#4f6ba3] after:transition-all hover:after:w-full">À propos</a>
                <a href="{{ url('/') }}#services" class="relative hover:text-[#4f6ba3] transition after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-[#4f6ba3] after:transition-all hover:after:w-full">Services</a>
                <a href="{{ url('/') }}#blog" class="relative hover:text-[#4f6ba3] transition after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-[#4f6ba3] after:transition-all hover:after:w-full">Blog</a>
                <a href="{{ url('/') }}#faq" class="relative hover:text-[#4f6ba3] transition after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-[#4f6ba3] after:transition-all hover:after:w-full">FAQ</a>
                <a href="{{ url('/') }}#contact" class="relative hover:text-[#4f6ba3] transition after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-[#4f6ba3] after:transition-all hover:after:w-full">Contact</a>
            </nav>

            <!-- Actions & Mobile Menu Button -->
            <div class="flex items-center gap-4">
                <!-- Desktop Actions -->
                <div class="hidden md:flex items-center gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/') }}" class="rounded-lg bg-[#4f6ba3] dark:bg-[#4f6ba3] dark:text-white px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-[#425a8a]">
                                Dashboard
                            </a>
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
                    @endif

                    <!-- Toggle Dark/Light Mode -->
                    <button class="theme-toggle" aria-label="Changer de thème">
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
                          <a href="{{ url('/') }}" class="block rounded-lg px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:text-[#4f6ba3]">Accueil</a>
                          <a href="{{ url('/') }}#about" class="block rounded-lg px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:text-[#4f6ba3]">À propos</a>
                          <a href="{{ url('/') }}#servic" class="block rounded-lg px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:text-[#4f6ba3]">Services</a>
                          <a href="{{ url('/') }}#blog" class="block rounded-lg px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:text-[#4f6ba3]">Blog</a>
                          <a href="{{ url('/') }}#faq" class="block rounded-lg px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:text-[#4f6ba3]">FAQ</a>
                          <a href="{{ url('/') }}#contact" class="block rounded-lg px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:text-[#4f6ba3]">Contact</a>
                          <!-- Actions -->
                          <div class="mt-4 border-t pt-4 space-y-2 border-[#4f6ba3] text-white dark:border-gray-600">
                              @if (Route::has('login'))
                                  @auth
                                      <a href="{{ url('/dashboard') }}" class="block w-full rounded-lg bg-[#4f6ba3]  px-5 py-2.5 text-center text-sm font-semibold text-white shadow-md transition hover:bg-[#425a8a]">Dashboard</a>
                                  @else
                                      <a href="{{ route('login') }}" class="block w-full rounded-lg bg-gray-100 px-5 py-2.5 text-center text-sm font-semibold text-gray-800 transition hover:bg-gray-200 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">Connexion</a>
                                      @if (Route::has('register'))
                                          <a href="{{ route('register') }}" class="block w-full rounded-lg bg-gray-800 px-5 py-2.5 text-center text-sm font-semibold text-white shadow-md transition hover:bg-gray-700 dark:bg-blue-600 dark:hover:bg-blue-700">Inscription</a>
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
        // --- Gestion du Menu Mobile ---
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const openIcon = document.getElementById('menu-icon-open');
        const closeIcon = document.getElementById('menu-icon-close');

        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', () => {
                const isExpanded = mobileMenuToggle.getAttribute('aria-expanded') === 'true';
                mobileMenuToggle.setAttribute('aria-expanded', !isExpanded);
                
                // Toggle des classes pour l'animation
                mobileMenu.classList.toggle('opacity-0');
                mobileMenu.classList.toggle('scale-95');
                mobileMenu.classList.toggle('pointer-events-none');
                
                // Toggle des icônes
                openIcon.classList.toggle('hidden');
                closeIcon.classList.toggle('hidden');
            });
        }

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

  
  <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 mt-8 sm:pt-0 relative z-10">
      <!-- <div>
        <a href="/">
          <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
        </a>
      </div> -->

      <div class="w-full sm:max-w-md mt-6 px-6 py-4   shadow-md overflow-hidden sm:rounded-lg">
      {{ $slot }}
 
      </div>
    </div>
  </body>
</html>
