
<!-- Footer amélioré -->
<footer class="relative pt-16 pb-8 text-white overflow-hidden">
     

    <!-- Image de fond -->
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat bg-gradient-to-br from-[#4f6ba3] to-[#283043] dark:from-gray-900 dark:to-gray-800" ></div>
    
    <!-- Overlay gradient pour la lisibilité style="background-image: url('{{ asset('images/bgSide.png') }}');"-->
    
    
    <!-- Abstract Animated Background -->
                
                
    <!-- Contenu du footer -->
    <div class="relative z-10 max-w-7xl mx-auto px-6">
        <!-- Grid principale -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-10 border-b-2 border-white/15 dark:border-gray-400/20 pb-10">
            <!-- Logo & description -->
            @php
                $homeUrl = url('/');
                $onHome = url()->current() === $homeUrl;
                $base = $onHome ? '' : $homeUrl;
            @endphp
            <div class="md:col-span-1 lg:col-span-1">
                <div class="flex items-center gap-3">
                    <span class="relative flex items-center justify-center overflow-hidden">
           <a href="{{ $homeUrl }}" class="inline-block p-1  bg-gradient-to-br from-gray-900 to-gray-800 drop-shadow-lg  shadow-8xl hover:scale-105 transition-transform duration-300 group">
                <img src="{{ asset('images/logo.png') }}" alt="Offitrade Logo" class="h-10 w-auto bg-gradient-to-br from-gray-900 to-gray-800 drop-shadow-lg opacity-95 group-hover:opacity-100" />
            </a>          
            </span>
                </div>
                <p class="mt-6 text-sm leading-relaxed text-white/80">
                    {{ __('site.footer.desc') }}
                </p>

            </div>

            <!-- Navigation -->
            <div class="">
                <h4 class="text-sm font-semibold uppercase tracking-wider text-white">{{ __('site.footer.navigation') }}</h4>
                <ul class="mt-6 space-y-3 text-sm text-white/70">
                    <li><a href="/" class="transition hover:text-white">{{ __('site.nav.home') }}</a></li>
                    <li><a href="#about" class="transition hover:text-white">{{ __('site.nav.about') }}</a></li>
                    <li><a href="{{ route('pourquoi') }}" class="transition hover:text-white">{{ __('site.nav.blog') }}</a></li>
                    <li><a href="#contact" class="transition hover:text-white">{{ __('site.nav.contact') }}</a></li>
                </ul>
            </div>

            <!-- Support & FAQ -->
            <div class="">
                <h4 class="text-sm font-semibold uppercase tracking-wider text-white">{{ __('site.footer.support') }}</h4>
                <ul class="mt-6 space-y-3 text-sm text-white/70">
                    <li><a href="#faq" class="transition hover:text-white">{{ __('site.nav.faq') }}</a></li>
                    <li><a href="{{ route('legal') }}" class="transition hover:text-white">{{ __('site.footer.legal') }}</a></li>
                    <li><a href="{{ route('privacy') }}" class="transition hover:text-white">{{ __('site.footer.privacy') }}</a></li>
                </ul>
            </div>
            <!-- Compte -->
            <div class="">
                <h4 class="text-sm font-semibold uppercase tracking-wider text-white">{{ __('site.footer.account') }}</h4>
                <ul class="mt-6 space-y-3 text-sm text-white/70">
                    @guest
                        <li><a href="{{ route('login') }}" class="transition hover:text-white">{{ __('site.footer.login') }}</a></li>
                        <li><a href="{{ route('register') }}" class="transition hover:text-white">{{ __('site.footer.register') }}</a></li>
                    @endguest
                    @auth
                        <li><a href="{{ route('profile.edit') }}" class="transition hover:text-white">{{ __('site.footer.profile') }}</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="transition hover:text-white">{{ __('site.footer.logout') }}</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>




            <!-- Réseaux sociaux -->
            <div class="">
                <h4 class="text-sm font-semibold uppercase tracking-wider text-white">{{ __('site.footer.follow_us') }}</h4>
                <div class="mt-2 flex flex-wrap gap-3">
                    <a href="{{ $siteSettings?->linkedin_url ?? 'https://www.linkedin.com/company/offitrade' }}" target="_blank" rel="noopener" aria-label="LinkedIn" class="group flex h-11 w-11 items-center justify-center rounded-2xl border border-white/10 bg-white/5 transition hover:-translate-y-1 hover:border-white/30 hover:bg-white/10">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 text-white/80 transition group-hover:text-white"><path fill="currentColor" d="M20.451 20.452h-3.554v-5.569c0-1.328-.026-3.037-1.849-3.037c-1.852 0-2.135 1.445-2.135 2.939v5.667H9.359V9h3.414v1.561h.049c.476-.899 1.64-1.85 3.377-1.85c3.606 0 4.272 2.372 4.272 5.457v6.284ZM5.337 7.433a2.065 2.065 0 1 1 0-4.13a2.065 2.065 0 0 1 0 4.13ZM6.925 20.452H3.75V9h3.175v11.452ZM22.225 0H1.771C.792 0 0 .77 0 1.723v20.554C0 23.23.792 24 1.771 24h20.454C23.2 24 24 23.23 24 22.277V1.723C24 .77 23.2 0 22.225 0Z"/></svg>
                    </a>
                    <a href="{{ $siteSettings?->instagram_url ?? 'https://www.instagram.com/offitrade.fr' }}" target="_blank" rel="noopener" aria-label="Instagram" class="group flex h-11 w-11 items-center justify-center rounded-2xl border border-white/10 bg-white/5 transition hover:-translate-y-1 hover:border-white/30 hover:bg-white/10">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 text-white/80 transition group-hover:text-white"><path fill="currentColor" d="M12 7.09a4.91 4.91 0 1 0 0 9.82a4.91 4.91 0 0 0 0-9.82Zm0 8.08a3.17 3.17 0 1 1 0-6.34a3.17 3.17 0 0 1 0 6.34Zm6.19-8.58a1.15 1.15 0 1 1-2.3 0a1.15 1.15 0 0 1 2.3 0Z"/><path fill="currentColor" d="M21.54 6.5a5.5 5.5 0 0 0-.36-1.85a3.77 3.77 0 0 0-2.16-2.16A5.5 5.5 0 0 0 17.17 2c-1.12-.05-1.47-.06-5.17-.06s-4.05 0-5.17.06A5.5 5.5 0 0 0 5 2.49a3.77 3.77 0 0 0-2.16 2.16A5.5 5.5 0 0 0 2.5 6.5C2.45 7.62 2.44 7.97 2.44 11.67s0 4.05.06 5.17A5.5 5.5 0 0 0 3 18.7a3.77 3.77 0 0 0 2.16 2.16a5.5 5.5 0 0 0 1.85.36c1.12.05 1.47.06 5.17.06s4.05 0 5.17-.06a5.5 5.5 0 0 0 1.85-.36a3.77 3.77 0 0 0 2.16-2.16a5.5 5.5 0 0 0 .36-1.85c.05-1.12.06-1.47.06-5.17s0-4.05-.06-5.17Zm-2.06 11a3.76 3.76 0 0 1-.21 1.25a1.94 1.94 0 0 1-1.11 1.11a3.76 3.76 0 0 1-1.25.21c-1.1.05-1.43.06-4.91.06s-3.81 0-4.91-.06a3.76 3.76 0 0 1-1.25-.21a1.94 1.94 0 0 1-1.11-1.11a3.76 3.76 0 0 1-.21-1.25c-.05-1.1-.06-1.43-.06-4.91s0-3.81.06-4.91a3.76 3.76 0 0 1 .21-1.25a1.94 1.94 0 0 1 1.11-1.11a3.76 3.76 0 0 1 1.25-.21c1.1-.05 1.43-.06 4.91-.06s3.81 0 4.91.06a3.76 3.76 0 0 1 1.25.21a1.94 1.94 0 0 1 1.11 1.11a3.76 3.76 0 0 1 .21 1.25c.05 1.1.06 1.43.06 4.91s0 3.81-.06 4.91Z"/></svg>
                    </a>
                </div>
                <div class="mt-3 space-y-2 text-sm text-white/70">
                    <p><span class="font-semibold text-white">{{ __('site.footer.email') }}:</span> {{ $siteSettings?->email ?? 'contact@offitrade.fr' }}</p>
                    <p><span class="font-semibold text-white">{{ __('site.footer.phone') }}:</span> {{ $siteSettings?->phone ?? '+33 07 67 70 67 26' }}</p>
                    <p class="max-w-xs leading-relaxed"><span class="font-semibold text-white">{{ __('site.footer.address') }}:</span> {{ $siteSettings?->address ?? '14 rue Beffory, 92200 Neuilly-sur-Seine, France' }}</p>
                </div>
            </div>
        </div>

            <!-- Copyright -->
        <div class="mt-4 text-center text-xs text-white/70 dark:text-gray-400">
            &copy; 
            @if (now()->year > 2025)
                2025-{{ now()->year }}
            @else
                2025
            @endif
            Offitrade. {{ __('site.footer.copyright') }} | 
            {!! __('site.footer.made_with') !!}
        </div>

    </div>
    <!-- Fin contenu footer z-10 -->
</footer>

<script>
    // Message caché dans la console
    console.log('%c👨‍💻 Développé par mohamed abouljid', 'color: #4f6ba3; font-size: 16px; font-weight: bold; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);');
    console.log('%c💙 Merci ', 'color: #5a7bbf; font-size: 12px;');
</script>
