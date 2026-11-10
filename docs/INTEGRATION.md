# Contrat d'intégration Nova

- Astral Template Nova compose les pages et fournit les vues PHP.
- Nova CSS reste propriétaire des tokens, palettes, composants visuels et comportements génériques.
- Le package ne copie pas de CSS de composants et ne dépend pas obligatoirement d'Astral Core.
- Les chemins CSS/JS sont configurables.
- La publication des vues est idempotente et ne remplace pas les personnalisations de l'application.
- La sélection de thème est une donnée de vue ; le stockage de préférence utilisateur reste à la charge de l'application.

## Pages d’erreur HTTP

Le partial `feedback/error-page` est commun aux réponses 403, 404, 500 et 503. Les applications peuvent transmettre `title`, `description`, `reference`, `homeUrl`, `homeLabel`, `supportUrl` et `supportLabel`. Les valeurs par défaut sont définies par code HTTP. Les textes sont échappés ; les liens acceptent uniquement les chemins locaux relatifs et les URL HTTP(S). Ne jamais transmettre de message d’exception ou de trace à cette vue.
