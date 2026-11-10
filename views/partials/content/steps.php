<?php

declare(strict_types=1);

use Astral\TemplateNova\Template;

$title = (string) ($title ?? '');
$titleAccent = (string) ($titleAccent ?? '');
$lead = (string) ($lead ?? '');
$items = is_array($items ?? null) ? $items : [];
?>
<section class="nova-steps">
    <?php if ($title !== '' || $titleAccent !== ''): ?>
        <h2 class="nova-steps__title">
            <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>
            <?php if ($titleAccent !== ''): ?>
                <span><?= htmlspecialchars($titleAccent, ENT_QUOTES, 'UTF-8') ?></span>
            <?php endif ?>
        </h2>
    <?php endif ?>
    <?php if ($lead !== ''): ?>
        <p class="nova-steps__lead"><?= htmlspecialchars($lead, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif ?>
    <div class="nova-steps__grid">
        <?php foreach ($items as $index => $item): ?>
            <?php
            $item = is_array($item) ? $item : [];
            $number = (string) ($item['number'] ?? (string) ($index + 1));
            $icon = (string) ($item['icon'] ?? '');
            $itemTitle = (string) ($item['title'] ?? '');
            $text = (string) ($item['text'] ?? '');
            ?>
            <article class="nova-step">
                <div class="nova-step__top">
                    <?php if ($icon !== ''): ?>
                        <span class="nova-step__icon"><?= Template::partial('interface/icon', ['name' => $icon]) ?></span>
                    <?php else: ?>
                        <span class="nova-step__icon" aria-hidden="true"></span>
                    <?php endif ?>
                    <span class="nova-step__number"><?= htmlspecialchars($number, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <?php if ($itemTitle !== ''): ?>
                    <strong><?= htmlspecialchars($itemTitle, ENT_QUOTES, 'UTF-8') ?></strong>
                <?php endif ?>
                <?php if ($text !== ''): ?>
                    <p><?= htmlspecialchars($text, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif ?>
            </article>
        <?php endforeach ?>
    </div>
</section>
