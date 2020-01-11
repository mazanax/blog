<?php
declare(strict_types=1);

namespace App\Repository\Contract;

use App\Repository\Contract\Post\DraftRepositoryInterface;
use App\Repository\Contract\Post\PublishedRepositoryInterface;
use App\Repository\Contract\Post\ScheduledRepositoryInterface;

interface PostRepositoryInterface extends DraftRepositoryInterface, PublishedRepositoryInterface, ScheduledRepositoryInterface
{

}
