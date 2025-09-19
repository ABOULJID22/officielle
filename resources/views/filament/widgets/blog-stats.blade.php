<x-filament-widgets::widget>
    <x-filament::section class="rounded-2xl shadow-lg bg-gradient-to-br from-white to-gray-50 p-6">
        <div class="flex flex-col gap-4">
            <!-- Users -->
            <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 50)"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-2'"
                 class="w-full p-5 bg-white rounded-xl shadow-md border border-gray-100 transition-all duration-500 ease-out hover:shadow-lg hover:-translate-y-1">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center gap-3">
                        <x-filament::icon icon="heroicon-m-users" class="h-8 w-8 text-indigo-600" />
                        <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Utilisateurs</div>
                    </div>
                    <div class="text-3xl font-extrabold text-gray-900">{{ number_format($usersCount) }}</div>
                </div>
            </div>

            <!-- Posts -->
            <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 150)"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-2'"
                 class="w-full p-5 bg-white rounded-xl shadow-md border border-gray-100 transition-all duration-500 ease-out hover:shadow-lg hover:-translate-y-1">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center gap-3">
                        <x-filament::icon icon="heroicon-m-rectangle-stack" class="h-8 w-8 text-green-600" />
                        <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Articles</div>
                    </div>
                    <div class="text-3xl font-extrabold text-gray-900">{{ number_format($postsCount) }}</div>
                </div>
            </div>

            <!-- Contacts -->
            <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 250)"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-2'"
                 class="w-full p-5 bg-white rounded-xl shadow-md border border-gray-100 transition-all duration-500 ease-out hover:shadow-lg hover:-translate-y-1">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center gap-3">
                        <x-filament::icon icon="heroicon-m-envelope" class="h-8 w-8 text-rose-600" />
                        <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Contacts</div>
                    </div>
                    <div class="text-3xl font-extrabold text-gray-900">{{ number_format($contactsCount) }}</div>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>