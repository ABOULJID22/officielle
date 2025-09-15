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
<body class="bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100 mt-8">
  @include('layouts.navbar')

<section class="bg-white dark:bg-gray-900">
    <div class="max-w-5xl mx-auto px-6 py-16">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Pourquoi Offitrade ?</h1>
        <p class="text-gray-600 dark:text-gray-300 mb-10">Découvrez en quoi Offitrade simplifie et optimise la gestion commerciale en officine.</p>

        <div class="space-y-10">
            <div id="gestion-operations" class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-6">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-3">1) Gestion des opérations Trade</h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                    Offitrade permet aux pharmaciens de bénéficier d’un service 100 % bénéfique. Les pharmacies qui nous font confiance évitent de perdre l’avantage financier des opérations Trade tout en optimisant leur temps pour développer d’autres axes. En moyenne, la gestion des opérations prend aux pharmaciens 8 heures par mois.
                </p>
            </div>

            <div id="accompagnement" class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-6">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-3">2) Accompagnement au développement personnalisé</h2>
                <ul class="list-disc pl-6 space-y-2 text-gray-700 dark:text-gray-300">
                    <li>Gain de temps considérable : plus besoin de recevoir les commerciaux, de comparer les offres ou de passer du temps en négociation.</li>
                    <li>Optimisation financière : négociations menées par des acheteurs experts pour obtenir les meilleures conditions tarifaires et commerciales.</li>
                    <li>Réassorts fluides et sécurisés : disponibilité des produits assurée, commandes gérées dans les délais et livraisons fiables directement en officine.</li>
                    <li>Sérénité et simplicité : une équipe dédiée gère l’intégralité du processus, de l’ouverture de marché aux commandes, permettant au pharmacien de se concentrer sur son cœur de métier : le patient.</li>
                    <li>Vision stratégique : rendez-vous réguliers en officine (tous les deux mois) pour ajuster les choix, analyser les résultats et définir les axes d’optimisation.</li>
                    <li>Flexibilité : une gestion majoritairement à distance, mais avec un suivi personnalisé et adapté à la réalité de chaque pharmacie.</li>
                    <li>Augmentation de la rentabilité : réduction des coûts d’achat et meilleure rotation des stocks pour améliorer la marge.</li>
                    <li>Accès à une expertise spécialisée : une équipe formée et connectée en permanence au marché, capable d’anticiper les évolutions et opportunités.</li>
                </ul>
            </div>
        </div>
    </div>
</section>
  @include('layouts.footer')
</body>
</html>
