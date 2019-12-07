<?php
declare(strict_types=1);

namespace App\Validation\Constraint\Contract;

interface StrategyConstraint extends Constraint
{
    public function buildViolation(array $placeholders): string;
}
