<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Politique de confidentialité — {{ config('app.name', 'Offitrade') }}</title>
  @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  @endif

              <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ $siteSettings?->favicon_path ? Storage::url($siteSettings->favicon_path) : asset('favicon.png') }}" />

</head>
<body class="bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100">

  @include('layouts.navbar')

  <main class="max-w-5xl mx-auto px-4 py-16 sm:py-24">
    <h1 class="text-3xl md:text-4xl font-bold mb-6 dark:text-white">Politique de confidentialité</h1>

    <div class="prose prose-blue max-w-none dark:prose-invert space-y-6">
      <p>L'utilisation de notre site Internet ou de nos applications implique la collecte, le traitement et la conservation de données à caractère personnel.</p>

      <p>Conformément à la loi informatique et libertés de 1978, les fichiers contenant ces données ont été déclarés auprès de la Commission nationale de l'informatique et des libertés (CNIL).</p>

      <p>Notre politique de confidentialité est en vigueur depuis le 25 mai 2018, en conformité avec le Règlement général sur la protection des données (RGPD).</p>

      <h2>Responsable du traitement des données</h2>
      <p>Le responsable du traitement est indiqué dans notre page de mentions légales, section "Déclaration CNIL".</p>

      <h2>Hébergement</h2>
      <p>Les données sont hébergées par notre prestataire d'hébergement, dont les informations figurent dans la page "Hébergeur" des mentions légales.</p>

      <h2>Transfert des données à caractère personnel</h2>
      <p>Les données personnelles collectées sont exclusivement utilisées par Offitrade pour les finalités décrites dans cette politique.</p>

      <h2>Personnes concernées</h2>
      <p>Toute personne naviguant sur notre site ou utilisant nos applications est susceptible de faire l'objet d'une collecte et d'un traitement de ses données personnelles.</p>

      <h2>Nature des données collectées et traitées</h2>

      <h3>Site Internet</h3>
      <p>Lors de la navigation, l'adresse IP peut être collectée dans des fichiers journaux sécurisés. Ces données permettent d'identifier d'éventuelles anomalies techniques ou attaques informatiques.</p>

      <h3>Formulaire de contact</h3>
      <p>Lorsque vous remplissez un formulaire de contact, nous recevons les informations suivantes : prénom, nom, email, téléphone, sujet et message. Les emails sont supprimés une fois le traitement effectué.</p>

      <h3>Cookies</h3>
      <p>Notre site utilise Google Analytics pour étudier de manière anonyme les visites. Les cookies sont conservés 12 mois sur votre appareil et peuvent être désactivés via vos préférences de navigation.</p>

      <h3>Sites d'officines partenaires</h3>
      <p>Pour accéder aux sites des officines partenaires, notre politique ne s'applique plus ; les données sont alors régies par la politique de confidentialité de chaque officine.</p>

      <h3>Applications "Offitrade" et "Offitrade Santé"</h3>
      <p>Les applications collectent uniquement les informations nécessaires pour l'inscription et la gestion des rappels de prises de médicaments. Les e-ordonnances sont traitées directement par l'utilisateur et l'officine.</p>

      <h2>Traitement des données</h2>
      <p>Les données sont insérées, modifiées ou supprimées via nos logiciels sur les serveurs de l’hébergeur. Chaque action est tracée avec la date et l’auteur.</p>

      <h2>Sécurisation et conservation des données</h2>
      <p>Les données personnelles et de santé sont conservées sur des serveurs sécurisés par un hébergeur conforme aux obligations légales. Elles sont chiffrées et isolées pour garantir la sécurité et la confidentialité.</p>

      <h2>Droits des utilisateurs</h2>
      <p>Conformément au RGPD, vous disposez du droit d’accès, de rectification, de suppression, de limitation ou d’opposition au traitement de vos données, ainsi que du droit à la portabilité.</p>
      <p>Pour exercer vos droits, contactez-nous via notre formulaire ou par courrier postal avec une copie d’une pièce d’identité.</p>
      <p>En cas de conflit non résolu, vous pouvez saisir la CNIL : <a href="https://www.cnil.fr" target="_blank" rel="noopener noreferrer">https://www.cnil.fr</a></p>

      <p class="text-sm text-gray-500 dark:text-gray-400 mt-6">Dernière mise à jour : 18 septembre 2025</p>
    </div>
  </main>

  @include('layouts.footer')

</body>
</html>
