<?php
declare(strict_types=1);

namespace App\Constant;

interface MimeTypeExtensions
{
    public const MAP = [
        'image/jpeg' => 'jpg',
        'image/gif' => 'gif',
        'image/svg+xml' => 'svg',
        'image/webp' => 'webp',
        'image/png' => 'png',
        'image/apng' => 'apng'
    ];
}
