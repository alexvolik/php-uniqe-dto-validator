<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Form\Handler\FormHandler;
use App\Form\UserDTOType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class UserController extends AbstractFOSRestController
{
    private $formHandler;

    public function __construct(FormHandler $formHandler)
    {
        $this->formHandler = $formHandler;
    }

    /**
     * @Rest\Post(path="check")
     *
     * @Rest\View()
     */
    public function check(Request $request)
    {
        $handledData = $this->formHandler->handle($request->request->all(), UserDTOType::class, new UserDTO());
        if (!$handledData instanceof UserDTO) {
            return $this->view($handledData, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $handledData;
    }
}
