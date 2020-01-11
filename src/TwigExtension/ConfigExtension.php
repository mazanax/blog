<?php
declare(strict_types=1);

namespace App\TwigExtension;

use App\Repository\Contract\ConfigRepositoryInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ConfigExtension extends AbstractExtension
{
    private static $loadedConfigs = [];
    private $twig;
    private $configRepository;

    public function __construct(Environment $twig, ConfigRepositoryInterface $configRepository)
    {
        $this->twig = $twig;
        $this->configRepository = $configRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_config', [$this, 'getConfig'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @throws LoaderError
     * @throws SyntaxError
     * @throws RuntimeError
     */
    public function getConfig(string $slug, string $default = ''): string
    {
        $config = self::$loadedConfigs[$slug] ?? $this->configRepository->findBySlug($slug);
        self::$loadedConfigs[$slug] = $config;

        return $this->twig->render(
            '_config.html.twig',
            [
                'content' => $config ? $config->getContent() : $default,
            ]
        );
    }
}
