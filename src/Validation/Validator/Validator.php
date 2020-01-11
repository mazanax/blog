<?php
declare(strict_types=1);

namespace App\Validation\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidatorInterface;

class Validator implements ValidatorInterface
{
    private $validator;

    public function __construct(SymfonyValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate($value, array $constraints, array $groups = null): array
    {
        $errors = $this->validator->validate($value, $constraints, $groups);

        return iterator_to_array($errors);
    }
}
