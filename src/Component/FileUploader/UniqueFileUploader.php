<?php
declare(strict_types=1);

namespace App\Component\FileUploader;

use App\Entity\File;
use App\Entity\FileHash;
use App\Repository\Contract\FileHashRepositoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UniqueFileUploader implements FileUploaderInterface
{
    /**
     * @var FileHashRepositoryInterface
     */
    private $fileHashRepository;

    /**
     * @var FileUploader
     */
    private $fileUploader;

    /**
     * @param FileHashRepositoryInterface $fileHashRepository
     * @param FileUploader                $fileUploader
     */
    public function __construct(
        FileHashRepositoryInterface $fileHashRepository,
        FileUploader $fileUploader
    ) {
        $this->fileHashRepository = $fileHashRepository;
        $this->fileUploader = $fileUploader;
    }

    /**
     * @param UploadedFile $file
     *
     * @return bool
     */
    public function isCanUpload(UploadedFile $file): bool
    {
        return $this->fileUploader->isCanUpload($file);
    }

    /**
     * @param UploadedFile $file
     *
     * @return File
     */
    public function upload(UploadedFile $file): File
    {
        $hash = $this->calculateHash($file);

        if (($fileHash = $this->fileHashRepository->findByHash($hash)) !== null) {
            return $fileHash->getFile();
        }

        $fileEntity = $this->fileUploader->upload($file);
        $fileHash = new FileHash($fileEntity, $hash);

        $this->fileHashRepository->persistAndFlush($fileHash);

        return $fileEntity;
    }

    /**
     * @param UploadedFile $file
     *
     * @return string
     */
    private function calculateHash(UploadedFile $file): string
    {
        return md5_file($file->getPathname());
    }
}
