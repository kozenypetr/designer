<?php

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use AppBundle\Validator\Constraints\CustomerEmail;

use Symfony\Component\OptionsResolver\OptionsResolver;


class CustomerBillingType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $emailConstraints = [new Email(), new NotBlank()];

        if ($options['registration'])
        {
            $emailConstraints[] = new CustomerEmail();
        }

        $builder
            ->add('email', EmailType::class, array(
                'required' => true,
                'constraints' => $emailConstraints,
                'label' => 'Email'
            ))
            ->add('phone', null, array(
                'required' => true,
                'constraints' => array(new NotBlank()),
                'label' => 'Telefon'
            ))
            ->add('billing_name', null, array(
                'required' => true,
                'constraints' => array(new NotBlank()),
                'label' => 'Celé jméno'
            ))
            ->add('billing_company', null, array(
                'required' => false,
                'label' => 'Název firmy'
            ))
            ->add('billing_ico', null, array(
                'required' => false,
                'label' => 'IČO'
            ))
            ->add('billing_dic', null, array(
                'required' => false,
                'label' => 'DIČ'
            ))
            ->add('billing_address', null, array(
                'required' => true,
                'constraints' => array(new NotBlank()),
                'label' => 'Ulice a ČP'
            ))
            ->add('billing_city', null, array(
                'required' => true,
                'constraints' => array(new NotBlank()),
                'label' => 'Město'
            ))
            ->add('billing_postcode', null, array(
                'required' => true,
                'constraints' => array(new NotBlank()),
                'label' => 'PSČ'
            ))
            ->add('order_note', TextareaType::class, array(
                'required' => true,
                'label' => 'Poznámky k objednávce',
                'attr' => [
                 'placeholder' => 'Vaše poznámky, přání ...'
                ]
            ))
            ->add('is_delivery', CheckboxType::class, array('required' => false, 'label' => 'Jiná doručovací adresa'))
            ->add('is_create_account', CheckboxType::class, array('required' => false, 'label' => 'Chci si tu vytvořit účet'))
        ;
    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'registration' => false,
        ));
    }

}