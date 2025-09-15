<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Non Trouvée</title>
    <!-- Intégration de Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      // Configuration pour ajouter votre couleur personnalisée au thème de Tailwind
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              // Vous pouvez maintenant utiliser `text-customBlue` ou `bg-customBlue`
              customBlue: '#4f6ba3',
            }
          }
        }
      }
    </script>
</head>
<body class="bg-gray-50">
    <!-- Conteneur principal pour centrer le contenu verticalement et horizontalement -->
    <div class="flex items-center justify-center min-h-screen px-4">
        
        <!-- Carte contenant tous les éléments, avec ombre et coins arrondis -->
        <div class="bg-white p-8 sm:p-12 rounded-2xl shadow-xl max-w-md w-full text-center">
            
            <!-- Numéro d'erreur, stylisé pour être l'élément central -->
            <div class="text-9xl font-black text-customBlue">
                404
            </div>

            <!-- Titre principal de la page d'erreur -->
            <div class="mt-6 text-3xl font-bold text-gray-800 lg:text-4xl">
                Page Introuvable
            </div>

            <!-- Message descriptif pour guider l'utilisateur -->
            <div class="mt-4 text-lg font-medium text-gray-500">
                Désolé, la page que vous cherchez n'existe pas ou a été déplacée.
            </div>

            <!-- Bouton d'action avec effets de survol dynamiques -->
            <a href="{{ url('/') }}" class="mt-8 inline-block rounded-lg bg-customBlue px-8 py-3 font-semibold text-white shadow-md transition-all duration-300 hover:scale-105 hover:bg-opacity-90">
                Retour à l'accueil
            </a>
        </div>
    </div>
</body>
</html>
