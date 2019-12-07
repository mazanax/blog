<?php
declare(strict_types=1);

namespace App\Validation\Validator;

interface ValidatorInterface
{
    public function validate($value, array $constraints, array $groups = null): array;
}
