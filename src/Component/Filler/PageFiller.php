<?php
declare(strict_types=1);

namespace App\Component\Filler;

use App\Entity\Page;
use App\Form\DTO\PageDTO;

class PageFiller implements PageFillerInterface
{
    public function fillFromDto(Page $page, PageDTO $pageDTO): void
    {
        $page->setUrl($pageDTO->url);
        $page->setTitle($pageDTO->title);
        $page->setContent($pageDTO->content);
    }
}
