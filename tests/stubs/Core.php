<?php

declare(strict_types=1);

namespace Core;

/** Minimal stubs so TemplateServiceProvider can load without astral-core. */
interface ServiceProviderInterface
{
    /** @param array<string, mixed> $appConfig @param array<string, mixed> $dbConfig */
    public function register(Container $container, array $appConfig, array $dbConfig): void;
}

final class Container
{
    public function make(string $id): mixed
    {
        return new View();
    }
}

final class View
{
    /** @var array<string, mixed> */
    public array $shared = [];

    public function share(string $key, mixed $value): void
    {
        $this->shared[$key] = $value;
    }
}
