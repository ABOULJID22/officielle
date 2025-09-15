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

  <main class="max-w-5xl mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold mb-6 dark:text-white">Politique de confidentialité</h1>
    <div class="prose prose-blue max-w-none dark:prose-invert">
      <p>L'utilisation de notre site Internet ou de nos applications implique des opérations de collecte, de traitement et de conservation de données à caractère personnel.</p>

      <p>En application de la loi informatique et libertés de 1978, les fichiers contenant de telles données ont fait l'objet d'une déclaration normale auprès de la Commission nationale informatique et libertés (CNIL). Cette déclaration a été accomplie par meSoigner SAS.</p>

      <p>Notre politique de confidentialité entre en vigueur le 25 mai 2018 en conformité avec les exigences du Règlement général sur la protection des données (Règlement GDPR).</p>

      <h2>Responsable du Traitement des données</h2>
      <p>Le responsable du traitement des données est indiqué sur notre page de mentions légales, section "Déclaration CNIL".</p>

      <h2>Hébergeur</h2>
      <p>L'hébergeur informatique des données est indiqué sur notre page de mentions légales, section "Hébergeur du site".</p>

      <h2>Transfert des données à caractère personnel</h2>
      <p>Les données à caractère personnel collectées sont exclusivement réservées à Mesoigner.</p>

      <h2>Personnes concernées par la collecte et le traitement des données</h2>
      <p>Toute personne naviguant notre site internet est susceptible d'être concernée par une collecte et un traitement de ses données à caractère personnel.</p>

      <h2>Nature des données collectées et traitées</h2>
      <h3>Site Internet</h3>
      <p>La visite sur le site Internet entraîne la collecte de l'adresse IP dans des fichiers de journalisation. L'accès à ces fichiers est restreint et sécurisé, seul Mesoigner et l'Hébergeur y ont accès. La collecte nous permet le cas échéant d'identifier d'éventuels bogues informatiques et de repérer d'éventuelles attaques informatiques.</p>

      <h3>Formulaire de contact</h3>
      <p>Lorsqu'un visiteur renseigne le formulaire de contact, nous recevons par courriel les informations personnelles du formulaire qu'il nous communique, à savoir : prénom et nom, adresse email, téléphone, sujet du message, message. Nous supprimons le courriel manuellement une fois le message traité.</p>

      <h3>Cookies</h3>
      <p>Une fonctionnalité de notre site s'appuie sur un service proposé par un éditeur tiers Google Analytics, qui nous permet d'étudier de manière totalement anonyme les visites sur ce site. Les cookies sont conservés pour une durée d'un an sur l'ordinateur du visiteur.</p>
      <p>Google Analytics dépose un cookie, que vous avez cependant la possibilité d'interdire en cliquant sur vos préférences de gestion de cookies</p>
      <p>Toutes les données collectées par le biais de ces cookies sont conservées pour une durée de 26 mois.</p>

      <h3>Site Internet des officines</h3>
      <p>Mesoigner.fr permet de trouver le site Internet d'une officine. Une fois sur le site Internet d'une officine, notre politique de confidentialité ne s'applique plus, c'est celle de l'officine qui prend le relai.</p>

      <h3>Application "meSoigner"</h3>
      <p>Nous n'effectuons aucune collecte d'information dans l'application. Notre application sert uniquement à trouver une officine partenaire et à s'y inscrire ou à s'y connecter.<br>
      Une fois inscrit dans une officine, l'utilisateur doit se référer à la politique de confidentialité disponible sur le site de l'officine.</p>

      <h3>Application "meSoigner santé"</h3>
      <h4>Inscription</h4>
      <p>Les données collectées dans le cadre de l'inscription à l'application "meSoigner santé" sont l'adresse email, le mot de passe (stocké de manière hashée) le prénom, le nom et le téléphone.</p>

      <h4>Rappels de prise</h4>
      <p>L'utilisateur peut indiquer ses habitudes de vie, ainsi que les horaires, quantités, durées et fréquences de ses rappels de prise de médicaments.</p>

      <h4>E-ordonnance</h4>
      <p>L'utilisateur peut retrouver les e-ordonnances envoyées par son médecin. Elles ne sont jamais traitées par nos services et sont envoyées par l'utilisateur à une officine.</p>

      <h2>Comment ces données à caractère personnel sont-elles traitées ?</h2>
      <p>Les données sont insérées, lues, modifiées ou supprimées par notre logiciel installé sur les serveurs de notre hébergeur.</p>
      <p>Les données chiffrées sont tracées au niveau de leur insertion, lecture, modification et suppression. L'auteur et la date de chaque action est conservée.</p>
      <p>Les données sont sauvegardées journalièrement par l'hébergeur.</p>

      <h2>Conservation et sécurisation de vos données à caractère personnel</h2>
      <p>Tous les fichiers comportant des données de santé à caractère personnel font l'objet d'une conservation sur des serveurs informatiques gérés et sécurisés par un hébergeur de données de santé personnelles répondant à toutes les obligations légales applicables en matière de conservation de données de santé. Les données chiffrées sont hébergées sur une base de données séparée, en ayant subi une série de traitements informatiques visant à chiffrer les données et à la rendre impossible d'accès.</p>

      <h2>Droits des utilisateurs de notre site internet et de nos applications</h2>
      <p>Chaque internaute inscrit dispose « du droit de demander au responsable du traitement l'accès aux données à caractère personnel, la rectification ou l'effacement de celles-ci, ou une limitation du traitement relatif à la personne concernée, ou du droit de s'opposer au traitement et du droit à la portabilité des données ; »</p>
      <p>Les internautes ayant créé un compte personnel peuvent en demander la suppression et retirer à tout moment leur consentement à tout futur traitement de leurs données à caractère personnel.</p>
      <p>L'internaute peut exercer les droits mentionnés ci-dessus, en nous contactant par formulaire de contact ou voie postale. Cette demande doit être accompagnée d'une copie d'une pièce probante, en noir et blanc et barrée, afin de pouvoir justifier de votre identité.</p>
      <p>Toutes questions et réclamations relatives à la présente politique de confidentialité et au traitement des données à caractère personnel doivent être adressées en priorité au responsable du traitement. En cas de conflit non résolu sur ces points, l'utilisateur de notre site internet peut s'adresser à la CNIL, autorité de contrôle de la protection des données à caractère personnel compétente pour introduire une réclamation
      <a href="https://www.offitrade.fr" target="_blank" rel="noopener noreferrer">https://www.cnil.fr</a>.</p>
    </div>
  </main>

  @include('layouts.footer')
</body>
</html>
