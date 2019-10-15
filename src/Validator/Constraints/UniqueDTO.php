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
     * Class on which will be DTO transferred before uniqueness check.
     *
     * @var string
     */
    public $mapToEntityClass;

    /**
     * @return string[]
     */
    public function getRequiredOptions(): array
    {
        return ['fields', 'mapToEntityClass'];
    }

    public function validatedBy(): string
    {
        return UniqueDTOValidator::class;
    }
}
