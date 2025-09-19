<x-guest-layout>
    <!-- Background Design Elements -->

    <div class="min-h-screen flex items-center justify-center px-18 relative z-10">
      <form method="POST" action="{{ route('register') }}" 
          class="w-full max-w-md dark:bg-gray-800 dark:border-gray-700 bg-white border border-gray-100 rounded-3xl shadow-2xl p-4 space-y-4 transform transition-all duration-300 hover:shadow-3xl animate-fadeInUp  bg-opacity-90 ">

            @csrf

            <!-- Title -->
            <div class="text-center md:mb-6">
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white leading-tight">
                    {{ __('auth.register.title_prefix') }} <span class="text-[#3a5a8f]">Offitrade</span>
                </h2>
             
            </div>

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Nom')" class="block font-medium mb-2 text-sm"/>
                <x-text-input 
                    id="name" 
                    class="block w-full rounded-xl border border-gray-300 px-4 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#a1b6d8] focus:border-transparent text-gray-900 text-base transition duration-200" 
                    type="text" 
                    name="name" 
                    :value="old('name')" 
                    required 
                    autofocus 
                    autocomplete="name" 
                    placeholder="{{ __('auth.placeholder.name') }}"
                />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-600 text-xs" />
            </div>

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Adresse e-mail')" class="block font-medium mb-2 text-sm"/>
                <x-text-input 
                    id="email" 
                    class="block w-full rounded-xl border border-gray-300 px-4 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#a1b6d8] focus:border-transparent text-gray-900 text-base transition duration-200" 
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    required 
                    autocomplete="username" 
                    placeholder="{{ __('auth.placeholder.email') }}"
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-xs" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :Value="__('Mot de passe')" class="block font-medium mb-2 text-sm"/>
                <x-text-input 
                    id="password" 
                    class="block w-full rounded-xl border border-gray-300 px-4 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#a1b6d8] focus:border-transparent text-gray-800 text-base transition duration-200" 
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="new-password" 
                    placeholder="{{ __('auth.placeholder.password') }}"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-xs" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" class="block font-medium mb-2 text-sm"/>
                <x-text-input 
                    id="password_confirmation" 
                    class="block w-full rounded-xl border border-gray-300 px-4 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#a1b6d8] focus:border-transparent text-gray-800 text-base transition duration-200" 
                    type="password" 
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password" 
                    placeholder="{{ __('auth.placeholder.confirm_password') }}"
                />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600 text-xs" />
            </div>

            <!-- Already registered -->
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-700 dark:text-white">{{ __('auth.register.already') }}</span>
                <a href="{{ route('login') }}" class="text-[#4f6ba3] hover:text-[#2c4772] hover:underline font-medium transition-colors">
                    {{ __('auth.register.sign_in') }}
                </a>
            </div>

            <!-- Submit Button -->
            <x-primary-button 
                class="w-full py-3.5 rounded-xl text-lg font-semibold bg-[#3a5a8f] text-white hover:bg-[#2c4772] 
                       transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="$errors->any()"
            >
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('auth.register.button') }}
            </x-primary-button>

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
