<?php
declare(strict_types=1);

namespace App\Repository;

class PostRepository
{
    public function get(string $post): array
    {
        return $this->findPublishedPosts(1)[0];
    }

    public function findPublishedPosts(int $limit, int $fromId = 0): array
    {
        return [
            [
                'id' => bin2hex(random_bytes(10)),
                'title' => bin2hex(random_bytes(32)),
                'preview' => bin2hex(random_bytes(1024)),
                'text' => bin2hex(random_bytes(4096)),
                'url' => bin2hex(random_bytes(14)),
                'published_at' => time(),
                'tags' => ['first', 'second']
            ],
            [
                'id' => bin2hex(random_bytes(10)),
                'title' => bin2hex(random_bytes(32)),
                'preview' => bin2hex(random_bytes(1024)),
                'text' => bin2hex(random_bytes(4096)),
                'url' => bin2hex(random_bytes(14)),
                'published_at' => time(),
                'tags' => [],
            ]
        ];
    }
}
