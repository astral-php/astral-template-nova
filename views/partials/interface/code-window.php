<?php

declare(strict_types=1);

$title = (string) ($title ?? 'snippet.php');
$code = (string) ($code ?? '');
/** @var list<array{label:string,active?:bool,indent?:bool}> $tree */
$tree = is_array($tree ?? null) ? $tree : [];
?>
<div class="nova-code-window"<?= isset($label) ? ' aria-label="' . htmlspecialchars((string) $label, ENT_QUOTES, 'UTF-8') . '"' : '' ?>>
    <div class="nova-code-window__bar">
        <span class="nova-code-window__dot nova-code-window__dot--red" aria-hidden="true"></span>
        <span class="nova-code-window__dot nova-code-window__dot--amber" aria-hidden="true"></span>
        <span class="nova-code-window__dot nova-code-window__dot--green" aria-hidden="true"></span>
        <span class="nova-code-window__title"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></span>
    </div>
    <div class="nova-code-window__body">
        <?php if ($tree !== []): ?>
            <aside class="nova-code-window__tree">
                <?php foreach ($tree as $item): ?>
                    <?php
                    $classes = 'nova-code-window__tree-item';
                    if (!empty($item['indent'])) {
                        $classes .= ' nova-code-window__tree-item--indent';
                    }
                    if (!empty($item['active'])) {
                        $classes .= ' nova-code-window__tree-item--active';
                    }
                    ?>
                    <div class="<?= $classes ?>"><?= htmlspecialchars((string) ($item['label'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                <?php endforeach ?>
            </aside>
        <?php endif ?>
        <pre class="nova-code-window__pre"><?= $code ?></pre>
    </div>
</div>
