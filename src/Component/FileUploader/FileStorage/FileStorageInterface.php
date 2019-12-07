<?php
declare(strict_types=1);

namespace App\Component\FileUploader\FileStorage;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileStorageInterface
{
    /**
     * @param UploadedFile $file
     * @param string       $name
     *
     * @return string Path to uploaded file
     */
    public function store(UploadedFile $file, string $name): string;

    /**
     * @param string $path
     *
     * @return string
     */
    public function getPublicUrl(string $path): string;
}