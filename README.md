# astral-template-nova

Prototype d'intégration d'**Astral Nova CSS** dans Astral Template. Le package reprend volontairement l'organisation et les conventions d'`astral-template` afin de faciliter une future intégration.

## État

Version **`1.0.0`** (stable) — layouts `admin`, `marketing`, `auth` et partials Nova (shell mobile, steps, icon API, formulaires).

## Compatibilité

- PHP `^8.3`.
- Nova CSS : **`^1.0`** (`nova-header`, marketing, code-window, shell, forms…).
- Astral Core : compatible avec Astral Core 2.1. Le provider reste optionnel ; le rendu des partials peut être utilisé indépendamment du provider.

## Installation locale avec Composer

Dans le `composer.json` de l'application de test, ajouter un dépôt path :

```json
{
  "repositories": [
    { "type": "path", "url": "../astral-template-nova", "options": { "symlink": true } }
  ],
  "require": { "astral-php/astral-template-nova": "@dev" },
  "minimum-stability": "dev",
  "prefer-stable": true
}
```

Puis exécuter `composer update astral-php/astral-template-nova`. Ajuster le chemin relatif selon l'organisation locale.

## Provider Astral

```php
use Astral\TemplateNova\TemplateServiceProvider;

return [
    // ...
    TemplateServiceProvider::class,
];
```

Le provider publie les layouts dans `views/layout/` et les partials dans `views/partials/astral-template-nova/`. Il ne remplace jamais un fichier déjà présent.

## Utilisation des layouts

Conserver le mécanisme habituel d'Astral Template : `$this->view->setLayout('admin')` ou `marketing`. Les layouts acceptent notamment `title`, `content`, `brand`, `adminNav` ou `marketingNav`, `theme` et `mode`.

Valeurs de thème prises en charge par Nova 0.9 : `white`, `blue`, `maroon-light`, `red`, `indigo`, `orange`, `green`. Modes : `light`, `dark`, `system`.

## Assets Nova

Par défaut, les layouts référencent `/vendor/astral-php/astral-nova-css/dist/nova.css`. Cette valeur est remplaçable avec `novaAssetBase` ou `novaCss`. Le JavaScript peut être fourni via `novaJs`. `novaEnabled => false` désactive le chargement CSS par défaut.

Le package ne duplique pas les fichiers de Nova CSS : l'application de test doit rendre les assets Nova accessibles publiquement (copie/build dans `public/assets/nova`, lien de développement ou mécanisme d'assets de l'application).

## Partials

```php
<?= $viewEngine->partial('partials/astral-template-nova/alert', [
    'type' => 'success', 'message' => 'Enregistré.'
]) ?>
```

Partials inclus : alert, breadcrumb, empty-state, page-header, table, navigation/sidebar, navigation/user-menu, interface/theme-switcher, feedback/flash, feedback/error-page, forms/field, forms/field-error et data/pagination.

### Pages d’erreur HTTP

Le partial `feedback/error-page` fournit une présentation commune pour les erreurs HTTP `403`, `404`, `500` et `503`. Chaque application peut personnaliser le titre, le descriptif, la référence de suivi et les liens d’action sans dupliquer le HTML :

```php
<?= \Astral\TemplateNova\Template::partial('feedback/error-page', [
    'code' => 404,
    'title' => 'Cette page a déménagé',
    'description' => 'Utilisez le bouton ci-dessous pour revenir à l’accueil.',
    'reference' => $errorReference ?? '',
    'homeUrl' => '/',
    'supportUrl' => '/contact',
]) ?>
```

Le composant échappe les contenus textuels et n’accepte pour les liens que les URL relatives locales (hors `//`) ou les URL `http`/`https`. Il n’affiche jamais de trace ni de message d’exception : les détails techniques doivent rester dans les journaux serveur.

## Tests

```bash
composer install
composer test
```

## Limites du prototype

- Les interactions de menu mobile et de sélecteur de thème doivent être raccordées au JavaScript Nova / à l'application.
- Le provider Astral est un adaptateur optionnel : le rendu des vues peut être utilisé indépendamment du framework.
- Les classes utilisées sont celles repérées dans les bundles Nova 0.7 fournis ; une vérification visuelle dans le Playground/application reste nécessaire.

## Integration notes

- PHP requirement: 8.3 or newer.
- Package namespace: `Astral\\TemplateNova`.
- Register `Astral\\TemplateNova\\TemplateServiceProvider::class` in the application's
  service-provider list after the framework provider is available.
- The provider publishes package layouts into `views/layout/` and partials into
  `views/partials/astral-template-nova/`, copying only files that do not already exist.
- Partial rendering is available through `Astral\\TemplateNova\\Template::partial($name, $data)`.
- Layout files are intended to be copied and customized by the application; they are not
  automatically executed by this package's provider.

## Partials disponibles

- `Template::partial('alert', [...])`
- `Template::partial('breadcrumb', [...])`
- `Template::partial('empty-state', [...])`
- `Template::partial('page-header', [...])`
- `Template::partial('table', [...])`
- `Template::partial('forms/field', [...])`
- `Template::partial('forms/field-error', [...])`
- `Template::partial('feedback/flash', [...])`
- `Template::partial('feedback/error-page', ['code' => 403|404|500|503, ...])`
- `Template::partial('data/pagination', [...])`
- `Template::partial('navigation/sidebar', [...])`
- `Template::partial('navigation/user-menu', [...])`
- `Template::partial('interface/theme-switcher', [...])`
- `Template::partial('interface/icon', ['name' => 'shield', 'size' => 'lg', 'filled' => false])`
- `Template::partial('interface/hero-illustration', [...])`
- `Template::partial('interface/feature-card', ['href' => '/login', 'title' => '…', 'text' => '…', 'icon' => 'shield'])`
- `Template::partial('interface/code-window', ['title' => 'HomeController.php', 'tree' => [...], 'code' => $trustedHighlightedHtml])`

Les valeurs textuelles sont échappées par les partials. Le paramètre `code` de `interface/code-window` est du HTML de coloration syntaxique **fourni par l’application** (contenu de confiance). Les liens générés par les partials passent également par `Template::safeUrl()` : chemins locaux, fragments, paramètres de requête et URL HTTP(S) sont acceptés ; les schémas actifs (`javascript:`) et les URL protocol-relative (`//...`) sont rejetés.
