<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Component\Menu\Factory\ItemDTOFactoryInterface;
use App\Component\Menu\Factory\ItemFactoryInterface;
use App\Component\Menu\Form\DTO\SortedMenu;
use App\Component\Menu\Form\Type\ItemType;
use App\Component\Menu\Form\Type\SortedMenuType;
use App\Component\Menu\ModificationApplierInterface;
use App\Component\Menu\Repository\Contract\ItemRepositoryInterface;
use App\Component\Menu\SorterInterface;
use App\Entity\Menu\Item;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/menu")
 */
class MenuController extends AbstractController
{
    private $repository;

    public function __construct(ItemRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/", name="admin_menu", methods={"GET"})
     */
    public function view(): Response
    {
        return $this->render(
            'admin/menu/view.html.twig',
            [
                'items' => $this->repository->getTopItems(),
            ]
        );
    }

    /**
     * @Route("/create/", name="admin_menu_create", methods={"GET", "POST"})
     * @Route("/create/{id<[-a-zA-Z0-9]+>}", name="admin_menu_create_in", methods={"GET", "POST"})
     */
    public function create(
        ItemDTOFactoryInterface $DTOFactory,
        ItemFactoryInterface $itemFactory,
        SorterInterface $sorter,
        Request $request,
        ?Item $parent = null
    ): Response {
        $modal = $request->isXmlHttpRequest() && $request->getMethod() === Request::METHOD_GET;

        $itemDTO = $DTOFactory->createWithParent($parent);
        $form = $this->createForm(ItemType::class, $itemDTO);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render(
                $modal ? 'admin/menu/modal.html.twig' : 'admin/menu/modify.html.twig',
                [
                    'id' => null,
                    'title' => 'Creating menu item',
                    'form' => $form->createView(),
                ]
            );
        }

        $item = $itemFactory->createByDTO($itemDTO);
        $item->fillFromDto($itemDTO);
        $items = array_merge($this->repository->all(), [$item]);
        $sorter->sort($items);

        $this->repository->batchPersist($items);
        $this->repository->flush();

        $this->addFlash('success', sprintf('%s created successfully', Item::TYPES[$item->getType()]));

        return $this->redirectToRoute('admin_menu');
    }

    /**
     * @Route("/remove/{id<[-a-zA-Z0-9]+>}", name="admin_menu_remove", methods={"POST"})
     */
    public function remove(EntityManagerInterface $entityManager, Item $item): Response
    {
        $entityManager->remove($item);
        $entityManager->flush();

        $this->addFlash('success', sprintf('%s removed successfully', $item->getTitle()));

        return $this->redirectToRoute('admin_menu');
    }

    /**
     * @Route("/reorder", name="menu_reorder", methods={"GET", "POST"})
     */
    public function reorder(
        ModificationApplierInterface $modificationApplier,
        SorterInterface $sorter,
        Request $request
    ): Response {
        if (!$request->isXmlHttpRequest()) {
            return new Response('', Response::HTTP_BAD_REQUEST);
        }
        $sortedMenu = new SortedMenu();
        $form = $this->createForm(SortedMenuType::class, $sortedMenu);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $errors = [];

            foreach ($form->getErrors(true) as $error) {
                $errors[$error->getCause()->getPropertyPath()][] = $error->getMessage();
            }

            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        $items = $this->repository->all();
        $modificationApplier->batchApply($items, $sortedMenu);
        $sorter->sort($items);

        $this->repository->batchPersist($items);
        $this->repository->flush();

        return new Response();
    }
}
