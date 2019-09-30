<?php

namespace App\Validator\Constraints;

use App\Mapper\DTOToEntityMapper;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntityValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueDTOValidator extends UniqueEntityValidator
{
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry);

        $this->registry = $registry;
    }

    public function validate($dtoObject, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueDTO) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\UniqueDTO');
        }

        $entity = $this->getEntity($dtoObject, $constraint);

        parent::validate($entity, $constraint);
    }

    private function getEntity($dtoObject, UniqueDTO $constraint)
    {
        $mapper = new DTOToEntityMapper($dtoObject);
        $entity = $constraint->entityClass;

        if (!$mapper->getDtoReflection()->hasProperty('id')) {
            return $mapper->map($constraint->fields, $entity);
        }

        $property = $mapper->getDtoReflection()->getProperty('id');
        $property->setAccessible(true);

        if (null === $id = $property->getValue($dtoObject)) {
            return $mapper->map($constraint->fields, $entity);
        }

        $em = $this->registry->getManager($constraint->em);
        $entity = $em->getRepository($entity)->find($id);

        return $mapper->map($constraint->fields, $entity);
    }
}
