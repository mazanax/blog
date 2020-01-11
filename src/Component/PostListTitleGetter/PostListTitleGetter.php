<?php
declare(strict_types=1);

namespace App\Component\PostListTitleGetter;

use RuntimeException;

class PostListTitleGetter implements PostListTitleGetterInterface
{
    private $strategies;

    public function __construct(PostListTitleStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    public function getTitle(string $chosenStrategy): string
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($chosenStrategy)) {
                return $strategy->getTitle();
            }
        }

        throw new RuntimeException(sprintf('[PostListTitleGetter] Unknown strategy %s', $chosenStrategy));
    }
}
