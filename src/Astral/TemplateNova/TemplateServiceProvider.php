<?php

declare(strict_types=1);

namespace Astral\TemplateNova;

use Core\Container;
use Core\ServiceProviderInterface;
use Core\View;

/** Publishes Nova views without overwriting application customizations. */
final class TemplateServiceProvider implements ServiceProviderInterface
{
    /** @param array<string, mixed> $appConfig @param array<string, mixed> $dbConfig */
    public function register(Container $container, array $appConfig, array $dbConfig): void
    {
        if (defined('BASE_PATH')) {
            self::publish((string) BASE_PATH);
        }

        /** @var View $view */
        $view = $container->make(View::class);
        $view->share('templateNova', Template::class);
        $view->share('astralTemplateNova', Template::class);
    }

    /** Copy package layouts and partials only when the destination is absent. */
    public static function publish(string $projectRoot): void
    {
        $packageViews = Template::root();
        $appViews = rtrim($projectRoot, '/\\') . DIRECTORY_SEPARATOR . 'views';

        self::copyIfMissing(
            $packageViews . DIRECTORY_SEPARATOR . 'layout',
            $appViews . DIRECTORY_SEPARATOR . 'layout',
            ['admin.php', 'marketing.php', 'auth.php'],
        );

        self::copyIfMissing(
            $packageViews . DIRECTORY_SEPARATOR . 'partials',
            $appViews . DIRECTORY_SEPARATOR . 'partials' . DIRECTORY_SEPARATOR . 'astral-template-nova',
            [
                'alert.php', 'breadcrumb.php', 'empty-state.php', 'page-header.php', 'table.php',
                'navigation/sidebar.php', 'navigation/user-menu.php',
                'interface/theme-switcher.php',
                'feedback/flash.php', 'feedback/error-page.php', 'forms/field-error.php', 'forms/field.php',
                'data/pagination.php',
            ],
        );
    }

    /** @param list<string> $files */
    private static function copyIfMissing(string $fromDir, string $toDir, array $files): void
    {
        foreach ($files as $file) {
            $relative = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $file);
            $source = $fromDir . DIRECTORY_SEPARATOR . $relative;
            $destination = $toDir . DIRECTORY_SEPARATOR . $relative;

            if (!is_file($source) || is_file($destination)) {
                continue;
            }

            $directory = dirname($destination);
            if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
                throw new \RuntimeException("Impossible de créer le répertoire : {$directory}");
            }

            if (!copy($source, $destination)) {
                throw new \RuntimeException("Impossible de publier le fichier : {$destination}");
            }
        }
    }
}
