<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Component\Filler\PostFillerInterface;
use App\Component\PostGetter\PostGetterInterface;
use App\Component\PostListTitleGetter\PostListTitleGetterInterface;
use App\Constant\PostStrategy;
use App\Entity\Post;
use App\Form\DTO\PostDTO;
use App\Form\Type\PostType;
use App\Repository\Contract\PostTagRepositoryInterface;
use App\Repository\Contract\TagRepositoryInterface;
use App\Validation\Constraint\Strategy;
use App\Validation\Validator\ValidatorInterface;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * @Route("/posts")
 */
class BlogController extends AbstractController
{
    private $postGetter;

    private $validator;

    private $router;

    private $logger;

    private $onPage;

    public function __construct(
        PostGetterInterface $postGetter,
        ValidatorInterface $validator,
        RouterInterface $router,
        LoggerInterface $logger,
        int $onPage
    ) {
        $this->postGetter = $postGetter;
        $this->validator = $validator;
        $this->router = $router;
        $this->logger = $logger;
        $this->onPage = $onPage;
    }

    /**
     * @Route("/toggle-previews", methods={"POST"}, name="admin_toggle_posts_previews")
     */
    public function togglePreviews(Request $request): RedirectResponse
    {
        $currentValue = (int) $request->cookies->get('posts-hide-previews', 0);
        $newValue = $currentValue ^ 1;
        $targetUrl = $request->headers->get('referrer', $this->router->generate('admin_posts'));

        $response = new RedirectResponse($targetUrl);
        $response->headers->setCookie(Cookie::create('posts-hide-previews', (string) $newValue));

        return $response;
    }

    /**
     * @Route("/", name="admin_posts", defaults={"strategy"="published"}, methods={"GET"})
     * @Route("/{strategy<\w+>}", name="admin_posts_strategy", methods={"GET"})
     */
    public function list(
        PostListTitleGetterInterface $postListTitleGetter,
        Request $request,
        string $strategy
    ): Response {
        $onPage = $this->onPage;
        $page = (int) $request->query->get('page', 1);
        $preResponse = $this->validateStrategy($request, $strategy, __METHOD__);

        if ($preResponse) {
            return $preResponse;
        }

        return $this->render('admin/blog/list.html.twig', [
            'title' => $postListTitleGetter->getTitle($strategy),
            'strategy' => $strategy,
            'posts' => $paginator = $this->postGetter->findAll($strategy, ($page - 1) * $onPage, $onPage),
            'currentPage' => $page,
            'lastPage' => ceil($paginator->count() / $onPage),
            'onPage' => $onPage,
            'hidePreviews' => (bool) $request->cookies->get('posts-hide-previews', 0),
        ]);
    }

    /**
     * @Route("/{strategy<\w+>}/create", name="admin_posts_create", defaults={"id"=null}, methods={"GET", "POST"})
     * @Route("/{strategy<\w+>}/update/{id<\d+>}", name="admin_posts_update", methods={"GET", "POST"})
     */
    public function modify(
        EntityManagerInterface $entityManager,
        PostTagRepositoryInterface $postTagRepository,
        TagRepositoryInterface $tagRepository,
        PostFillerInterface $filler,
        Request $request,
        string $strategy,
        ?int $id
    ): Response {
        $preResponse = $this->validateStrategy($request, $strategy, __METHOD__);

        if ($preResponse) {
            return $preResponse;
        }

        $post = new Post();
        if (null !== $id) {
            $post = $this->postGetter->findById($strategy, $id);
        }
        $postDTO = PostDTO::createFromEntity($post);

        $form = $this->createForm(PostType::class, $postDTO);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render(
                'admin/blog/modify.html.twig',
                [
                    'id' => $id,
                    'title' => $id === null ? 'Creating post' : sprintf('Updating «%s»', $postDTO->title),
                    'strategy' => $strategy,
                    'tags' => $tagRepository->all(),
                    'form' => $form->createView()
                ]
            );
        }

        $filler->fillFromDto($post, $postDTO);
        $postTagRepository->removeByPostExcept($post, $post->getTags()->toArray());
        $entityManager->persist($post);
        $entityManager->flush();

        $action = 'created';

        if ($id !== null) {
            $action = $post->isPublished() ? 'modified' : 'scheduled';
        }

        $this->addFlash('success', sprintf('Post «%s» %s successfully', $post->getTitle(), $action));

        $targetStrategy = $post->isPublished() ? PostStrategy::PUBLISHED : PostStrategy::SCHEDULED;

        return $this->redirectToRoute('admin_posts_strategy', ['strategy' => $targetStrategy]);
    }

    /**
     * @Route("/{strategy<\w+>}/publish/{id<\d+>}", name="admin_posts_publish", methods={"POST"})
     */
    public function publish(
        EntityManagerInterface $entityManager,
        PostFillerInterface $filler,
        Request $request,
        string $strategy,
        int $id
    ): Response {
        $preResponse = $this->validateStrategy($request, $strategy, __METHOD__);

        if ($preResponse) {
            return $preResponse;
        }

        $post = $this->postGetter->findById($strategy, $id);

        if (null === $post) {
            throw $this->createNotFoundException();
        }

        $post = $this->postGetter->findById($strategy, $id);
        $postDTO = PostDTO::createFromEntity($post);
        $postDTO->publishedAt = new DateTimeImmutable();

        $filler->fillFromDto($post, $postDTO);
        $entityManager->persist($post);
        $entityManager->flush();

        $this->addFlash('success', sprintf('Post «%s» published successfully', $post->getTitle()));

        return $this->redirectToRoute('admin_posts_strategy', ['strategy' => PostStrategy::PUBLISHED]);
    }

    /**
     * @Route("/{strategy<\w+>}/remove/{id<\d+>}", name="admin_posts_remove", methods={"POST"})
     */
    public function remove(
        EntityManagerInterface $entityManager,
        Request $request,
        string $strategy,
        int $id
    ): Response {
        $preResponse = $this->validateStrategy($request, $strategy, __METHOD__);

        if ($preResponse) {
            return $preResponse;
        }

        $post = $this->postGetter->findById($strategy, $id);

        if (null === $post) {
            throw $this->createNotFoundException();
        }

        $entityManager->remove($post);
        $entityManager->flush();

        $this->addFlash('success', sprintf('Post «%s» removed successfully', $post->getTitle()));

        return $this->redirectToRoute('admin_posts_strategy', ['strategy' => $strategy]);
    }

    private function validateStrategy(Request $request, string $strategy, string $method): ?Response
    {
        $errors = $this->validator->validate($strategy, [new Strategy()]);

        if (count($errors)) {
            $this->logger->error(sprintf('[AdminController::%s] Invalid strategy provided', $method), $errors);
            $targetUrl = $request->headers->get('referer', $this->router->generate('admin_posts'));

            return $this->redirect($targetUrl);
        }

        return null;
    }
}
