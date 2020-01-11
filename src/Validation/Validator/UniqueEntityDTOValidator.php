<?php
declare(strict_types=1);

namespace App\Validation\Validator;

use App\Validation\Constraint\UniqueEntityDTO;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueEntityDTOValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueEntityDTO) {
            return;
        }

        $entityRepository = $this->entityManager->getRepository($constraint->entityClass);

        if (!is_scalar($constraint->field)) {
            throw new InvalidArgumentException('"field" parameter should be any scalar type');
        }

        $field = $constraint->field;
        $searchResults = $entityRepository->findOneBy([$constraint->field => $value->$field]);

        if (null !== $searchResults && $searchResults->getId() !== $value->id) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
