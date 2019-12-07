<?php
declare(strict_types=1);

namespace App\Component\FileUploader\FileStorage;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class LocalStorage implements FileStorageInterface
{
    /**
     * @var string
     */
    private $uploadsDir;

    /**
     * @var string
     */
    private $publicPath;

    /**
     * @param string $uploadsDir
     * @param string $publicPath
     */
    public function __construct(string $uploadsDir, string $publicPath)
    {
        $this->uploadsDir = $uploadsDir;
        $this->publicPath = $publicPath;
    }

    /**
     * @param UploadedFile $file
     * @param string       $name
     *
     * @return string
     */
    public function store(UploadedFile $file, string $name): string
    {
        $file->move($this->uploadsDir, $name);

        return $this->uploadsDir . DIRECTORY_SEPARATOR . $name;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function getPublicUrl(string $path): string
    {
        return str_replace($this->uploadsDir, $this->publicPath, $path);
    }
}
