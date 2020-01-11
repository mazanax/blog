<?php
declare(strict_types=1);

namespace App\Validation\Constraint;

use App\Validation\Constraint\Contract\StrategyConstraint;
use App\Validation\Validator\StrategyValidator;
use Symfony\Component\Validator\Constraint;

class Strategy extends Constraint implements StrategyConstraint
{
    private $message = 'Unknown strategy `{{ strategy }}`. Allowed: {{ allowed_strategies }}';

    public function validatedBy(): string
    {
        return StrategyValidator::class;
    }

    public function buildViolation(array $placeholders): string
    {
        return strtr($this->message, $placeholders);
    }
}
