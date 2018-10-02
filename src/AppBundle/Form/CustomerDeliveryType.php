<?php

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class CustomerDeliveryType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('delivery_name', null, array(
                'required' => true,
                'constraints' => array(new NotBlank())
            ))
            ->add('delivery_address', null, array(
                'required' => true,
                'constraints' => array(new NotBlank())
            ))
            ->add('delivery_city', null, array(
                'required' => true,
                'constraints' => array(new NotBlank())
            ))
            ->add('delivery_postcode', null, array(
                'required' => true,
                'constraints' => array(new NotBlank())
            ))
            ->add('delivery_note', null, array(
                'required' => true,
                'constraints' => array(new NotBlank())
            ))
        ;
    }

}