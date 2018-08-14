<?php

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\Email;

class CustomerBillingType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array(
                'required' => true,
                'constraints' => array(new Email())
            ))
            ->add('phone', null, array(
                'required' => true,
            ))
            ->add('billing_name', null, array(
                'required' => true,
            ))
            ->add('billing_address', null, array(
                'required' => true,
            ))
            ->add('billing_city', null, array(
                'required' => true,
            ))
            ->add('billing_postcode', null, array(
                'required' => true,
            ))
            ->add('is_delivery', CheckboxType::class)
            ->add('is_create_account', CheckboxType::class)
        ;
    }

}