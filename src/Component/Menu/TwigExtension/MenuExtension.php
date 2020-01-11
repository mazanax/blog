<?php
declare(strict_types=1);

namespace App\Component\Menu\TwigExtension;

use App\Component\Menu\Repository\Contract\ItemRepositoryInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MenuExtension extends AbstractExtension
{
    private $twig;

    private $repository;

    public function __construct(Environment $twig, ItemRepositoryInterface $repository)
    {
        $this->twig = $twig;
        $this->repository = $repository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('site_menu', [$this, 'renderSiteMenu'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @throws LoaderError
     * @throws SyntaxError
     * @throws RuntimeError
     */
    public function renderSiteMenu(): string
    {
        return $this->twig->render(
            '_menu.html.twig',
            [
                'items' => $this->repository->getTopItems(),
            ]
        );
    }
}
