<?php
declare(strict_types=1);

namespace App\Component\FileUploader;

use App\Entity\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileUploaderInterface
{
    public function isCanUpload(UploadedFile $file): bool;

    public function upload(UploadedFile $file): File;
}
