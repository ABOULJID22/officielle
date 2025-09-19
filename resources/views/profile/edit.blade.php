<x-app-layout>
    {{-- En-tête de la page --}}
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight drop-shadow-sm">
            {{ __('Mon Profil') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Carte de Bienvenue Utilisateur -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6 flex items-center space-x-6 transition-all duration-300 hover:shadow-xl">
                <img src="{{ Auth::user()->avatar }}" alt="Avatar" class="w-20 h-20 rounded-full object-cover border-4 border-blue-500 dark:border-blue-400 shadow-md">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</h3>
                    <p class="text-md text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                </div>
            </div>

            <!-- Section: Informations du Profil -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-lg rounded-2xl transition-all duration-300">
                <div class="max-w-2xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Section: Mettre à jour le mot de passe -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-lg rounded-2xl transition-all duration-300">
                <div class="max-w-2xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Section: Supprimer le compte -->
            <div class="p-4 sm:p-8 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 shadow-lg rounded-2xl transition-all duration-300">
                <div class="max-w-2xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
