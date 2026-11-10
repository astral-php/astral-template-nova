<?php

declare(strict_types=1);

namespace Astral\TemplateNova\Tests;

use Astral\TemplateNova\Template;
use Astral\TemplateNova\TemplateServiceProvider;
use PHPUnit\Framework\TestCase;

final class TemplateTest extends TestCase
{
    public function testRootContainsAllLayouts(): void
    {
        foreach (['admin', 'marketing', 'auth'] as $layout) {
            $this->assertFileExists(Template::layoutPath($layout));
        }
    }

    public function testAlertPartialEscapesContent(): void
    {
        $html = Template::partial('alert', ['message' => '<script>x</script>', 'type' => 'success']);
        $this->assertStringContainsString('&lt;script&gt;', $html);
        $this->assertStringContainsString('nova-alert-success', $html);
    }

    public function testTablePartialRendersRowsAndEscapesCells(): void
    {
        $html = Template::partial('table', [
            'headers' => ['Nom'],
            'rows' => [['<Alice>']],
        ]);
        $this->assertStringContainsString('&lt;Alice&gt;', $html);
        $this->assertStringContainsString('nova-table', $html);
    }

    public function testNestedPartialCanBeRendered(): void
    {
        $html = Template::partial('navigation/user-menu', ['userName' => 'Alice']);
        $this->assertStringContainsString('Alice', $html);
    }

    public function testFormFieldEscapesValuesAndDisplaysAccessibleError(): void
    {
        $html = Template::partial('forms/field', [
            'name' => 'email',
            'label' => 'Adresse e-mail',
            'value' => '" onfocus="alert(1)',
            'error' => '<script>bad</script>',
            'required' => true,
        ]);
        $this->assertStringContainsString('aria-invalid="true"', $html);
        $this->assertStringContainsString('&lt;script&gt;bad&lt;/script&gt;', $html);
        $this->assertStringNotContainsString('<script>bad</script>', $html);
    }

    public function testFlashPartialEscapesMessages(): void
    {
        $html = Template::partial('feedback/flash', ['messages' => ['error' => '<b>Erreur</b>']]);
        $this->assertStringContainsString('&lt;b&gt;Erreur&lt;/b&gt;', $html);
        $this->assertStringContainsString('role="alert"', $html);
    }

    public function testPaginationUsesNovaCssContractAndEscapesUrl(): void
    {
        $html = Template::partial('data/pagination', [
            'currentPage' => 2,
            'totalPages' => 4,
            'baseUrl' => '/users?page=',
        ]);
        $this->assertStringContainsString('nova-pagination-list', $html);
        $this->assertStringContainsString('aria-current="page"', $html);
        $this->assertStringContainsString('rel="next"', $html);
    }

    public function testErrorPageSupportsAllStandardHttpCodes(): void
    {
        foreach ([403, 404, 500, 503] as $code) {
            $html = Template::partial('feedback/error-page', ['code' => $code]);
            $this->assertStringContainsString('nova-error-page--' . $code, $html);
            $this->assertStringContainsString('aria-labelledby="nova-error-title"', $html);
            $this->assertStringContainsString('href="/"', $html);
        }
    }

    public function testErrorPageEscapesCustomizationAndReference(): void
    {
        $html = Template::partial('feedback/error-page', [
            'code' => 500,
            'title' => '<script>alert(1)</script>',
            'description' => '<img src=x onerror=alert(1)>',
            'reference' => '<REF-123>',
            'homeUrl' => 'javascript:alert(1)',
            'supportUrl' => 'https://support.example.test/contact',
        ]);

        $this->assertStringContainsString('&lt;script&gt;', $html);
        $this->assertStringContainsString('&lt;img', $html);
        $this->assertStringContainsString('&lt;REF-123&gt;', $html);
        $this->assertStringNotContainsString('href="javascript:', $html);
        $this->assertStringContainsString('https://support.example.test/contact', $html);
    }

    public function testUnknownErrorCodeFallsBackToInternalError(): void
    {
        $html = Template::partial('feedback/error-page', ['code' => 418]);
        $this->assertStringContainsString('nova-error-page--500', $html);
        $this->assertStringContainsString('Erreur interne', $html);
    }

    public function testSafeUrlRejectsUnsafeSchemesAndProtocolRelativeUrls(): void
    {
        $this->assertSame('/', Template::safeUrl('javascript:alert(1)', '/'));
        $this->assertSame('#', Template::safeUrl('//evil.example/path'));
        $this->assertSame('/users?page=2', Template::safeUrl('/users?page=2'));
        $this->assertSame('https://example.test/path', Template::safeUrl('https://example.test/path'));
    }

    public function testNavigationPartialRejectsJavascriptUrl(): void
    {
        $html = Template::partial('navigation/sidebar', [
            'items' => [['label' => 'Danger', 'href' => 'javascript:alert(1)']],
        ]);
        $this->assertStringNotContainsString('href="javascript:', $html);
        $this->assertStringContainsString('href="#"', $html);
    }

    public function testFeatureCardEscapesTextAndSanitizesHref(): void
    {
        $html = Template::partial('interface/feature-card', [
            'href' => 'javascript:alert(1)',
            'title' => '<b>Auth</b>',
            'text' => '<script>x</script>',
            'icon' => 'shield',
        ]);
        $this->assertStringContainsString('nova-feature-card', $html);
        $this->assertStringContainsString('href="#"', $html);
        $this->assertStringContainsString('&lt;b&gt;Auth&lt;/b&gt;', $html);
        $this->assertStringNotContainsString('<script>x</script>', $html);
        $this->assertStringContainsString('nova-icon-shield', $html);
    }

    public function testIconSupportsSizeAndFilledVariant(): void
    {
        $html = Template::partial('interface/icon', [
            'name' => 'heart',
            'size' => 'lg',
            'filled' => true,
        ]);
        $this->assertStringContainsString('nova-icon nova-icon--lg nova-icon--filled', $html);
        $this->assertStringContainsString('#nova-icon-heart-fill', $html);
    }

    public function testMobileToggleExposesHeaderContract(): void
    {
        $html = Template::partial('navigation/mobile-toggle', [
            'controls' => 'demo-panel',
            'label' => 'Menu',
        ]);
        $this->assertStringContainsString('data-nova-header-toggle', $html);
        $this->assertStringContainsString('aria-controls="demo-panel"', $html);
        $this->assertStringContainsString('nova-icon-menu', $html);
        $this->assertStringContainsString('nova-icon-close', $html);
    }

    public function testStepsPartialEscapesContent(): void
    {
        $html = Template::partial('content/steps', [
            'title' => '<Min>',
            'titleAccent' => '<Accent>',
            'lead' => '<script>x</script>',
            'items' => [
                ['icon' => 'puzzle', 'number' => '1', 'title' => '<T>', 'text' => '<p>bad</p>'],
            ],
        ]);
        $this->assertStringContainsString('nova-steps', $html);
        $this->assertStringContainsString('&lt;Min&gt;', $html);
        $this->assertStringContainsString('&lt;Accent&gt;', $html);
        $this->assertStringContainsString('&lt;script&gt;x&lt;/script&gt;', $html);
        $this->assertStringContainsString('&lt;T&gt;', $html);
        $this->assertStringNotContainsString('<script>x</script>', $html);
        $this->assertStringContainsString('nova-icon-puzzle', $html);
    }

    public function testCodeWindowEscapesTitleAndTreeLabels(): void
    {
        $html = Template::partial('interface/code-window', [
            'title' => '<Home>.php',
            'tree' => [
                ['label' => '<app>', 'indent' => false],
                ['label' => 'index.php', 'indent' => true, 'active' => true],
            ],
            'code' => htmlspecialchars('echo 1;', ENT_QUOTES, 'UTF-8'),
        ]);
        $this->assertStringContainsString('nova-code-window', $html);
        $this->assertStringContainsString('&lt;Home&gt;.php', $html);
        $this->assertStringContainsString('&lt;app&gt;', $html);
        $this->assertStringContainsString('nova-code-window__tree-item--active', $html);
    }

    public function testPartialRejectsTraversal(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Template::partial('../secret');
    }

    public function testLayoutPathRejectsTraversal(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Template::layoutPath('../secret');
    }

    public function testPublishCopiesFilesAndDoesNotOverwriteCustomizations(): void
    {
        $project = sys_get_temp_dir() . '/astral-template-nova-' . bin2hex(random_bytes(5));
        mkdir($project, 0755, true);
        mkdir($project . '/views/layout', 0755, true);
        file_put_contents($project . '/views/layout/admin.php', 'custom-layout');

        try {
            TemplateServiceProvider::publish($project);
            $this->assertSame('custom-layout', file_get_contents($project . '/views/layout/admin.php'));
            $this->assertFileExists($project . '/views/layout/marketing.php');
            $this->assertFileExists($project . '/views/layout/auth.php');
            $this->assertFileExists($project . '/views/partials/astral-template-nova/navigation/sidebar.php');
            $this->assertFileExists($project . '/views/partials/astral-template-nova/feedback/error-page.php');

            TemplateServiceProvider::publish($project);
            $this->assertSame('custom-layout', file_get_contents($project . '/views/layout/admin.php'));
        } finally {
            $this->removeDirectory($project);
        }
    }

    private function removeDirectory(string $directory): void
    {
        if (!is_dir($directory)) {
            return;
        }
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST,
        );
        foreach ($iterator as $item) {
            $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
        }
        rmdir($directory);
    }
}
