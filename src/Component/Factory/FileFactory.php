<?php
declare(strict_types=1);

namespace App\Component\Factory;

use App\Entity\File;

class FileFactory implements FileFactoryInterface
{
    /**
     * @param string $name
     * @param string $path
     * @param string $publicPath
     *
     * @return File
     */
    public function create(string $name, string $path, string $publicPath): File
    {
        return (new File())
            ->setName($name)
            ->setPath($path)
            ->setPublicPath($publicPath);
    }
}
