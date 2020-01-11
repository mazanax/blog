<?php
declare(strict_types=1);

namespace App\Controller;

use App\Repository\Contract\PageRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/page")
 */
class PageController extends AbstractController
{
    /**
     * @Route("/{url<[-_a-zA-Z0-9]+>}", methods={"GET"}, name="page_view")
     */
    public function view(PageRepositoryInterface $pageRepository, string $url): Response
    {
        $page = $pageRepository->findByUrl($url);

        if (null === $page) {
            throw $this->createNotFoundException();
        }

        return $this->render('page/view.html.twig', [
            'page' => $page,
        ]);
    }
}
