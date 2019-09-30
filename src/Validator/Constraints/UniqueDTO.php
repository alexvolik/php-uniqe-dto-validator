<?php

namespace App\Validator\Constraints;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Checks if entity associated to DTO is unique.
 *
 * @Annotation
 * @Target({"CLASS"})
 */
class UniqueDTO extends UniqueEntity
{
    /**
     * Entity class which will be used for uniqueness check.
     *
     * @var string
     */
    public $entityClass;

    /**
     * @return string[]
     */
    public function getRequiredOptions(): array
    {
        return ['fields', 'entityClass'];
    }

    public function validatedBy(): string
    {
        return UniqueDTOValidator::class;
    }
}
