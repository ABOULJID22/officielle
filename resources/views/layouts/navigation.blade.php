<!-- Header Navigation - Standards 2025 Responsive -->
<header 
    id="app-header" 
    role="banner"
    class="fixed top-0 z-50 w-full backdrop-blur-lg bg-gray-900/30 shadow-sm transition-all duration-300 dark:bg-gray-900/80"
    x-data="{ mobileMenuOpen: false, userMenuOpen: false }"
    @click.away="userMenuOpen = false; mobileMenuOpen = false"
>
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="flex h-16 sm:h-20 items-center justify-between">
            
            <!-- Logo - Optimized -->
            <a 
                href="{{ url('/') }}" 
                class="flex items-center gap-2 sm:gap-3 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#4f6ba3] focus-visible:ring-offset-2 rounded-lg transition-transform hover:scale-105"
                aria-label="{{ __('site.aria.home') }}"
            >
                <img 
                    src="{{ asset('images/logo.png') }}" 
                    alt="Offitrade Logo" 
                    class="h-8 sm:h-10 w-auto drop-shadow-md" 
                    width="auto"
                    height="40"
                />
            </a>

            <!-- Desktop Navigation -->
            <nav 
                class="hidden lg:flex items-center gap-6 xl:gap-8" 
                role="navigation"
                aria-label="{{ __('site.aria.main_navigation') }}"
            >
                @foreach([
                    '' => __('site.nav.home'),
                    '#about' => __('site.nav.about'),
                    '#services' => __('site.nav.services'),
                    '#blog' => __('site.nav.blog'),
                    '#faq' => __('site.nav.faq'),
                    '#contact' => __('site.nav.contact'),
                ] as $anchor => $label)
                    <a 
                        href="{{ url('/') }}{{ $anchor }}" 
                        class="group relative text-base font-medium text-white/90 transition hover:text-[#4f6ba3] focus:outline-none focus-visible:text-[#4f6ba3] dark:text-gray-100"
                    >
                        {{ $label }}
                        <span class="absolute -bottom-1 left-0 h-0.5 w-0 bg-gradient-to-r from-[#4f6ba3] to-[#6e94c3] transition-all duration-300 group-hover:w-full group-focus-visible:w-full"></span>
                    </a>
                @endforeach
            </nav>

            <!-- Desktop Actions -->
            <div class="hidden lg:flex items-center gap-3 xl:gap-4">
                
                <!-- Theme Toggle -->
                <button 
                    type="button"
                    class="theme-toggle flex h-9 w-9 items-center justify-center rounded-lg  text-white transition hover:bg-white/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#4f6ba3]"
                    aria-label="{{ __('site.aria.toggle_theme') }}"
                >
                    <svg class="theme-icon-light h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg class="theme-icon-dark hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                <!-- Language Switcher -->
                @php
                    $current = app()->getLocale();
                    $locales = ['fr' => 'FR', 'en' => 'EN'];
                @endphp
                     <nav class="inline-flex items-center gap-1 rounded-full border border-white/20 bg-white/20 p-0.5 text-xs font-semibold text-white shadow-sm backdrop-blur supports-[backdrop-filter]: dark:border-gray-700/80 dark:bg-gray-800/80 dark:text-gray-200">
                        @foreach ($locales as $locale => $label)
                            <a
                                href="{{ url('/locale/'.$locale) }}"
                                @class([
                                    'inline-flex items-center rounded-full px-3 py-1 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-1 focus-visible:ring-[#4f6ba3] focus-visible:ring-offset-transparent',
                                    'bg-[#4f6ba3] text-white shadow-md dark:bg-[#4f6ba3]' => $current === $locale,
                                    'hover:bg-white/30 hover:text-white dark:hover:bg-gray-700/80' => $current !== $locale,
                                ])
                            >
                                {{ $label }}
                            </a>
                        @endforeach
                    </nav>

                <!-- 
                    
                    <nav class="inline-flex items-center gap-1 rounded-full border border-white/30  p-0.5 text-xs font-semibold text-white shadow-sm backdrop-blur supports-[backdrop-filter]:bg-white/5 dark:border-gray-700 dark:bg-gray-800/80 dark:text-gray-200">
                        @foreach ($locales as $locale => $label)
                            <a
                                href="{{ url('/locale/'.$locale) }}"
                                @class([
                                    'inline-flex items-center rounded-full px-3 py-1 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-1 focus-visible:ring-[#4f6ba3] focus-visible:ring-offset-transparent',
                                    'bg-[#4f6ba3] text-white shadow-md dark:bg-blue-600' => $current === $locale,
                                    'hover:bg-white/20 hover:text-white dark:hover:bg-gray-700/80' => $current !== $locale,
                                ])
                            >
                                {{ $label }}
                            </a>
                        @endforeach
                    </nav> -->

                <!-- Auth Buttons / User Menu -->
                @if (Route::has('login'))
                    @auth
                        @php $user = Auth::user(); @endphp
                        <div class="relative">
                            <button 
                                @click="userMenuOpen = !userMenuOpen"
                                type="button"
                                class="flex items-center focus:outline-none focus-visible:ring-2 focus-visible:ring-[#4f6ba3] rounded-full"
                                aria-haspopup="true"
                                :aria-expanded="userMenuOpen"
                                aria-label="{{ __('site.aria.user_menu') }}"
                            >
                                <img 
                                    src="{{ $user->avatar }}" 
                                    alt="{{ $user->name }}" 
                                    class="h-9 w-9 rounded-full border-2 border-transparent object-cover shadow-md transition hover:border-[#4f6ba3]"
                                >
                            </button>
                            
                            <!-- User Dropdown -->
                            <div 
                                x-show="userMenuOpen"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 mt-3 w-64 origin-top-right overflow-hidden rounded-xl border border-gray-200 bg-white/95 shadow-xl backdrop-blur dark:border-gray-700 dark:bg-gray-800/95"
                                role="menu"
                                x-cloak
                            >
                                <!-- User Info -->
                                <div class="flex items-center gap-3 bg-[#4f6ba3]/10 px-4 py-3 dark:bg-blue-600/10">
                                    <img src="{{ $user->avatar }}" alt="" class="h-9 w-9 rounded-full object-cover">
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                                        <p class="truncate text-xs text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                                    </div>
                                </div>

                                <!-- Menu Items -->
                                <div class="p-2">
                                    @if ($user?->hasAnyRole(['super_admin', 'admin']))
                                        <a href="{{ route('filament.admin.pages.dashboard') }}" target="_blank" class="flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm text-gray-700 transition hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700" role="menuitem">
                                            <svg class="h-4 w-4 text-[#4f6ba3]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 19h18M7 16V8m5 8V4m5 12v-6"/></svg>
                                            {{ __('site.auth.admin_panel') }}
                                        </a>
                                    @endif

                                    @if ($user?->hasAnyRole('client'))
                                            <a href="{{ route('filament.admin.pages.dashboard') }}" target="_blank" class="flex items-center text-[#4f6ba3] text-sm gap-2 rounded-lg px-3 py-2 transition hover:bg-gray-100 dark:hover:bg-gray-700">
                                                <svg class="h-4 w-4 text-[#4f6ba3]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M9 6V5a3 3 0 0 1 3-3 3 3 0 0 1 3 3v1" />
                                                    <path d="M4 9h16" />
                                                    <path d="M4 9v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9" />
                                                    <path d="M10 13h4" />
                                                </svg>
                                                {{ __('site.auth.my_space') }}
                                            </a>
                                        @endif

                                    @if ($user?->hasRole('user') && !$user?->hasRole('client'))
                                        <a href="{{ route('pharmacist.request.create') }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm text-yellow-700 transition hover:bg-yellow-50 dark:text-yellow-400 dark:hover:bg-gray-700" role="menuitem">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v6m3-3h-6"/></svg>
                                            {{ __('site.pharmacist.request') }}
                                        </a>
                                    @endif

                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm text-gray-700 transition hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700" role="menuitem">
                                        <svg class="h-4 w-4 text-[#4f6ba3]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 20a6 6 0 0 0-12 0"/><circle cx="12" cy="10" r="4"/><circle cx="12" cy="12" r="9"/></svg>
                                        {{ __('site.auth.profile') }}
                                    </a>
                                </div>

                                <!-- Logout -->
                                <div class="border-t border-gray-100 p-2 dark:border-gray-700">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-medium text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10" role="menuitem">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4m-5-4l5-5-5-5m5 5H3"/></svg>
                                            {{ __('site.auth.logout') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="rounded-lg border border-[#4f6ba3] px-4 py-2 text-sm font-medium text-white transition hover:bg-[#4f6ba3]/10 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#4f6ba3] dark:border-gray-200">
                            {{ __('site.auth.login') }}
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="rounded-lg bg-[#4f6ba3] px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-[#465a87] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#4f6ba3] focus-visible:ring-offset-2">
                                {{ __('site.auth.register') }}
                            </a>
                        @endif
                    @endauth
                @endif
            </div>

            <!-- Mobile Menu Button -->
            <button 
                @click="mobileMenuOpen = !mobileMenuOpen"
                type="button"
                class="flex h-10 w-10 items-center justify-center rounded-lg  text-white transition hover:bg-white/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#4f6ba3] lg:hidden"
                :aria-expanded="mobileMenuOpen"
                aria-label="{{ __('site.aria.menu') }}"
            >
                <svg 
                    class="h-6 w-6 transition-transform duration-300"
                    :class="{ 'rotate-90': mobileMenuOpen }"
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke="currentColor" 
                    stroke-width="2"
                >
                    <path 
                        x-show="!mobileMenuOpen"
                        stroke-linecap="round" 
                        stroke-linejoin="round" 
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                    <path 
                        x-show="mobileMenuOpen"
                        stroke-linecap="round" 
                        stroke-linejoin="round" 
                        d="M6 18L18 6M6 6l12 12"
                        x-cloak
                    />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div 
            x-show="mobileMenuOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="lg:hidden pb-4"
            x-cloak
        >
            <nav 
                class="mt-4 space-y-1 rounded-xl bg-white/95 p-4 shadow-xl ring-1 ring-black/5 backdrop-blur-sm dark:bg-gray-800/95"
                role="navigation"
            >
                <!-- Mobile Links -->
                @foreach([
                    '#accueil' => __('site.nav.home'),
                    '#about' => __('site.nav.about'),
                    '#services' => __('site.nav.services'),
                    '#blog' => __('site.nav.blog'),
                    '#faq' => __('site.nav.faq'),
                    '#contact' => __('site.nav.contact'),
                ] as $anchor => $label)
                    <a 
                        href="{{ url('/') }}{{ $anchor }}" 
                        class="block rounded-lg px-4 py-2.5 text-base font-medium text-gray-700 transition hover:bg-[#4f6ba3]/10 hover:text-[#4f6ba3] dark:text-gray-200"
                        @click="mobileMenuOpen = false"
                    >
                        {{ $label }}
                    </a>
                @endforeach

                @auth
                    @if (Auth::user()?->hasRole('user') && !Auth::user()?->hasRole('client'))
                        <a href="{{ route('pharmacist.request.create') }}" class="block rounded-lg px-4 py-2.5 text-base font-medium text-yellow-700 transition hover:bg-yellow-50 dark:text-yellow-400">
                            {{ __('site.pharmacist.request') }}
                        </a>
                    @endif
                @endauth

                <!-- Mobile Actions -->
                <div class="mt-4 space-y-2.5 border-t border-gray-200 pt-4 dark:border-gray-700">
                    <!-- Theme + Language -->
                    <div class="flex items-center gap-2 justify-center">
                        <button 
                            type="button"
                            class="theme-toggle flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 text-gray-700 transition hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200"
                            aria-label="{{ __('site.aria.toggle_theme') }}"
                        >
                            <svg class="theme-icon-light h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <svg class="theme-icon-dark hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            </svg>
                        </button>

                        <nav class="inline-flex items-center gap-1 rounded-full border border-gray-300/60 bg-white/70 p-0.5 text-xs font-semibold text-gray-700 shadow-sm backdrop-blur supports-[backdrop-filter]:bg-white/40 dark:border-gray-600 dark:bg-gray-700/70 dark:text-gray-200">
                            @foreach ($locales as $locale => $label)
                                <a
                                    href="{{ url('/locale/'.$locale) }}"
                                    @class([
                                        'inline-flex items-center text-gray-900 dark:text-white rounded-full px-3 py-1 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-[#4f6ba3] focus-visible:ring-offset-0',
                                        'bg-[#4f6ba3] text-white shadow dark:bg-blue-600' => $current === $locale,
                                        'hover:bg-gray-100 hover:text-[#4f6ba3] dark:hover:bg-gray-600 dark:hover:text-primary-200' => $current !== $locale,
                                    ])
                                >
                                    {{ $label }}
                                </a>
                            @endforeach
                        </nav>
                    </div>

                    <!-- Auth Buttons Mobile -->
                    @if (Route::has('login'))
                        @auth
                            @if (Auth::user()?->hasAnyRole(['super_admin','admin']))
                                <a href="{{ route('filament.admin.pages.dashboard') }}" class="block w-full rounded-lg bg-[#4f6ba3] px-4 py-2.5 text-center text-sm font-semibold text-white shadow transition hover:bg-[#465a87]">
                                    {{ __('site.auth.admin_panel') }}
                                </a>
                            @endif

                            @if (Auth::user()?->hasAnyRole('client'))
                                <a href="{{ route('filament.admin.pages.dashboard') }}" class="block w-full rounded-lg bg-[#4f6ba3] px-4 py-2.5 text-center text-sm font-semibold text-white shadow transition hover:bg-[#465a87]">
                                    {{ __('site.auth.my_space') }}
                                </a>
                            @endif

                            <a href="{{ route('profile.edit') }}" class="block w-full rounded-lg bg-gray-100 px-4 py-2.5 text-center text-sm font-semibold text-gray-800 transition hover:bg-gray-200 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                                {{ __('site.auth.profile') }}
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full rounded-lg bg-red-50 px-4 py-2.5 text-center text-sm font-semibold text-red-600 transition hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400">
                                    {{ __('site.auth.logout') }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block w-full rounded-lg bg-gray-100 px-4 py-2.5 text-center text-sm font-semibold text-gray-800 transition hover:bg-gray-200 dark:bg-gray-700 dark:text-white">
                                {{ __('site.auth.login') }}
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="block w-full rounded-lg bg-gray-800 px-4 py-2.5 text-center text-sm font-semibold text-white shadow transition hover:bg-[#465a87] dark:bg-[#4f6ba3]">
                                    {{ __('site.auth.register') }}
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </nav>
        </div>
    </div>
</header>
