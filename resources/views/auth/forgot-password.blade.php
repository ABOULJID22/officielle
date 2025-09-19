<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-lg">
            <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-3xl shadow-2xl overflow-hidden">
                <div class="px-8 py-6 md:px-12 md:py-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('auth.forgot.title', ['app' => 'Offitrade']) }}</h3>
                    </div>

                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">{{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}</p>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                        @csrf

                        <div>
                            <x-input-label for="email" :value="__('auth.placeholder.email')" />
                            <x-text-input id="email" class="mt-2" type="email" name="email" :value="old('email')" required autofocus placeholder="{{ __('auth.placeholder.email') }}" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm" />
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('login') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">{{ __('auth.forgot.back_to_login') }}</a>
                            <x-primary-button class="bg-[#4f6ba3] hover:bg-[#3a5680]">
                                {{ __('auth.forgot.send_link') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
