Issue: 
======

`UniqueEntityValidator` not working with DTO, because this class not a doctrine entity.

Usage:
======

For validating need to add annotation UniqueDTO on DTO class with 
required parameters **fields** and **mapToEntityClass**.

**Fields** required option is the field (or list of fields) on which this entity should be unique. For example, if you specified both the email and login field in a single UniqueEntity constraint, then it would enforce that the combination value is unique (e.g. two users could have the same email, as long as they don't have the same login also).

If you need to require two fields to be individually unique (e.g. a unique email and a unique login), you use two UniqueEntity entries, each with a single field.

```
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
```