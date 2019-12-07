<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Component\Menu\Factory\ItemDTOFactoryInterface;
use App\Component\Menu\Factory\ItemFactoryInterface;
use App\Component\Menu\Form\DTO\OrderedMenuDTO;
use App\Component\Menu\Form\Type\ItemType;
use App\Component\Menu\Form\Type\OrderType;
use App\Component\Menu\ItemFillerInterface;
use App\Component\Menu\MenuSorterInterface;
use App\Component\Menu\Repository\Contract\ItemRepositoryInterface;
use App\Entity\Menu\Folder;
use App\Entity\Menu\Item;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/menu")
 */
class MenuController extends AbstractController
{
    /**
     * @var ItemRepositoryInterface
     */
    private $repository;

    /**
     * @param ItemRepositoryInterface $repository
     */
    public function __construct(ItemRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/", name="admin_menu", methods={"GET"})
     *
     * @return Response
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
     *
     * @param ItemDTOFactoryInterface $DTOFactory
     * @param ItemFactoryInterface    $itemFactory
     * @param MenuSorterInterface     $sorter
     * @param ValidatorInterface      $validator
     * @param Request                 $request
     * @param Item|null               $parent
     *
     * @return Response
     */
    public function create(
        ItemDTOFactoryInterface $DTOFactory,
        ItemFactoryInterface $itemFactory,
        MenuSorterInterface $sorter,
        ValidatorInterface $validator,
        Request $request,
        ?Item $parent = null
    ): Response {
        $modal = $request->isXmlHttpRequest() && $request->getMethod() === Request::METHOD_GET;

        $itemDTO = $DTOFactory->createFromEntity(new Folder());
        $itemDTO->parent = $parent;

        $form = $this->createForm(ItemType::class, $itemDTO->toArray());
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

        $data = $form->getData();
        $itemDTO = $DTOFactory->createFromArray($data);
        $errors = $validator->validate($itemDTO);

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                /** @var ConstraintViolationInterface $error */
                $form->get($error->getPropertyPath())->addError(new FormError($error->getMessage()));
            }

            return $this->render(
                $modal ? 'admin/menu/modal.html.twig' : 'admin/menu/modify.html.twig',
                [
                    'id' => null,
                    'title' => 'Creating menu item',
                    'form' => $form->createView(),
                ]
            );
        }

        $item = $itemFactory->createFromDTO($itemDTO);
        $items = array_merge($this->repository->all(), [$item]);

        $sorter->sort($items);

        foreach ($items as $updatedItem) {
            $this->repository->persist($updatedItem);
        }
        $this->repository->flush();

        $this->addFlash('success', sprintf('%s created successfully', Item::TYPES[$item->getType()]));

        return $this->redirectToRoute('admin_menu');
    }

    /**
     * @Route("/remove/{id<[-a-zA-Z0-9]+>}", name="admin_menu_remove", methods={"POST"})
     *
     * @param EntityManagerInterface $entityManager
     * @param Item                   $item
     *
     * @return Response
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
     *
     * @param ItemFillerInterface $filler
     * @param MenuSorterInterface $sorter
     * @param Request             $request
     *
     * @return Response
     */
    public function reorder(
        ItemFillerInterface $filler,
        MenuSorterInterface $sorter,
        Request $request
    ): Response {
        if (!$request->isXmlHttpRequest()) {
            return new Response('', Response::HTTP_BAD_REQUEST);
        }
        $dto = new OrderedMenuDTO();
        $form = $this->createForm(OrderType::class, $dto);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $errors = [];

            foreach ($form->getErrors(true) as $error) {
                $errors[$error->getCause()->getPropertyPath()][] = $error->getMessage();
            }

            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        $itemsData = array_reduce(
            $dto->items,
            static function (array $acc, array $data) {
                $acc[$data['id']] = $data;

                return $acc;
            },
            []
        );
        $items = $this->repository->all();

        foreach ($items as $item) {
            $filler->fillFromArray($item, $itemsData[$item->getId()]);
        }

        $sorter->sort($items);

        foreach ($items as $item) {
            $this->repository->persist($item);
        }

        $this->repository->flush();

        return new Response();
    }
}
