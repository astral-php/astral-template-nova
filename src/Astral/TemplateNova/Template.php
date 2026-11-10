<?php

declare(strict_types=1);

namespace Astral\TemplateNova;

/** Resolves package layouts and renders package partials. */
final class Template
{
    public static function root(): string
    {
        return dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'views';
    }

    /** @param array<string, mixed> $data */
    public static function partial(string $name, array $data = []): string
    {
        self::assertSafeName($name);
        $file = self::root() . DIRECTORY_SEPARATOR . 'partials'
            . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $name) . '.php';

        if (!is_file($file)) {
            throw new \RuntimeException("Partial introuvable : {$name}");
        }

        return self::renderFile($file, $data);
    }

    /** Return a safe navigable URL, falling back when its scheme is not allowed. */
    public static function safeUrl(string $url, string $fallback = '#'): string
    {
        $url = trim($url);
        if ($url === '' || preg_match('/[\x00-\x20]/', $url)) {
            return $fallback;
        }

        if (
            str_starts_with($url, '/') && !str_starts_with($url, '//')
            || str_starts_with($url, '#')
            || str_starts_with($url, '?')
        ) {
            return $url;
        }

        if (
            filter_var($url, FILTER_VALIDATE_URL) !== false
            && in_array(strtolower((string) parse_url($url, PHP_URL_SCHEME)), ['http', 'https'], true)
        ) {
            return $url;
        }

        return $fallback;
    }

    public static function layoutPath(string $name): string
    {
        self::assertSafeName($name);
        $file = self::root() . DIRECTORY_SEPARATOR . 'layout' . DIRECTORY_SEPARATOR . $name . '.php';
        if (!is_file($file)) {
            throw new \RuntimeException("Layout introuvable : {$name}");
        }
        return $file;
    }

    /** @param array<string, mixed> $data */
    private static function renderFile(string $file, array $data): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        try {
            require $file;
            return (string) ob_get_clean();
        } catch (\Throwable $exception) {
            ob_end_clean();
            throw $exception;
        }
    }

    private static function assertSafeName(string $name): void
    {
        if (
            $name === ''
            || str_contains($name, '..')
            || str_starts_with($name, '/')
            || str_starts_with($name, '\\')
            || str_contains($name, "\0")
        ) {
            throw new \InvalidArgumentException("Nom de vue invalide : {$name}");
        }
    }
}
