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
    private $storage;

    private $factory;

    private $repository;

    private $allowedMimeTypes;

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

    public function isCanUpload(UploadedFile $file): bool
    {
        return in_array($file->getMimeType(), $this->allowedMimeTypes, true);
    }

    public function upload(UploadedFile $file): File
    {
        $name = $this->generateFileName($file->getMimeType());
        $path = $this->storage->store($file, $name);
        $publicPath = $this->storage->getPublicUrl($path);

        $fileEntity = $this->factory->create($name, $path, $publicPath);
        $this->repository->persistAndFlush($fileEntity);

        return $fileEntity;
    }

    private function generateFileName(string $mimeType): string
    {
        $extension = MimeTypeExtensions::MAP[$mimeType];

        return uniqid('upload_', true) . '.' . $extension;
    }
}
