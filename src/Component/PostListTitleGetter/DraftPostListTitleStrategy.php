<?php
declare(strict_types=1);

namespace App\Component\PostListTitleGetter;

use App\Constant\PostStrategy;

class DraftPostListTitleStrategy implements PostListTitleStrategyInterface
{
    public function supports(string $strategy): bool
    {
        return $strategy === PostStrategy::DRAFTS;
    }

    public function getTitle(): string
    {
        return 'Drafts';
    }
}
