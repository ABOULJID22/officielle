<x-guest-layout>
    <!-- Background Design Elements -->
    <div class="absolute inset-0 z-0 bg-gradient-to-br from-blue-10 via-purple-100 to-pink-100 opacity-70"></div>
    <div class="absolute inset-0 z-0 bg-[url('https://tailwindcss.com/_next/static/media/hero.ca9265f0.jpg')] opacity-5" style="background-size: cover; background-position: center;"></div>
    
    <!-- Session Status -->
    <div class="relative z-10 flex justify-center pt-8">
        <x-auth-session-status class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-md" :status="session('status')" />
    </div>

    <div class="min-h-screen flex items-center justify-center px-18  relative z-10">
        <form method="POST" action="{{ route('login') }}" 
              class="w-full max-w-md bg-white border border-gray-100 rounded-3xl shadow-2xl p-4 space-y-4 transform transition-all duration-300 hover:shadow-3xl animate-fadeInUp backdrop-blur-sm bg-opacity-90">
            @csrf

        <!--    
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Offitrade Logo" class="h-16 w-auto object-contain" />
            </div> -->

            <!-- Title -->
            <div class="text-center mb-8 ">
                <h2 class="text-4xl font-extrabold text-gray-900 leading-tight">
                    Bienvenue sur <span class="text-[#3a5a8f]">Offitrade</span>
                </h2>
                <p class="mt-3 text-base text-gray-600">
                    Connectez-vous à votre compte 
                </p>
            </div>

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Adresse e-mail')" class="block text-gray-700 font-medium mb-2 text-sm"/>
                <x-text-input 
                    id="email" 
                    class="block w-full rounded-xl border border-gray-300 px-4 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#a1b6d8] focus:border-transparent text-gray-800 text-base transition duration-200" 
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    required 
                    autofocus 
                    autocomplete="username" 
                    placeholder="votre.email@exemple.com"
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-xs" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Mot de passe')" class="block text-gray-700 font-medium mb-2 text-sm"/>
                <x-text-input 
                    id="password" 
                    class="block w-full rounded-xl border border-gray-300 px-4 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#a1b6d8] focus:border-transparent text-gray-800 text-base transition duration-200" 
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="current-password" 
                    placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-xs" />
            </div>

            <!-- Remember Me + Forgot Password -->
            <div class="flex items-center justify-between text-sm">
                <label for="remember_me" class="inline-flex items-center text-gray-700 cursor-pointer select-none">
                    <input id="remember_me" type="checkbox" name="remember" 
                           class="rounded-md border-gray-300 text-[#3a5a8f] shadow-sm focus:ring-[#3a5a8f] mr-2" />
                    <span class="text-gray-700 hover:text-gray-900 transition-colors">Se souvenir de moi</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-[#3a5a8f] hover:text-[#2c4772] hover:underline font-medium transition-colors">
                        Mot de passe oublié ?
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <x-primary-button 
                class="w-full py-3.5 rounded-xl text-lg font-semibold bg-[#3a5a8f] text-white hover:bg-[#2c4772] 
                       transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="$errors->any()"
            >
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                Se connecter
            </x-primary-button>

            <!-- Divider -->
            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-500 rounded-full font-medium">ou</span>
                </div>
            </div>

            <!-- Register Link -->
            <div class="text-center text-base text-gray-700">
                Pas encore de compte ?&nbsp;
                <a href="{{ route('register') }}" class="text-[#3a5a8f] hover:text-[#2c4772] hover:underline font-semibold transition-colors">
                    Créez-en un
                </a>
            </div>
        </form>
    </div>

    <!-- Animation -->
    <style>
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out;
        }
    </style>
</x-guest-layout>