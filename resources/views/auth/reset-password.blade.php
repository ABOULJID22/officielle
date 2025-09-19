<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-lg">
            <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-3xl shadow-2xl overflow-hidden">
                <div class="px-8 py-6 md:px-12 md:py-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('auth.forgot_section.title') }}</h3>
                    </div>

                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">{{ __('Please provide a new secure password for your account.') }}</p>

                    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                        @csrf

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email Address -->
                        <div>
                            <x-text-input id="email" class="mt-2" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" placeholder="{{ __('auth.placeholder.email') }}" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm" />
                        </div>

                        <!-- Password -->
                        <div>
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" class="mt-2" type="password" name="password" required autocomplete="new-password"  />
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm" />
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                            <x-text-input id="password_confirmation" class="mt-2" type="password" name="password_confirmation" required autocomplete="new-password"  />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600 text-sm" />
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('login') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">{{ __('auth.forgot_section.back_to_login') }}</a>
                            <x-primary-button class="bg-[#4f6ba3] hover:bg-[#3a5680]">
                                {{ __('Reset Password') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

