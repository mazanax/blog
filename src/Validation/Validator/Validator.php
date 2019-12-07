<?php
declare(strict_types=1);

namespace App\Validation\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidatorInterface;

class Validator implements ValidatorInterface
{
    /**
     * @var SymfonyValidatorInterface
     */
    private $validator;

    /**
     * @param SymfonyValidatorInterface $validator
     */
    public function __construct(SymfonyValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param            $value
     * @param array      $constraints
     * @param array|null $groups
     *
     * @return array
     */
    public function validate($value, array $constraints, array $groups = null): array
    {
        $errors = $this->validator->validate($value, $constraints, $groups);

        return iterator_to_array($errors);
    }
}
