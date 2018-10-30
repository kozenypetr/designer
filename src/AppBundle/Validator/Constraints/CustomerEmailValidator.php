<?php

    namespace AppBundle\Validator\Constraints;

    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\Validator\Constraint;
    use Symfony\Component\Validator\ConstraintValidator;
    use Symfony\Component\Validator\Exception\UnexpectedTypeException;

    class CustomerEmailValidator extends ConstraintValidator
    {
        /**
         * @var EntityManager
         */
        protected $em;

        public function __construct(EntityManagerInterface $em)
        {
            $this->em = $em;
        }

        public function validate($value, Constraint $constraint)
        {
            $customer = $this->em->getRepository('AppBundle:Customer')->findOneByEmail($value);

            if ($customer)
            {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }