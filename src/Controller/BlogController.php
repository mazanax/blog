<?php
declare(strict_types=1);

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="blog_list")
     *
     * @param PostRepository $postRepository
     *
     * @return Response
     */
    public function list(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findPublishedPosts(10);

        return $this->render(
            'blog/list.html.twig',
            [
                'posts' => $posts
            ]
        );
    }

    /**
     * @Route("/{id<\w+>}-{url<\w+>}", methods={"GET"}, name="blog_post")
     *
     * @param PostRepository $postRepository
     * @param string         $id
     *
     * @return Response
     */
    public function view(PostRepository $postRepository, string $id): Response
    {
        $post = $postRepository->get($id);

        return $this->render(
            'blog/view.html.twig',
            [
                'post' => $post
            ]
        );
    }
}
