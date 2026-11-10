<?php

declare(strict_types=1);

$name = (string) ($name ?? 'check');
if (!preg_match('/^[a-z][a-z0-9-]*$/D', $name)) {
    throw new InvalidArgumentException('Invalid Nova icon');
}

$size = strtolower((string) ($size ?? 'md'));
if (!in_array($size, ['sm', 'md', 'lg', 'xl'], true)) {
    $size = 'md';
}

$filled = filter_var($filled ?? false, FILTER_VALIDATE_BOOLEAN);
$symbol = $name;
if ($filled && !str_ends_with($name, '-fill')) {
    $symbol = $name . '-fill';
}

$base = (string) ($novaIconsBase ?? '/assets/nova/icons/icons.svg');
$label = trim((string) ($label ?? ''));
$extraClass = trim((string) ($class ?? ''));
$classes = trim('nova-icon nova-icon--' . $size . ($filled ? ' nova-icon--filled' : '') . ($extraClass !== '' ? ' ' . $extraClass : ''));
$href = $base . '#nova-icon-' . $symbol;
?>
<svg class="<?= htmlspecialchars($classes, ENT_QUOTES, 'UTF-8') ?>"<?= $label === '' ? ' aria-hidden="true"' : ' role="img" aria-label="' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '"' ?>><use href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>"></use></svg>
