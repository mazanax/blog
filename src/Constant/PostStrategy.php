<?php
declare(strict_types=1);

namespace App\Constant;

interface PostStrategy
{
    public const PUBLISHED = 'published';
    public const SCHEDULED = 'scheduled';
    public const DRAFTS = 'drafts';

    public const ALLOWED_STRATEGIES = [self::PUBLISHED, self::SCHEDULED, self::DRAFTS];
}
