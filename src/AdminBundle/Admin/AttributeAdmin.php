<?php

// src/AppBundle/Admin/CategoryAdmin.php
namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

use Sonata\CoreBundle\Form\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Sonata\CoreBundle\Validator\ErrorElement;
use AppBundle\Entity\Order;

class AttributeAdmin extends AbstractAdmin
{
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
        ->with('Základní', array('class' => 'col-md-6'))
            ->add('name', 'text', array('label' => 'Název'))
            ->add('isRequired', CheckboxType::class, array('label' => 'Povinný', 'required' => false))
            ->add('type', ChoiceType::class, array(
            'choices'  => array(
                'Text' => TextType::class,
                'Dlouhý text' => TextareaType::class,
                'Select' => ChoiceType::class,
                'Checkbox' => CheckboxType::class,
                'Soubor' => FileType::class,
            )))
            ->add('position', 'text', array('label' => 'Řazení', 'required' => false))
        ->end()
        ->with('Možnosti', array('class' => 'col-md-6'))
            ->add('class', 'text', array('label' => 'Třída', 'required' => false))
            ->add('help', null, array('label' => 'Nápověda'))
            ->add('modul', 'text', array('label' => 'Modul', 'required' => false))
            ->add('options', CollectionType::class, [
                'label' => 'Možnosti',
                'required' => false,
                'by_reference' => false,
                'type_options' => [
                    // Prevents the "Delete" option from being displayed
                    'delete' => true,
                    'delete_options' => [
                        // You may otherwise choose to put the field but hide it
                        'type'         => CheckboxType::class,
                        // In that case, you need to fill in the options as well
                        'type_options' => [
                            'mapped'   => false,
                            'required' => true,
                        ]
                    ]
                ]
                ],[
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable' => 'id'
                ]
            )
        ->end()
     //    ->add('product')
    ;
    // navod na tiny
    // http://www.techtonet.com/sonata-add-ckeditor-in-admin-textareas/
  }

  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('name', null, array('label' => 'Název', 'expand' => true))
    ;
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('id')
      ->add('name', null, array('label' => 'Název'))
    ;
  }


  // add this method
  public function validate(ErrorElement $errorElement, $object)
  {
    /*$errorElement
      ->with('name')
      ->assertLength(array('max' => 32))
      ->end()
    ;*/
  }

  public function toString($object)
  {
    return $object instanceof Attribute
      ? 'Atribut ' . $object->getName()
      : 'Nový atribut'; // shown in the breadcrumb on the create view
  }

}