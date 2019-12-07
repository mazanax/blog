<?php
declare(strict_types=1);

namespace App\Component\Menu\Factory;

use App\Component\Menu\Factory\ItemDTO\ConcreteItemDTOFactoryInterface;
use App\Component\Menu\Form\DTO\ItemDTO;
use App\Entity\Menu\Item;
use InvalidArgumentException;
use LogicException;

class ItemDTOFactory implements ItemDTOFactoryInterface
{
    /**
     * @var ConcreteItemDTOFactoryInterface[]
     */
    private $factories;

    /**
     * @param ConcreteItemDTOFactoryInterface[] $factories
     */
    public function __construct(ConcreteItemDTOFactoryInterface ...$factories)
    {
        $this->factories = $factories;
    }

    /**
     * @param Item $item
     *
     * @return ItemDTO
     */
    public function createFromEntity(Item $item): ItemDTO
    {
        foreach ($this->factories as $factory) {
            if (!$factory->supports($item->getType())) {
                continue;
            }

            return $factory->createFromEntity($item);
        }

        throw new LogicException(sprintf('[ItemDTOFactory] Unknown item %s', get_class($item)));
    }

    /**
     * @param array $data
     *
     * @return ItemDTO
     */
    public function createFromArray(array $data): ItemDTO
    {
        if (!array_key_exists('type', $data) || !is_int($data['type'])) {
            throw new InvalidArgumentException('Data array should contain field `type` with integer value');
        }

        $dto = null;

        foreach ($this->factories as $factory) {
            if (!$factory->supports($data['type'])) {
                continue;
            }

            $dto = $factory->create();
        }

        if (null === $dto) {
            throw new LogicException(sprintf('[ItemDTOFactory] Unknown item type %d', $data['type']));
        }

        foreach (get_object_vars($dto) as $property => $_) {
            $dto->$property = $data[$property] ?? null;
        }

        return $dto;
    }
}
