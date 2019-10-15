<?php

namespace App\Validator\Constraints;

use App\Helper\ArrayHelper;
use App\Mapper\DTOToEntityMapper;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityNotFoundException;
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

        if (ArrayHelper::isAssocArray($constraint->fields)) {
            $constraint->errorPath = ArrayHelper::getFirstStringKeyInAssocArray($constraint->fields);
            $constraint->fields = array_values($constraint->fields);
        }

        parent::validate($entity, $constraint);
    }

    private function getEntity($dtoObject, UniqueDTO $constraint)
    {
        $mapper = new DTOToEntityMapper($dtoObject);
        $entityClass = $constraint->mapToEntityClass;

        $em = $this->registry->getManager($constraint->em);

        // TODO: add the ability to work with composite ids
        $identifierFieldName = $em->getClassMetadata($constraint->mapToEntityClass)->getSingleIdentifierFieldName();
        if (!$mapper->getDtoReflection()->hasProperty($identifierFieldName)) {
            throw new \InvalidArgumentException(sprintf(
                'Property %s of class %s does not exist',
                $identifierFieldName,
                $mapper->getDtoReflection()->name
            ));
        }

        $property = $mapper->getDtoReflection()->getProperty($identifierFieldName);
        $property->setAccessible(true);

        if (null === $id = $property->getValue($dtoObject)) {
            return $mapper->map($constraint->fields, $entityClass);
        }

        if (null === $entity = $em->getRepository($entityClass)->find($id)) {
            throw new EntityNotFoundException(
                sprintf(
                    'Entity of class %s with id %s not found',
                    $constraint->mapToEntityClass,
                    $id
                )
            );
        }

        return $mapper->map($constraint->fields, $entity);
    }
}
