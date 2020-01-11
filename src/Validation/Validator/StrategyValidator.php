<?php
declare(strict_types=1);

namespace App\Validation\Validator;

use App\Constant\PostStrategy;
use App\Validation\Constraint\Contract\StrategyConstraint;
use App\Validation\Validator\Contract\StrategyValidatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class StrategyValidator extends ConstraintValidator implements StrategyValidatorInterface
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof StrategyConstraint || $this->isValid($value, $constraint)) {
            return;
        }

        $violation = $constraint->buildViolation([
            '{{ strategy }}' => $value,
            '{{ allowed_strategies }}' => PostStrategy::ALLOWED_STRATEGIES
        ]);

        $this->context->buildViolation($violation)
            ->addViolation();
    }

    public function isValid($value, StrategyConstraint $constraint): bool
    {
        return in_array($value, PostStrategy::ALLOWED_STRATEGIES, true);
    }
}
