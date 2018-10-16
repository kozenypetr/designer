<?php

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\OptionsResolver\OptionsResolver;


class CustomerDeliveryType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('delivery_name', null, array(
                'required' => true,
                'constraints' => array(new NotBlank()),
                'label' => 'Celé jméno'
            ))
            ->add('delivery_phone', null, array(
                'required' => false,
                'label' => 'Telefon'
            ));

        $type = null;
        $constraints = array(new NotBlank());
        if (!$options['full_address'])
        {
            $type = 'hidden';
            $constraints = null;
        }

        $builder
            ->add('delivery_company', $type, array(
                'required' => false,
                'label' => 'Firma'
            ))

            ->add('delivery_address', $type, array(
                'required' => true,
                'constraints' => $constraints,
                'label' => 'Ulice a ČP'
            ))
            ->add('delivery_city', $type, array(
                'required' => true,
                'constraints' => $constraints,
                'label' => 'Město'
            ))
            ->add('delivery_postcode', $type, array(
                'required' => true,
                'constraints' => $constraints,
                'label' => 'PSČ'
            ))
            ->add('delivery_note', $type, array(
                'required' => false,
                'label' => 'Poznámka k dodací adrese'
            ))
        ;

    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'full_address' => true,
        ));
    }

}