<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Page;
use App\Form\DTO\PageDTO;
use App\Form\Type\PageType;
use App\Repository\Contract\PageRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pages")
 */
class PageController extends AbstractController
{
    private $entityManager;

    private $onPage;

    public function __construct(EntityManagerInterface $entityManager, int $onPage)
    {
        $this->entityManager = $entityManager;
        $this->onPage = $onPage;
    }

    /**
     * @Route("/", name="admin_pages", methods={"GET"})
     */
    public function list(PageRepositoryInterface $pageRepository, Request $request): Response
    {
        $onPage = $this->onPage;
        $page = (int) $request->query->get('page', 1);

        return $this->render('admin/page/list.html.twig', [
            'pages' => $paginator = $pageRepository->paginate(($page - 1) * $onPage, $onPage),
            'currentPage' => $page,
            'lastPage' => ceil($paginator->count() / $onPage),
            'onPage' => $onPage,
        ]);
    }

    /**
     * @Route("/create", name="admin_pages_create", defaults={"id"=null}, methods={"GET", "POST"})
     * @Route("/update/{id<\d+>}", name="admin_pages_update", methods={"GET", "POST"})
     */
    public function modify(Request $request, ?Page $page): Response
    {
        if (null === $page) {
            $page = new Page();
        }

        $id = $page->getId();

        $pageDTO = PageDTO::createFromEntity($page);
        $form = $this->createForm(PageType::class, $pageDTO);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render(
                'admin/page/modify.html.twig',
                [
                    'id' => $id,
                    'title' => $id === null ? 'Creating page' : sprintf('Updating «%s»', $pageDTO->title),
                    'form' => $form->createView()
                ]
            );
        }

        $page->fillFromDto($pageDTO);
        $this->entityManager->persist($page);
        $this->entityManager->flush();

        $this->addFlash(
            'success',
            sprintf(
                'Page «%s» %s successfully',
                $page->getTitle(),
                $id === null ? 'created' : 'modified'
            )
        );

        return $this->redirectToRoute('admin_pages');
    }

    /**
     * @Route("/remove/{id<\d+>}", name="admin_pages_remove", methods={"POST"})
     */
    public function remove(Page $page): Response
    {
        $this->entityManager->remove($page);
        $this->entityManager->flush();

        $this->addFlash('success', sprintf('Page «%s» removed successfully', $page->getTitle()));

        return $this->redirectToRoute('admin_pages');
    }
}
