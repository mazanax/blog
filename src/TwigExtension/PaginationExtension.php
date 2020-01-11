<?php
declare(strict_types=1);

namespace App\TwigExtension;

use MZ\Paginator;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PaginationExtension extends AbstractExtension
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('paginate', [$this, 'renderPagination'], ['is_safe' => ['html']]),
            new TwigFunction('pages_info', [$this, 'pagesInfo']),
        ];
    }

    /**
     * @throws LoaderError
     * @throws SyntaxError
     * @throws RuntimeError
     */
    public function renderPagination(
        int $currentPage,
        int $totalElements,
        int $onPage,
        string $position = 'center',
        bool $floated = false
    ): string {
        $paginator = new Paginator(['separator' => '...', 'on_page' => $onPage]);

        return $this->twig->render(
            'admin/_pagination.html.twig',
            [
                'pages' => $paginator->getPages($currentPage, $totalElements),
                'currentPage' => $currentPage,
                'position' => $position,
                'floated' => $floated
            ]
        );
    }

    public function pagesInfo(int $currentPage, int $totalElements, int $onPage): string
    {
        $firstElement = ($currentPage - 1) * $onPage + 1;
        $lastElement = min($onPage * $currentPage, $totalElements);
        $pagesCount = ceil($totalElements / $onPage);
        $suffix = $pagesCount % 10 !== 1 || $pagesCount % 100 === 11 ? 's' : '';

        return sprintf(
            'Showing %d to %d of %d (%d page%s)',
            min($firstElement, $totalElements),
            $lastElement,
            $totalElements,
            $pagesCount,
            $suffix
        );
    }
}
