<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Politique de confidentialité — {{ config('app.name', 'Offitrade') }}</title>
  @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  @endif
</head>
<body class="bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100">
  @include('layouts.navbar')
<div class="max-w-2xl mx-auto py-12">
    <h1 class="text-2xl font-semibold mb-4">Demander un profil Pharmacien</h1>
    @if (session('status'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
    @endif
    <form method="POST" action="{{ route('pharmacist.request.store') }}" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Nom du pharmacien</label>
                <input type="text" name="pharmacist_name" class="mt-1 w-full rounded border-gray-300" required>
                @error('pharmacist_name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Numéro d'inscription</label>
                <input type="text" name="registration_number" class="mt-1 w-full rounded border-gray-300" required>
                @error('registration_number')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium">Message (optionnel)</label>
            <textarea name="message" rows="4" class="mt-1 w-full rounded border-gray-300"></textarea>
            @error('message')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Envoyer la demande</button>
    </form>
</div>
  @include('layouts.footer')
</body>
</html>