<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mentions légales — {{ config('app.name', 'Offitrade') }}</title>
  @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  @endif
              <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ $siteSettings?->favicon_path ? Storage::url($siteSettings->favicon_path) : asset('favicon.png') }}" />

</head>
<body class="bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100">

  @include('layouts.navbar')

  <main class="max-w-5xl mx-auto px-4 py-12">
    <h1 class="text-3xl md:text-4xl font-bold mb-6 dark:text-white">Mentions légales</h1>

    <div class="prose prose-blue max-w-none dark:prose-invert space-y-6">

      <p>Ce site est édité par <strong>Offitrade</strong>, société responsable de la publication et du contenu.</p>

      <h2>Éditeur du site</h2>
      <ul>
        <li><strong>Dénomination :</strong> Offitrade</li>
        <li><strong>Adresse :</strong> 14 rue Beffory, 92200 Neuilly-sur-Seine, France</li>
        <li><strong>Email :</strong> <a href="mailto:contact@offitrade.fr">contact@offitrade.fr</a></li>
        <li><strong>Numéro de SIRET :</strong> 123 456 789 00010</li>
        <li><strong>Directeur de la publication :</strong> M. Mohamed Aboujid</li>
      </ul>

      <h2>Hébergement</h2>
      <ul>
        <li><strong>Hébergeur :</strong> OVH / Hostinger / Votre hébergeur</li>
        <li><strong>Adresse :</strong> 2 rue Kellermann, 59100 Roubaix, France</li>
        <li><strong>Téléphone :</strong> +33 1 23 45 67 89</li>
      </ul>

      <h2>Propriété intellectuelle</h2>
      <p>Tous les contenus présents sur ce site, y compris textes, images, logos, vidéos et icônes, sont la propriété exclusive d’<strong>Offitrade</strong> ou de ses partenaires. Toute reproduction, modification ou redistribution sans autorisation écrite est strictement interdite.</p>

      <h2>Collecte de données personnelles</h2>
      <p>Les informations recueillies via les formulaires de contact ou d’inscription sont utilisées uniquement pour le traitement des demandes. Conformément au RGPD, vous pouvez exercer vos droits d’accès, de rectification ou de suppression en contactant <a href="mailto:contact@offitrade.fr">contact@offitrade.fr</a>.</p>

      <h2>Cookies</h2>
      <p>Ce site utilise des cookies pour améliorer l’expérience utilisateur et analyser le trafic. Vous pouvez gérer vos préférences via les paramètres de votre navigateur.</p>

      <h2>Responsabilité</h2>
      <p>Offitrade s’efforce de fournir des informations fiables et à jour. Toutefois, nous ne pouvons garantir l’exactitude complète des contenus et déclinons toute responsabilité pour toute erreur ou omission.</p>

      <h2>Contact</h2>
      <p>Pour toute question concernant les mentions légales, vous pouvez nous contacter à l’adresse suivante : <a href="mailto:contact@offitrade.fr">contact@offitrade.fr</a></p>

      <p class="text-sm text-gray-500 dark:text-gray-400 mt-6">Dernière mise à jour : 18 septembre 2025</p>

    </div>
  </main>

  @include('layouts.footer')

</body>
</html>
