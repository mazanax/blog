<?php
declare(strict_types=1);

namespace App\Validation\Validator\Contract;

use App\Validation\Constraint\Contract\StrategyConstraint;

interface StrategyValidatorInterface
{
    public function isValid($value, StrategyConstraint $constraint): bool;
}
