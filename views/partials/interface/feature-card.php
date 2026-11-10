<?php

declare(strict_types=1);

use Astral\TemplateNova\Template;

$href = Template::safeUrl((string) ($href ?? '#'));
$title = (string) ($title ?? '');
$text = (string) ($text ?? '');
$icon = (string) ($icon ?? 'layout');
?>
<a class="nova-feature-card" href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>">
    <span class="nova-feature-card__icon"><?= Template::partial('interface/icon', ['name' => $icon]) ?></span>
    <span class="nova-feature-card__body">
        <span class="nova-feature-card__title"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></span>
        <?php if ($text !== ''): ?><p class="nova-feature-card__text"><?= htmlspecialchars($text, ENT_QUOTES, 'UTF-8') ?></p><?php endif ?>
    </span>
    <span class="nova-feature-card__chevron" aria-hidden="true"><?= Template::partial('interface/icon', ['name' => 'chevron-right']) ?></span>
</a>
