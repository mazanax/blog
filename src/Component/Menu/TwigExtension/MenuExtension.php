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
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var ItemRepositoryInterface
     */
    private $repository;

    /**
     * @param Environment             $twig
     * @param ItemRepositoryInterface $repository
     */
    public function __construct(Environment $twig, ItemRepositoryInterface $repository)
    {
        $this->twig = $twig;
        $this->repository = $repository;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('site_menu', [$this, 'renderSiteMenu'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @return string
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
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
