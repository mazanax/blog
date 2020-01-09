<?php
declare(strict_types=1);

namespace App\Component\Menu\Factory;

use App\Component\Menu\Factory\Item\ConcreteItemFactoryInterface;
use App\Component\Menu\Form\DTO\ItemDTO;
use App\Entity\Menu\Item;
use LogicException;

class ItemFactory implements ItemFactoryInterface
{
    /**
     * @var ConcreteItemFactoryInterface[]
     */
    private $concreteFactories;

    /**
     * @param ConcreteItemFactoryInterface[] $concreteFactories
     */
    public function __construct(ConcreteItemFactoryInterface ...$concreteFactories)
    {
        $this->concreteFactories = $concreteFactories;
    }

    /**
     * @param ItemDTO $dto
     *
     * @return Item
     */
    public function createByDTO(ItemDTO $dto): Item
    {
        foreach ($this->concreteFactories as $factory) {
            if (!$factory->supports($dto->type)) {
                continue;
            }

            return $factory->create();
        }

        throw new LogicException(sprintf('[ItemFactory] Unknown menu item %d', $dto->type));
    }
}
