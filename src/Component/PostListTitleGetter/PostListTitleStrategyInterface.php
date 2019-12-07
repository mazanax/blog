<?php
declare(strict_types=1);

namespace App\Component\PostListTitleGetter;

interface PostListTitleStrategyInterface
{
    public function supports(string $strategy): bool;

    public function getTitle(): string;
}
