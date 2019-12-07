<?php
declare(strict_types=1);

namespace App\Component\Filler;

use App\Entity\Page;
use App\Form\DTO\PageDTO;

interface PageFillerInterface
{
    public function fillFromDto(Page $page, PageDTO $pageDTO): void;
}
