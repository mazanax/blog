<?php
declare(strict_types=1);

namespace App\Component\Menu;

use App\Component\Menu\Form\DTO\ItemDTO;
use App\Entity\Menu\Item;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class ItemFiller implements ItemFillerInterface
{
    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    /**
     * @param PropertyAccessorInterface $propertyAccessor
     */
    public function __construct(PropertyAccessorInterface $propertyAccessor)
    {
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * @param Item    $item
     * @param ItemDTO $dto
     */
    public function fillFromDTO(Item $item, ItemDTO $dto): void
    {
        foreach (get_object_vars($dto) as $property => $value) {
            if (!property_exists($item, $property)) {
                continue;
            }

            if (!$this->propertyAccessor->isWritable($item, $property)) {
                continue;
            }

            $this->propertyAccessor->setValue($item, $property, $value);
        }
    }

    /**
     * @param Item  $item
     * @param array $data
     */
    public function fillFromArray(Item $item, array $data): void
    {
        foreach ($data as $property => $value) {
            if (!$this->propertyAccessor->isWritable($item, $property)) {
                continue;
            }

            $this->propertyAccessor->setValue($item, $property, $value);
        }
    }
}
