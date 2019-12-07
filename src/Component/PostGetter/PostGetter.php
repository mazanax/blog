<?php
declare(strict_types=1);

namespace App\Component\PostGetter;

use App\Entity\Post;
use Doctrine\ORM\Tools\Pagination\Paginator;
use LogicException;

class PostGetter implements PostGetterInterface
{
    /**
     * @var PostGetterStrategyInterface[]
     */
    private $strategies;

    /**
     * @param PostGetterStrategyInterface[] $strategies
     */
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

    /**
     * @param string $chosenStrategy
     *
     * @param int    $offset
     * @param int    $limit
     *
     * @return Paginator
     */
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

    /**
     * @param string $chosenStrategy
     * @param array  $tags
     * @param int    $offset
     * @param int    $limit
     *
     * @return Paginator
     */
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
