<!-- Footer amélioré -->
<footer class="bg-gradient-to-r from-[#4f6ba3] to-[#5a7bbf] text-white pt-16 pb-8 mt-16 relative overflow-hidden dark:from-gray-900 dark:to-gray-800">
    <div class="max-w-7xl mx-auto px-6">
        <!-- Grid principale -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-10 border-b border-white/20 pb-12">
            <!-- Logo & description -->

            <div class="md:col-span-1">
                <img src="{{ $siteSettings?->logo_path ? Storage::url($siteSettings->logo_path) : '/images/logo.png' }}" alt="Offitrade Logo" class="h-12 mb-4">
                <p class="text-sm mt-2 text-white/80 dark:text-gray-300">
                    {{ __('site.footer.desc') }}
                </p>
            </div>

            <!-- Navigation -->
            <div>
                <h4 class="text-sm font-semibold text-white mb-4 uppercase tracking-wider dark:text-gray-200">{{ __('site.footer.navigation') }}</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/" class="hover:text-white dark:hover:text-gray-300 transition">{{ __('site.nav.home') }}</a></li>
                    <li><a href="#about" class="hover:text-white dark:hover:text-gray-300 transition">{{ __('site.nav.about') }}</a></li>
                    <li><a href="{{ route('pourquoi') }}" class="hover:text-white dark:hover:text-gray-300 transition">{{ __('site.nav.blog') }}</a></li>
                    <li><a href="#contact" class="hover:text-white dark:hover:text-gray-300 transition">{{ __('site.nav.contact') }}</a></li>
                </ul>
            </div>

            <!-- Compte -->
@guest
<div>
    <h4 class="text-sm font-semibold text-white mb-4 uppercase tracking-wider dark:text-gray-200">{{ __('site.footer.account') }}</h4>
    <ul class="space-y-2 text-sm">
        <li>
            <a href="{{ route('login') }}" class="hover:text-white dark:hover:text-gray-300 transition">
                {{ __('site.footer.login') }}
            </a>
        </li>
        <li>
            <a href="{{ route('register') }}" class="hover:text-white dark:hover:text-gray-300 transition">
                {{ __('site.footer.register') }}
            </a>
        </li>
    </ul>
</div>
@endguest

@auth
{{-- Exemple : montrer autre chose si connecté --}}
<div>
    <h4 class="text-sm font-semibold text-white mb-4 uppercase tracking-wider dark:text-gray-200">{{ __('site.footer.my_account') }}</h4>
    <ul class="space-y-2 text-sm">
        <li>
            <a href="{{ route('profile.edit')}}" class="hover:text-white dark:hover:text-gray-300 transition">
                {{ __('site.footer.profile') }}
            </a>
        </li>
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="hover:text-white dark:hover:text-gray-300 transition">
                    {{ __('site.footer.logout') }}
                </button>
            </form>
        </li>
    </ul>
</div>
@endauth


            <!-- Support & FAQ -->
            <div>
                <h4 class="text-sm font-semibold text-white mb-4 uppercase tracking-wider dark:text-gray-200">{{ __('site.footer.support') }}</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#faq" class="hover:text-white dark:hover:text-gray-300 transition">{{ __('site.nav.faq') }}</a></li>
                    <li><a href="{{ route('legal') }}" class="hover:text-white dark:hover:text-gray-300 transition">{{ __('site.footer.legal') }}</a></li>
                    <li><a href="{{ route('privacy') }}" class="hover:text-white dark:hover:text-gray-300 transition">{{ __('site.footer.privacy') }}</a></li>
                </ul>
            </div>

            <!-- Réseaux sociaux -->
            <div>
                <h4 class="text-sm font-semibold text-white mb-4 uppercase tracking-wider dark:text-gray-200">{{ __('site.footer.follow_us') }}</h4>
                <div class="flex space-x-4">
                    <!-- Facebook -->
                   <!--  <a href="{{ $siteSettings?->facebook_url ?? 'https://www.facebook.com/' }}" target="_blank" aria-label="Facebook" class="hover:text-blue-400 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                            <path d="M22.675 0h-21.35C.597 0 0 .597 0 1.326v21.348C0 23.403.597 24 1.325 
                            24h11.495v-9.294H9.691v-3.622h3.129V8.413c0-3.1 1.894-4.788 
                            4.659-4.788 1.325 0 2.464.099 
                            2.796.143v3.24h-1.918c-1.505 0-1.797.716-1.797 
                            1.764v2.314h3.59l-.467 3.622h-3.123V24h6.127C23.403 
                            24 24 23.403 24 22.674V1.326C24 
                            .597 23.403 0 22.675 0z"/>
                        </svg>
                    </a> -->
                    <!-- LinkedIn -->
                    <a href="{{ $siteSettings?->linkedin_url ?? 'https://www.linkedin.com/Company/offitrade' }}" target="_blank" aria-label="LinkedIn" class="hover:text-blue-400 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.327-.026-3.037-1.85-3.037-1.853 
                            0-2.136 1.445-2.136 2.939v5.667H9.354V9h3.414v1.561h.049c.476-.9 
                            1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 
                            5.455v6.286zM5.337 7.433c-1.144 
                            0-2.067-.926-2.067-2.067 0-1.142.923-2.066 
                            2.067-2.066s2.066.924 2.066 
                            2.066c0 1.141-.922 2.067-2.066 
                            2.067zm1.777 13.019H3.559V9h3.555v11.452zM22.225 
                            0H1.771C.792 0 0 .771 0 1.723v20.554C0 
                            23.229.792 24 1.771 24h20.451C23.2 24 24 
                            23.229 24 22.277V1.723C24 
                            .771 23.2 0 22.225 0z"/>
                        </svg>
                    </a>
                    <!-- Twitter -->
                   <!--  <a href="{{ $siteSettings?->twitter_url ?? 'https://twitter.com/' }}" target="_blank" aria-label="Twitter" class="hover:text-blue-400 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                            <path d="M24 4.557a9.83 9.83 0 0 
                            1-2.828.775 4.932 4.932 0 0 
                            0 2.165-2.724 9.864 9.864 0 0 
                            1-3.127 1.195A4.916 4.916 0 0 
                            0 16.616 3c-2.72 0-4.924 2.204-4.924 
                            4.923 0 .386.045.762.127 
                            1.124C7.691 8.856 4.066 6.9 
                            1.64 3.905a4.822 4.822 0 0 0-.666 
                            2.475c0 1.708.869 3.216 2.19 
                            4.099a4.903 4.903 0 0 
                            1-2.229-.616c-.054 2.386 1.693 
                            4.63 4.188 5.122a4.935 4.935 0 0 
                            1-2.224.085c.63 1.953 2.445 
                            3.376 4.6 3.418A9.867 9.867 0 0 
                            1 0 19.54a13.94 13.94 0 0 0 
                            7.548 2.212c9.058 0 14.01-7.513 
                            14.01-14.01 0-.213-.005-.425-.014-.636A10.012 
                            10.012 0 0 0 24 4.557z"/>
                        </svg>
                    </a> -->
                    <!-- Instagram -->
                    <a href="{{ $siteSettings?->instagram_url ?? 'https://www.instagram.com/offitrade.fr' }}" target="_blank" aria-label="Instagram" class="hover:text-pink-400 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 
                            4.85.07 1.366.062 2.633.35 
                            3.608 1.325.975.975 1.263 
                            2.242 1.325 3.608.058 1.266.069 
                            1.646.069 4.85s-.012 3.584-.07 
                            4.85c-.062 1.366-.35 2.633-1.325 
                            3.608-.975.975-2.242 1.263-3.608 
                            1.325-1.266.058-1.646.069-4.85.069s-3.584-.012-4.85-.07c-1.366-.062-2.633-.35-3.608-1.325-.975-.975-1.263-2.242-1.325-3.608C2.175 
                            15.747 2.163 15.367 2.163 
                            12s.012-3.584.07-4.85c.062-1.366.35-2.633 
                            1.325-3.608.975-.975 2.242-1.263 
                            3.608-1.325C8.416 2.175 8.796 
                            2.163 12 2.163zm0-2.163C8.741 
                            0 8.332.013 7.052.072 5.775.131 
                            4.638.396 3.678 1.356 2.718 2.316 
                            2.453 3.453 2.394 4.73 2.335 
                            6.01 2.322 6.419 2.322 12c0 
                            5.581.013 5.99.072 7.27.059 
                            1.277.324 2.414 1.284 
                            3.374.96.96 2.097 1.225 3.374 
                            1.284 1.28.059 1.689.072 7.27.072s5.99-.013 
                            7.27-.072c1.277-.059 2.414-.324 
                            3.374-1.284.96-.96 1.225-2.097 
                            1.284-3.374.059-1.28.072-1.689.072-7.27 
                            0-5.581-.013-5.99-.072-7.27-.059-1.277-.324-2.414-1.284-3.374C19.414.396 
                            18.277.131 16.999.072 15.719.013 15.31 
                            0 12 0zm0 5.838a6.162 6.162 0 1 0 
                            0 12.324 6.162 6.162 0 0 0 0-12.324zm0 
                            10.162a3.999 3.999 0 1 1 
                            0-7.998 3.999 3.999 0 0 1 0 7.998zm6.406-11.845a1.44 
                            1.44 0 1 1-2.881 0 1.44 1.44 0 0 
                            1 2.881 0z"/>
                        </svg>
                    </a>
                </div>
                <div class="mt-6 text-xs text-white/80 space-y-1">
                    <p><span class="font-semibold">{{ __('site.footer.email') }}:</span> {{ $siteSettings?->email ?? 'contact@offitrade.fr' }}</p>
                    <p><span class="font-semibold">{{ __('site.footer.phone') }}:</span> {{ $siteSettings?->phone ?? '+33 07 67 70 67 26' }}</p>
                    <p class="max-w-[12rem] leading-snug"><span class="font-semibold">{{ __('site.footer.address') }}:</span> {{ $siteSettings?->address ?? '14 rue Beffory, 92200 Neuilly-sur-Seine, France' }}</p>
                </div>
            </div>
        </div>

            <!-- Copyright -->
        <div class="mt-8 text-center text-xs text-white/70 dark:text-gray-400">
            &copy; 
            @if (now()->year > 2025)
                2025-{{ now()->year }}
            @else
                2025
            @endif
            Offitrade. {{ __('site.footer.copyright') }} | {{ __('site.footer.made_with') }}
        </div>

    </div>
</footer>
