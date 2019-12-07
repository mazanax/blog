<?php
declare(strict_types=1);

namespace App\Component\PostListTitleGetter;

use App\Constant\PostStrategy;

class ScheduledPostListTitleStrategy implements PostListTitleStrategyInterface
{
    /**
     * @param string $strategy
     *
     * @return bool
     */
    public function supports(string $strategy): bool
    {
        return $strategy === PostStrategy::SCHEDULED;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return 'Scheduled Posts';
    }
}
