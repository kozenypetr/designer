<?php

    namespace AppBundle\Validator\Constraints;

    use Symfony\Component\Validator\Constraint;

    /**
     * Class UniqueEmail
     * @package AppBundle\Validator\Constraints
     * @Annotation
     */
    class CustomerEmail extends Constraint
    {
        public $message = 'Tento email je již registrovaný';

        public function validatedBy()
        {
            return get_class($this).'Validator';
        }
    }