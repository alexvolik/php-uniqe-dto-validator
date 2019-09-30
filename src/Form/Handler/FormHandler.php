<?php

namespace App\Form\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormHandler
{
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function handle(
        array $data,
        // Form type classname
        string $type,
        // Initial data
        $entity,
        array $options = []
    ) {
        $form = $this->formFactory->create($type, $entity, $options);
        $form->submit($data);

        return $this->processSubmitted($form, $entity);
    }

    public function handlePartialUpdate(
        array $data,
        // Form type classname
        string $type,
        // Initial data
        $entity,
        array $options = []
    ) {
        $form = $this->formFactory->create($type, $entity, $options);
        $form->submit($data, false);

        return $this->processSubmitted($form, $entity);
    }

    private function processSubmitted(FormInterface $form, $entity)
    {
        if (!$form->isValid()) {
            return $form->getErrors();
        }

        if (!is_object($entity)) {
            $entity = $form->getData();
        }

        return $entity;
    }
}
