<?php
declare(strict_types=1);

namespace App\Component\FileUploader;

use App\Component\Factory\FileFactoryInterface;
use App\Component\FileUploader\FileStorage\FileStorageInterface;
use App\Constant\MimeTypeExtensions;
use App\Entity\File;
use App\Repository\Contract\FileRepositoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader implements FileUploaderInterface
{
    /**
     * @var FileStorageInterface
     */
    private $storage;

    /**
     * @var FileFactoryInterface
     */
    private $factory;

    /**
     * @var FileRepositoryInterface
     */
    private $repository;

    /**
     * @var array
     */
    private $allowedMimeTypes;

    /**
     * @param FileStorageInterface    $storage
     * @param FileFactoryInterface    $factory
     * @param FileRepositoryInterface $repository
     * @param array                   $allowedMimeTypes
     */
    public function __construct(
        FileStorageInterface $storage,
        FileFactoryInterface $factory,
        FileRepositoryInterface $repository,
        array $allowedMimeTypes
    ) {
        $this->storage = $storage;
        $this->factory = $factory;
        $this->repository = $repository;
        $this->allowedMimeTypes = $allowedMimeTypes;
    }

    /**
     * @param UploadedFile $file
     *
     * @return bool
     */
    public function isCanUpload(UploadedFile $file): bool
    {
        return in_array($file->getMimeType(), $this->allowedMimeTypes, true);
    }

    /**
     * @param UploadedFile $file
     *
     * @return File
     */
    public function upload(UploadedFile $file): File
    {
        $name = $this->generateFileName($file->getMimeType());
        $path = $this->storage->store($file, $name);
        $publicPath = $this->storage->getPublicUrl($path);

        $fileEntity = $this->factory->create($name, $path, $publicPath);
        $this->repository->persistAndFlush($fileEntity);

        return $fileEntity;
    }

    /**
     * @param string $mimeType
     *
     * @return string
     */
    private function generateFileName(string $mimeType): string
    {
        $extension = MimeTypeExtensions::MAP[$mimeType];

        return uniqid('upload_', true) . '.' . $extension;
    }
}
