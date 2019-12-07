<?php
declare(strict_types=1);

namespace App\Validation\Constraint;

use App\Validation\Validator\UniqueEntityDTOValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueEntityDTO extends Constraint
{
    public $message = 'This value is already used.';
    public $entityClass;
    public $field;

    /**
     * @return array
     */
    public function getRequiredOptions(): array
    {
        return ['entityClass', 'field'];
    }

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return UniqueEntityDTOValidator::class;
    }
}
