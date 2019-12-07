<?php
declare(strict_types=1);

namespace App\Form\DTO;

use App\Entity\Page;
use App\Validation\Constraint\UniqueEntityDTO;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntityDTO(entityClass="App\Entity\Page", field="url")
 */
final class PageDTO
{
    public $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    public $title;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=255)
     * @Assert\Regex(pattern="/^[\-_a-zA-Z0-9]+$/")
     */
    public $url;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    public $content;

    public static function createFromEntity(Page $page): PageDTO
    {
        $dto = new static();
        $dto->id = $page->getId();
        $dto->url = $page->getUrl();
        $dto->title = $page->getTitle();
        $dto->content = $page->getContent();

        return $dto;
    }
}
