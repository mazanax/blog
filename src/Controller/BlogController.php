<?php
declare(strict_types=1);

namespace App\Controller;

use App\Component\PostGetter\PostGetterInterface;
use App\Constant\PostStrategy;
use App\Repository\Contract\PostRepositoryInterface;
use App\Repository\Contract\TagRepositoryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @var int
     */
    private $onPage;

    /**
     * @param int $onPage
     */
    public function __construct(int $onPage)
    {
        $this->onPage = $onPage;
    }

    /**
     * @Route("/", methods={"GET"}, name="blog_list")
     * @Route("/page-{page<\d+>}", methods={"GET"}, name="blog_paginated_list")
     *
     * @param PostRepositoryInterface $postRepository
     * @param int|null                $page
     *
     * @return Response
     */
    public function list(PostRepositoryInterface $postRepository, ?int $page = 1): Response
    {
        $onPage = $this->onPage;
        $posts = $postRepository->createQueryBuilderForPublishedPosts(($page - 1) * $onPage, $onPage);

        return $this->render(
            'blog/list.html.twig',
            [
                'posts' => $paginator = new Paginator($posts),
                'currentPage' => $page,
                'lastPage' => ceil($paginator->count() / $onPage)
            ]
        );
    }

    /**
     * @Route("/tag/{tag}", methods={"GET"}, name="blog_tag_list")
     * @Route("/tag/{tag}/page-{page<\d+>}", methods={"GET"}, name="blog_tag_paginated_list")
     *
     * @param PostGetterInterface    $postGetter
     * @param TagRepositoryInterface $tagRepository
     * @param string                 $tag
     * @param int|null               $page
     *
     * @return Response
     */
    public function tagList(
        PostGetterInterface $postGetter,
        TagRepositoryInterface $tagRepository,
        string $tag,
        ?int $page = 1
    ): Response {
        $onPage = $this->onPage;
        $tagEntity = $tagRepository->findByName($tag);

        if (null === $tagEntity) {
            throw $this->createNotFoundException();
        }

        $posts = $postGetter->findByTags(PostStrategy::PUBLISHED, [$tag], ($page - 1) * $onPage, $onPage);

        return $this->render(
            'blog/tag_list.html.twig',
            [
                'tag' => $tagEntity,
                'posts' => $posts,
                'currentPage' => $page,
                'lastPage' => ceil($posts->count() / $onPage),
                'onPage' => $onPage,
            ]
        );
    }

    /**
     * @Route("/{id<\d+>}-{url<[-_a-zA-Z0-9]+>}", methods={"GET"}, name="blog_post")
     *
     * @param PostGetterInterface $postGetter
     * @param int                 $id
     *
     * @return Response
     */
    public function view(PostGetterInterface $postGetter, int $id): Response
    {
        $post = $postGetter->findById(PostStrategy::PUBLISHED, $id);

        if (null === $post) {
            throw $this->createNotFoundException();
        }

        return $this->render(
            'blog/view.html.twig',
            [
                'post' => $post
            ]
        );
    }
}
