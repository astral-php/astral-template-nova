# Changelog

## 1.0.0 — Nova Design stable (2026-11-08 / 2026-11-10)

Aligné sur **`astral-nova-css@1.0.0`** et **`astral-nova-icons@1.0.0`**.

- Layouts `marketing` / `admin` / `auth` branchés shell Nova.
- Partials shell : `navigation/mobile-toggle`, `content/steps`, icon API (`size` / `filled`).
- Contrat partiels formulaires, flash, pagination, empty-state, page-header.
- Consommé à 100 % par Astral Starter Nova.
- `require` Composer : `astral-nova-css` + `astral-nova-icons` en `^1.0` (plus en `suggest`).

## 0.4.0-dev — shell partials (DEV-5)

- Partials `navigation/mobile-toggle` et `content/steps`.
- Layouts `marketing` / `admin` branchés sur `nova-header` et shell off-canvas (`data-nova-shell`).
- Aligné sur `astral-nova-css` `0.9.2-dev`.

## 0.3.1-dev — icon API (DEV-4 parallel)

- Partial `interface/icon` : `size` (`sm|md|lg|xl`) et `filled` (symbole `-fill` + classe `.nova-icon--filled`).
- Aligné sur `astral-nova-icons` `0.2.0-dev`.

## 0.3.0-dev — DEV-3 marketing partials

- Cible Nova CSS `0.9.0-dev` (motifs marketing + code-window).
- Ajout des partials `interface/feature-card` et `interface/code-window`.
- Tests d’échappement / `safeUrl` pour les nouvelles surfaces.
- Documentation README alignée sur Nova 0.9 et Template 0.3.

## 0.2.0-dev

- Partials `interface/icon` et `interface/hero-illustration`.
- Compatibilité Nova CSS 0.8 / icônes optionnelles.

## 0.1.0-dev
- Prototype initial de layouts Nova admin, marketing et auth.
- Reprise du mécanisme de publication sans écrasement.
- Partials Nova de base et premiers tests PHPUnit.
- Configuration des assets Nova personnalisable par variables de vue.

## 0.1.0-dev — integration hardening

- Corrected Composer PSR-4 metadata.
- Hardened partial/layout name validation and missing-layout reporting.
- Kept publication idempotent and non-destructive for application overrides.
- Fixed the admin layout's fully-qualified partial renderer reference.
- Expanded tests for nested partials, escaping, and safe publication.

## 0.1.0-dev — compléments d'intégration

- Ajout des partials réutilisables de champ de formulaire, erreur de champ, messages flash et pagination.
- Publication des nouveaux partials dans un sous-dossier dédié, sans écrasement des personnalisations.
- Renforcement des tests d'échappement HTML et du contrat CSS Nova.

## 0.1.0-dev — sécurisation et pages d'erreur

- Ajout du partial commun `feedback/error-page` pour les réponses 403, 404, 500 et 503, avec personnalisation des textes, liens d'action et référence de suivi.
- Ajout d'une politique centrale `Template::safeUrl()` et application aux liens des partials de navigation, fil d'Ariane, pagination et actions.
- Rejet des schémas non autorisés (`javascript:` notamment) et des URL protocol-relative (`//...`) pour limiter les risques XSS via les liens.
- Ajout de tests de non-régression pour les pages d'erreur et la validation des URL.
