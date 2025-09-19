<x-filament::page>
    <div class="space-y-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Besoin d’aide ? Envoyez-nous un message, nous vous répondrons rapidement dans Conversations support.</p>
            </div>

        </div>

        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/30 dark:text-red-200">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            <div class="rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-800 dark:border-green-800 dark:bg-green-900/30 dark:text-green-200">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            
            <!-- Form column -->
            <div class="lg:col-span-2">
                <x-filament::section>
                    <x-slot name="heading">Envoyer un message au support</x-slot>
                    <form x-data="{ loading: false }" x-on:submit="loading = true" method="POST" action="{{ route('client.support.submit') }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>

                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nom</label>
                            <input type="text" id="name" name="name"
                                class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-700 
                                        dark:bg-gray-900 dark:text-gray-100 
                                        focus:border-primary-500 focus:ring-primary-500"
                                value="{{ old('name', auth()->user()->name) }}" required readonly />
                           </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                                <input type="email" id="email" name="email" class="mt-1 block w-full border rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-primary-500 focus:ring-primary-500" value="{{ old('email', auth()->user()->email) }}" required readonly />
                            </div>
                            <div class="md:col-span-2">
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Téléphone</label>
                                <input type="text" id="phone" name="phone" class="mt-1 block w-full border rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-primary-500 focus:ring-primary-500" value="{{ old('phone', auth()->user()->phone) }}" />
                            </div>
                            <div class="md:col-span-2">
                                <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Message</label>
                                <textarea id="message" name="message" rows="7" class="mt-1 block w-full rounded-lg dark:border-white  dark:bg-gray-900 dark:text-gray-100 focus:border-primary-500 focus:ring-primary-500" placeholder="Décrivez votre demande avec le plus de détails possibles" required>{{ old('message') }}</textarea>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-3">
                            <x-filament::button type="submit" x-bind:disabled="loading">
                                <svg x-show="loading" x-cloak class="-ml-0.5 mr-2 inline h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                <span x-show="!loading">Envoyer</span>
                                <span x-show="loading" x-cloak>Envoi…</span>
                            </x-filament::button>
                            <x-filament::button color="gray" type="reset" x-bind:disabled="loading" class="!bg-gray-200 !text-gray-800 dark:!bg-gray-800 dark:!text-gray-200">Réinitialiser</x-filament::button>
                        </div>
                    </form>
                </x-filament::section>
            </div>
        </div>
    </div>


</x-filament::page>
