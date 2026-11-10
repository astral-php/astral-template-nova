<?php

declare(strict_types=1);

use Astral\TemplateNova\Template;

$controls = (string) ($controls ?? 'nova-nav-panel');
$label = (string) ($label ?? 'Ouvrir le menu');
$expanded = !empty($expanded);
$class = trim('nova-nav-toggle ' . (string) ($class ?? ''));
?>
<button
    class="<?= htmlspecialchars($class, ENT_QUOTES, 'UTF-8') ?>"
    type="button"
    data-nova-header-toggle
    aria-controls="<?= htmlspecialchars($controls, ENT_QUOTES, 'UTF-8') ?>"
    aria-expanded="<?= $expanded ? 'true' : 'false' ?>"
    aria-label="<?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>"
>
    <span class="nova-nav-toggle__open"><?= Template::partial('interface/icon', ['name' => 'menu']) ?></span>
    <span class="nova-nav-toggle__close"><?= Template::partial('interface/icon', ['name' => 'close']) ?></span>
</button>
