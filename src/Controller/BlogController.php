<?php
declare(strict_types=1);

namespace App\Controller;

use App\Repository\PostRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="blog_list")
     * @Route("/page-{page<\d+>}", methods={"GET"}, name="blog_paginated_list")
     *
     * @param PostRepository $postRepository
     * @param int|null       $page
     *
     * @return Response
     */
    public function list(PostRepository $postRepository, ?int $page = 1): Response
    {
        $onPage = $this->getParameter('on_page');
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
     * @Route("/{id<\d+>}-{url<[-a-zA-Z0-9]+>}", methods={"GET"}, name="blog_post")
     *
     * @param PostRepository $postRepository
     * @param int            $id
     *
     * @return Response
     */
    public function view(PostRepository $postRepository, int $id): Response
    {
        $post = $postRepository->find($id);

        return $this->render(
            'blog/view.html.twig',
            [
                'post' => $post
            ]
        );
    }
}
