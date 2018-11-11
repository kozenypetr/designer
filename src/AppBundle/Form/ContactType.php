<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'Vaše jméno', 'attr' => array('placeholder' => ''),
                'constraints' => array(
                    new NotBlank(array("message" => "Vyplňte prosím jméno")),
                )
            ))
            ->add('subject', TextType::class, array('required' => false, 'label' => 'Předmět', 'attr' => array('placeholder' => ''),
                'constraints' => array(

                )
            ))
            ->add('email', EmailType::class, array('label' => 'Email', 'attr' => array('placeholder' => ''),
                'constraints' => array(
                    new NotBlank(array("message" => "Vyplňte prosím emailovou adresu")),
                    new Email(array("message" => "Emailová adresa nevypadá validní")),
                )
            ))
            ->add('phone', TextType::class, array('required' => false, 'label' => 'Telefon', 'attr' => array('placeholder' => ''),
                'constraints' => array(

                )
            ))
            ->add('message', TextareaType::class, array('label' => 'Váš dotaz', 'attr' => array('placeholder' => ''),
                'constraints' => array(
                    new NotBlank(array("message" => "Napište nám prosím, co potřebujete")),
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'error_bubbling' => true
        ));
    }

    public function getName()
    {
        return 'contact_form';
    }
}