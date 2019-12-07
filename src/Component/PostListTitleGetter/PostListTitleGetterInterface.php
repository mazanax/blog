<?php
declare(strict_types=1);

namespace App\Component\PostListTitleGetter;

interface PostListTitleGetterInterface
{
    public function getTitle(string $chosenStrategy): string;
}
