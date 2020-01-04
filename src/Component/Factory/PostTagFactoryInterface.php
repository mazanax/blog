<?php
declare(strict_types=1);

namespace App\Component\Factory;

use App\Entity\Post;
use App\Entity\PostTag;

interface PostTagFactoryInterface
{
    public function create(Post $post, string $tag): PostTag;
}
