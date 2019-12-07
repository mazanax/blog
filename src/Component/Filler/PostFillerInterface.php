<?php
declare(strict_types=1);

namespace App\Component\Filler;

use App\Entity\Post;
use App\Form\DTO\PostDTO;

interface PostFillerInterface
{
    public function fillFromDto(Post $post, PostDTO $dto): void;
}
