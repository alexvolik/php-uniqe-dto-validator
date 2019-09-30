<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AppAssert;

/**
 * @AppAssert\UniqueDTO(fields={"login"}, entityClass="App\Entity\User")
 * @AppAssert\UniqueDTO(fields={"email"}, entityClass="App\Entity\User")
 */
class UserDTO
{
    public $id;

    /**
     * @Assert\NotBlank
     * @Assert\Length(max = 255)
     */
    public $login;

    /**
     * @Assert\NotBlank
     * @Assert\Length(max = 255)
     */
    public $email;
}