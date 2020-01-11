<?php
declare(strict_types=1);

namespace App\Component\PostGetter;

use App\Entity\Post;
use Doctrine\ORM\Tools\Pagination\Paginator;
use LogicException;

class PostGetter implements PostGetterInterface
{
    private $strategies;

    public function __construct(PostGetterStrategyInterface... $strategies)
    {
        $this->strategies = $strategies;
    }

    public function findById(string $chosenStrategy, int $id): ?Post
    {
        foreach ($this->strategies as $strategy) {
            if (!$strategy->supports($chosenStrategy)) {
                continue;
            }

            return $strategy->findById($id);
        }

        throw new LogicException(sprintf('[PostGetter] Unknown strategy %s', $chosenStrategy));
    }

    public function findAll(string $chosenStrategy, int $offset, int $limit): Paginator
    {
        foreach ($this->strategies as $strategy) {
            if (!$strategy->supports($chosenStrategy)) {
                continue;
            }

            return $strategy->findAll($offset, $limit);
        }

        throw new LogicException(sprintf('[PostGetter] Unknown strategy %s', $chosenStrategy));
    }

    public function findByTags(string $chosenStrategy, array $tags, int $offset, int $limit): Paginator
    {
        foreach ($this->strategies as $strategy) {
            if (!$strategy->supports($chosenStrategy)) {
                continue;
            }

            return $strategy->findByTags($tags, $offset, $limit);
        }

        throw new LogicException(sprintf('[PostGetter] Unknown strategy %s', $chosenStrategy));
    }
}
