<x-filament-widgets::widget>
    <x-filament::section class="flex justify-between rounded-2xl shadow-lg bg-gradient-to-br from-white to-gray-50 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="flex flex-col items-center justify-center p-6 bg-white rounded-xl shadow-md transform transition duration-300 hover:scale-105 hover:shadow-lg border border-gray-100">
                <x-filament::icon icon="heroicon-m-users" class="h-10 w-10 text-indigo-600 mb-3" />
                <div class="text-center">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Utilisateurs</div>
                    <div class="text-4xl font-extrabold text-gray-900 mt-1">{{ number_format($usersCount) }}</div>
                </div>
            </div>
          
        </div>
    </x-filament::section>
        <x-filament::section class="rounded-2xl shadow-lg bg-gradient-to-br from-white to-gray-50 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Card for Posts --}}
            <div class="flex flex-col items-center justify-center p-6 bg-white rounded-xl shadow-md transform transition duration-300 hover:scale-105 hover:shadow-lg border border-gray-100">
                <x-filament::icon icon="heroicon-m-rectangle-stack" class="h-10 w-10 text-green-600 mb-3" />
                <div class="text-center">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Articles</div>
                    <div class="text-4xl font-extrabold text-gray-900 mt-1">{{ number_format($postsCount) }}</div>
                </div>
            </div>

           

          
        </div>
    </x-filament::section>
        <x-filament::section class="rounded-2xl shadow-lg bg-gradient-to-br from-white to-gray-50 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
             {{-- Card for Contacts --}}
            <div class="flex flex-col items-center justify-center p-6 bg-white rounded-xl shadow-md transform transition duration-300 hover:scale-105 hover:shadow-lg border border-gray-100">
                <x-filament::icon icon="heroicon-m-envelope" class="h-10 w-10 text-rose-600 mb-3" />
                <div class="text-center">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Contacts</div>
                    <div class="text-4xl font-extrabold text-gray-900 mt-1">{{ number_format($contactsCount) }}</div>
                </div>
            </div>

           

          
        </div>
    </x-filament::section>
</x-filament-widgets::widget>