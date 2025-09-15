<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mentions légales — {{ config('app.name', 'Offitrade') }}</title>
  @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  @endif
</head>
<body class="bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100">
  @include('layouts.navbar')

  <main class="max-w-5xl mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold mb-6 dark:text-white">Mentions légales</h1>
    <div class="prose prose-blue max-w-none dark:prose-invert">
      <p>Ce site est édité par <strong>Offitrade</strong>.</p>
      <h2>Éditeur du site</h2>
      <ul>
        <li>Dénomination: Offitrade</li>
        <li>Adresse: 7 rue des Fleurs, 37000 Tours, France</li>
        <li>Email: contact@offitrade.com</li>
      </ul>
      <h2>Hébergement</h2>
      <p>Le site est hébergé par votre prestataire d'hébergement habituel.</p>
      <h2>Propriété intellectuelle</h2>
      <p>Tous les contenus (textes, images, logos) sont protégés par le droit d'auteur. Toute reproduction est interdite sans autorisation préalable.</p>
    </div>
  </main>

  @include('layouts.footer')
</body>
</html>
