<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AppAssert;

/**
 * @AppAssert\UniqueDTO(fields={"userName": "login"}, mapToEntityClass="App\Entity\User")
 * @AppAssert\UniqueDTO(fields={"email"}, mapToEntityClass="App\Entity\User")
 */
class UserDTO
{
    public $id;

    /**
     * @Assert\NotBlank
     * @Assert\Length(max = 255)
     */
    public $userName;

    /**
     * @Assert\NotBlank
     * @Assert\Length(max = 255)
     */
    public $email;
}