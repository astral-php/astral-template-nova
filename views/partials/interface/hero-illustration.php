<?php
$src = (string) ($src ?? '/assets/app/illustrations/hero-custom.svg');
if (!str_starts_with($src, '/assets/') || str_contains($src, '..')) { throw new InvalidArgumentException('Invalid hero path'); }
?><img class="nova-hero-illustration" src="<?= htmlspecialchars($src, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars((string) ($alt ?? 'Illustration Astral'), ENT_QUOTES, 'UTF-8') ?>" loading="lazy">
