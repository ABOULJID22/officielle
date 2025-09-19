# Déployer sur Hostinger (Laravel 12 + Filament 4)

Ce guide explique deux façons sûres de déployer votre application et d’éviter les problèmes liés au dossier `public_html` sur Hostinger.

## 1) Choisir une mise en place (recommandé → A)

- Option A – Changer le dossier du site vers `.../public` (idéal) :
  - Dans hPanel: Domaine → Gérer → Site web → Changer le dossier du site.
  - Placez le projet DANS LE DOSSIER DU DOMAINE, puis pointez la racine vers son dossier `public`.
  - Exemples:
    - Si votre domaine est chez Hostinger: `/home/USER/domains/votre-domaine.tld/laravel` → dossier du site: `/home/USER/domains/votre-domaine.tld/laravel/public`.
    - Variante simple: `public_html/offitrade2` → dossier du site: `public_html/offitrade2/public`.
  - Avantage: Pas de modification de code, structure Laravel standard.

- Option B – Laisser `public_html` comme racine et y mettre les fichiers publics:
  - Placez le projet à l’extérieur de `public_html`, par ex. `/home/USER/offitrade2` (recommandé), ou dans `/home/USER/domains/votre-domaine.tld/laravel`.
  - Copiez TOUT le contenu de `offitrade2/public/` dans le `public_html` de votre domaine. Chez Hostinger c’est souvent: `/home/USER/domains/votre-domaine.tld/public_html/`.
  - Adaptez `public_html/index.php` pour pointer vers `vendor` et `bootstrap` du projet (voir exemples selon l’arborescence ci‑dessous).

> Remarque importante: le mot « public » ici désigne le dossier `public` de Laravel (dans votre projet), à ne pas confondre avec le `public_html` fourni par l’hébergeur.

## 2) Préparer le build en local (Windows PowerShell)

```powershell
# PHP 8.2+ requis, Node 18+ recommandé
composer install --no-dev --optimize-autoloader
npm ci
npm run build

# Vérifier que les assets existent
Get-ChildItem .\public\build -Recurse | Select-Object -First 5
```

Résultat attendu: un dossier `public/build` avec vos assets Vite.

## 3) Upload des fichiers

- Uploadez tout le projet, mais SANS `node_modules` ni fichiers de dev.
- Si Composer est disponible en SSH côté serveur, vous pouvez exécuter `composer install --no-dev` sur le serveur au lieu d’uploader `vendor/`.
- Option A: uploadez le projet dans `public_html/offitrade2` (ou un nom de dossier de votre choix), puis changez le dossier du site vers `public_html/offitrade2/public`.
- Option B: uploadez le projet en dehors de `public_html` et SEULEMENT le contenu de `public/` dans `public_html`.

## 4) Configurer l’environnement (.env)

- Créez `.env` sur le serveur (ne pas commiter) avec:
  - `APP_ENV=production`
  - `APP_DEBUG=false`
  - `APP_URL=https://votre-domaine.tld`
  - DB: `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
  - Queue: `QUEUE_CONNECTION=database` (recommandé en mutualisé)
- Si `APP_KEY` est vide, générez-la en SSH dans le dossier projet: `php artisan key:generate`.

## 5) Permissions et liens de stockage

En SSH, à la racine du projet:

```bash
chmod -R 775 storage bootstrap/cache || true
php artisan storage:link || true
```

Si les symlinks sont désactivés: copiez `storage/app/public` vers `public/storage` via un cron/rsync ou changez la stratégie d’upload pour écrire directement dans `public/storage`.

## 6) Migrations et caches

```bash
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

## 7) Exemple d’`index.php` pour Option B

Structure (cas 1 – public_html à la racine du HOME):

```
/home/USER/offitrade2          # racine projet (artisan, vendor, bootstrap, ...)
/home/USER/public_html         # racine web (contient le contenu de /public)
```

Éditez `/home/USER/public_html/index.php` ainsi:

```php
<?php
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/../offitrade2/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../offitrade2/vendor/autoload.php';

/** @var Application $app */
$app = require_once __DIR__.'/../offitrade2/bootstrap/app.php';

$app->handleRequest(Request::capture());
```

Adaptez `offitrade2` au nom réel de votre dossier projet.

Vérifiez aussi que `.htaccess` de `public` est bien dans `public_html`.

### Variante Hostinger (cas 2 – public_html dans `domains/`)

Structure typique Hostinger pour un domaine:

```
/home/USER/offitrade2                      # racine projet (artisan, vendor, bootstrap, ...)
/home/USER/domains/votre-domaine.tld/public_html   # racine web du domaine
```

Dans ce cas, depuis `public_html` il faut remonter de 2 niveaux pour atteindre le HOME, puis aller dans `offitrade2`.

Éditez `/home/USER/domains/votre-domaine.tld/public_html/index.php` ainsi:

```php
<?php
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/../../offitrade2/storage/framework/maintenance.php')) {
  require $maintenance;
}

require __DIR__.'/../../offitrade2/vendor/autoload.php';

/** @var Application $app */
$app = require_once __DIR__.'/../../offitrade2/bootstrap/app.php';

$app->handleRequest(Request::capture());
```

Adaptez `offitrade2` au nom réel du dossier projet. Vous pouvez valider les chemins via `pwd` et `ls` en SSH.

## 8) CRON (planificateur) et file d’attente

Sur mutualisé, n’utilisez pas `schedule:work`. Ajoutez un cron chaque minute:

```
PHP binaire: /usr/bin/php  (ou celui fourni par Hostinger)
Commande:   /usr/bin/php /home/USER/offitrade2/artisan schedule:run >> /home/USER/cron.log 2>&1
Fréquence:  Chaque minute
```

Pour les queues (si `QUEUE_CONNECTION=database`):

```
Commande cron toutes les minutes:
/usr/bin/php /home/USER/offitrade2/artisan queue:work --sleep=3 --tries=3 --timeout=90 --stop-when-empty >> /home/USER/queue.log 2>&1
```

> `schedule:work` peut échouer en mutualisé; `schedule:run` via cron est la bonne approche.

## 9) HTTPS, PHP et sécurité

- Activez le SSL et forcez HTTPS (hPanel → Redirections/Forcer HTTPS, ou middleware).
- Réglez PHP sur 8.2 dans hPanel (nécessaire pour Laravel 12).
- Masquez les erreurs publiques (`APP_DEBUG=false`).
- Surveillez `storage/logs/*.log` en cas de soucis.

---

### Check‑list rapide

1. PHP 8.2, SSL activé, domaine pointé.
2. `composer install --no-dev` et `npm run build` effectués.
3. Option A (docroot → public) OU Option B (`index.php` ajusté) appliquée.
4. `.env` rempli et `APP_KEY` générée.
5. Permissions `storage/` et `bootstrap/cache` OK, `storage:link` si possible.
6. `php artisan migrate --force` et caches compilés.
7. CRON `schedule:run` + cron pour `queue:work` en place.

# PHP 8.2+, Node 18+ recommandés
composer install --no-dev --optimize-autoloader
npm ci
npm run build