<?php
declare(strict_types=1);

namespace App\Form\DTO;

use App\Entity\Config;
use App\Validation\Constraint\UniqueEntityDTO;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntityDTO(entityClass="App\Entity\Config", field="slug")
 */
class ConfigDTO
{
    private const DEFAULT_CONTENT = '';

    public $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    public $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    public $slug;

    public $content = self::DEFAULT_CONTENT;

    public static function createFromEntity(Config $config): ConfigDTO
    {
        $dto = new self();
        $dto->id = $config->getId();
        $dto->name = $config->getName();
        $dto->slug = $config->getSlug();
        $dto->content = $config->getContent() ?? self::DEFAULT_CONTENT;

        return $dto;
    }
}
