<?php
declare(strict_types=1);

namespace App\Component\FileUploader\FileStorage;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class LocalStorage implements FileStorageInterface
{
    private $uploadsDir;

    private $publicPath;

    public function __construct(string $uploadsDir, string $publicPath)
    {
        $this->uploadsDir = $uploadsDir;
        $this->publicPath = $publicPath;
    }

    public function store(UploadedFile $file, string $name): string
    {
        $file->move($this->uploadsDir, $name);

        return $this->uploadsDir . DIRECTORY_SEPARATOR . $name;
    }

    public function getPublicUrl(string $path): string
    {
        return str_replace($this->uploadsDir, $this->publicPath, $path);
    }
}
