<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Offitrade - Blog</title>
    <link rel="icon" type="image/png" href="/favicon.png" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="bg-gray-50 dark:bg-gray-900 font-sans text-gray-900 dark:text-gray-100 antialiased">
    @include('layouts.navbar')

    <main class="py-16 sm:py-24">

       <!-- Barre de filtre (améliorée) -->
        <section class="sticky top-0 z-20 border-b border-gray-200/70 dark:border-gray-800/70 bg-white/80 dark:bg-gray-900/70 backdrop-blur">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex flex-col md:flex-row items-center justify-between gap-4">
            <!-- Catégories -->
            <div class="flex flex-wrap items-center gap-2">
            <button class="px-4 py-2 text-sm font-semibold rounded-full bg-indigo-600 text-white shadow hover:bg-indigo-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500">
                Tous
            </button>
            <button class="px-4 py-2 text-sm font-semibold rounded-full bg-gray-100 text-gray-900 hover:bg-indigo-600 hover:text-white dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-indigo-500 dark:hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500">
                Productivité
            </button>
            <button class="px-4 py-2 text-sm font-semibold rounded-full bg-gray-100 text-gray-900 hover:bg-indigo-600 hover:text-white dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-indigo-500 dark:hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500">
                Tendances
            </button>
            <button class="px-4 py-2 text-sm font-semibold rounded-full bg-gray-100 text-gray-900 hover:bg-indigo-600 hover:text-white dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-indigo-500 dark:hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500">
                Sécurité
            </button>
            <button class="px-4 py-2 text-sm font-semibold rounded-full bg-gray-100 text-gray-900 hover:bg-indigo-600 hover:text-white dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-indigo-500 dark:hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500">
                Conseils
            </button>
            </div>

            <!-- Tri -->
            <div class="relative w-full md:w-auto">
            <select class="w-full appearance-none pl-4 pr-10 py-2 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500">
                <option>Plus récents</option>
                <option>Les plus populaires</option>
                <option>Les mieux notés</option>
            </select>
            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-500 dark:text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
            </svg>
            </div>
        </div>
        </section>

        <!-- Grille d’articles (améliorée) -->
        <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Carte d’article -->
            <article class="group overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-md ring-1 ring-gray-200/60 dark:ring-gray-700/50 transition-all duration-300 hover:shadow-xl">
            <div class="relative">
                <img src="/images/img1.jpg" alt="Image article" class="w-full aspect-[16/9] object-cover transition-transform duration-300 group-hover:scale-[1.03]">
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-black/0 to-black/0 pointer-events-none"></div>
                <span class="absolute top-4 left-4 inline-flex items-center rounded-full bg-indigo-600/90 text-white text-xs font-semibold px-3 py-1 ring-1 ring-white/10 backdrop-blur">
                Productivité
                </span>
            </div>
            <div class="p-6">
                <h3 class="text-xl font-semibold tracking-tight mb-2 text-gray-900 dark:text-white transition-colors group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
                Optimiser votre Productivité avec Offitrade
                </h3>
                <p class="text-gray-600 dark:text-gray-300 line-clamp-3 mb-4">
                Découvrez comment nos solutions peuvent transformer votre gestion de projet et stimuler l'efficacité de vos équipes.
                </p>
                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-gray-700 pt-4">
                <span>Il y a 3 jours</span>
                <span>Par Jean Dupont</span>
                </div>
                <a href="#" class="mt-4 inline-flex items-center font-semibold text-indigo-600 dark:text-indigo-400 group-hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 rounded">
                Lire la suite
                <svg class="ml-1 w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.25 8.25L21 12l-3.75 3.75M21 12H3"/>
                </svg>
                </a>
            </div>
            </article>

            <!-- Dupliquez la carte ci-dessus pour d’autres articles -->
        </div>
        </section>

        <!-- CTA Newsletter (amélioré) -->
        <section class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 to-blue-600 dark:from-indigo-500 dark:to-blue-500 p-8 sm:p-12 text-white shadow-xl">
            <div class="pointer-events-none absolute -inset-1 bg-[radial-gradient(ellipse_at_top_right,rgba(255,255,255,0.15),transparent_60%)]"></div>
            <div class="relative">
                <h2 class="text-3xl md:text-4xl font-extrabold mb-3 tracking-tight">Restez informé !</h2>
                <p class="text-lg opacity-90 max-w-2xl">Abonnez-vous à notre newsletter et recevez les dernières actualités directement dans votre boîte mail.</p>
                <form class="mt-8 max-w-xl mx-auto flex flex-col sm:flex-row gap-3">
                <input type="email" placeholder="Votre adresse email" required
                        class="flex-1 h-12 rounded-xl px-4 text-gray-900 placeholder-gray-500 bg-white/95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-indigo-600">
                <button type="submit"
                        class="h-12 px-6 rounded-xl font-semibold bg-white text-indigo-700 hover:bg-gray-100 active:scale-[0.99] transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-indigo-600">
                    S'abonner
                </button>
                </form>
            </div>
            </div>
        </div>
        </section>


    </main>

    {{-- @include('layouts.footer') --}}
</body>
</html>
