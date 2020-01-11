<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Component\FileUploader\FileUploaderInterface;
use App\Component\PostGetter\PostGetterInterface;
use App\Constant\PostStrategy;
use App\Repository\Contract\PageRepositoryInterface;
use App\Repository\Contract\PostRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommonController extends AbstractController
{
    /**
     * @Route("/", name="admin_index", methods={"GET"})
     */
    public function index(
        PostRepositoryInterface $postRepository,
        PostGetterInterface $postGetter,
        PageRepositoryInterface $pageRepository
    ): Response
    {
        $scheduledCount = $postRepository->countScheduled();
        $publishedCount = $postRepository->countPublished();
        $staticPagesCount = $pageRepository->count();

        $schedules = $postGetter->findAll(PostStrategy::SCHEDULED, 0, 5);
        $recentPosts = $postGetter->findAll(PostStrategy::PUBLISHED, 0, 5);

        return $this->render('admin/dashboard/index.html.twig', [
            'scheduled_count' => $scheduledCount,
            'published_count' => $publishedCount,
            'static_pages_count' => $staticPagesCount,
            'schedules' => $schedules,
            'recent_posts' => $recentPosts,
        ]);
    }

    /**
     * @Route("/upload-image", name="admin_upload_image", methods={"POST"})
     */
    public function uploadImage(FileUploaderInterface $uploader, Request $request): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return new Response('', Response::HTTP_BAD_REQUEST);
        }

        if (!$request->files->has('image') || !($file = $request->files->get('image')) instanceof UploadedFile) {
            return new Response('Unknown error', Response::HTTP_BAD_REQUEST);
        }

        if (!$uploader->isCanUpload($file)) {
            return new Response(
                sprintf('File with mime-type %s is not allowed', $file->getMimeType()),
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse($uploader->upload($file)->getPublicPath(), Response::HTTP_OK);
    }
}
