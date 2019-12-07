<?php
declare(strict_types=1);

namespace App\Repository\Contract;

use App\Entity\Post;
use App\Entity\PostTag;

interface PostTagRepositoryInterface
{
    public function findByPostAndName(Post $post, string $name): ?PostTag;

    public function removeByPostExcept(Post $post, array $keep): void;
}
