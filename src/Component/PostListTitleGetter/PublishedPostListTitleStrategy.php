<?php
declare(strict_types=1);

namespace App\Component\PostListTitleGetter;

use App\Constant\PostStrategy;

class PublishedPostListTitleStrategy implements PostListTitleStrategyInterface
{
    public function supports(string $strategy): bool
    {
        return $strategy === PostStrategy::PUBLISHED;
    }

    public function getTitle(): string
    {
        return 'Published Posts';
    }
}
