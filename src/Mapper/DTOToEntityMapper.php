<?php

namespace App\Mapper;

class DTOToEntityMapper
{
    private $entity;
    private $dto;
    private $entityReflection;
    private $dtoReflection;

    public function __construct($dto)
    {
        $this->dto = $dto;
        $this->dtoReflection = new \ReflectionClass($dto);
    }

    public function map(array $fields, $entity)
    {
        $this->entityReflection = new \ReflectionClass($entity);

        if (is_object($entity)) {
            $this->entity = $entity;
        } else {
            $this->entity = $this->entityReflection->newInstanceWithoutConstructor();
        }

        foreach ($fields as $field) {
            $this->fillField($field, $field);
        }

        return $this->entity;
    }

    public function getDtoReflection(): \ReflectionClass
    {
        return $this->dtoReflection;
    }

    private function fillField(string $entityFieldName, string $dtoFieldName)
    {
        $this->checkField($this->entityReflection, $entityFieldName);
        $this->checkField($this->dtoReflection, $dtoFieldName);

        $property = $this->entityReflection->getProperty($entityFieldName);
        $property->setAccessible(true);
        $property->setValue(
            $this->entity,
            $this->dtoReflection->getProperty($dtoFieldName)->getValue($this->dto)
        );
    }


    /**
     * @param \ReflectionClass $reflectionClass
     * @param string $fieldName
     * @return bool
     */
    private function checkField(\ReflectionClass $reflectionClass, string $fieldName)
    {
        if (!$reflectionClass->hasProperty($fieldName)) {
            throw new \InvalidArgumentException(sprintf(
                'Property %s of entity %s does not exist',
                $fieldName,
                $reflectionClass->name
            ));
        }

        return true;
    }
}