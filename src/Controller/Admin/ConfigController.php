<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Config;
use App\Form\DTO\ConfigDTO;
use App\Form\Type\ConfigType;
use App\Repository\Contract\ConfigRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/config")
 */
class ConfigController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="admin_config", methods={"GET"})
     */
    public function list(ConfigRepositoryInterface $configRepository): Response
    {
        return $this->render('admin/config/list.html.twig', [
            'items' => $configRepository->all(),
        ]);
    }

    /**
     * @Route("/create/", name="admin_config_create", defaults={"id"=null}, methods={"GET", "POST"})
     * @Route("/update/{id<[-a-zA-Z0-9]+>}", name="admin_config_update", methods={"GET", "POST"})
     */
    public function modify(Request $request, ?Config $config): Response
    {
        if (null === $config) {
            $config = new Config();
        }

        $id = $config->getId();

        $configDTO = ConfigDTO::createFromEntity($config);
        $form = $this->createForm(ConfigType::class, $configDTO);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render(
                'admin/config/modify.html.twig',
                [
                    'id' => $id,
                    'title' => $id === null ? 'Creating config' : sprintf('Updating «%s»', $configDTO->name),
                    'form' => $form->createView()
                ]
            );
        }

        $config->fillFromDto($configDTO);
        $this->entityManager->persist($config);
        $this->entityManager->flush();

        $this->addFlash(
            'success',
            sprintf(
                'Config Item «%s» %s successfully',
                $config->getName(),
                $id === null ? 'created' : 'modified'
            )
        );

        return $this->redirectToRoute('admin_config');
    }

    /**
     * @Route("/remove/{id<[-a-zA-Z0-9]+>}", name="admin_config_remove", methods={"POST"})
     */
    public function remove(Config $config): Response
    {
        $this->entityManager->remove($config);
        $this->entityManager->flush();

        $this->addFlash('success', sprintf('Config item «%s» removed successfully', $config->getName()));

        return $this->redirectToRoute('admin_config');
    }
}
