<?php

// src/AppBundle/Admin/CategoryAdmin.php
namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

use Sonata\CoreBundle\Validator\ErrorElement;
use AppBundle\Entity\Payment;

class PaymentAdmin extends AbstractAdmin
{
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->with('Základní informace', array('class' => 'col-md-9'))
        ->add('locale', 'choice',
            ['label' => 'Jazyk', 'required' => false,
                'choices' => [
                    'Čeština' => 'cs',
                    'Angličtina' => 'en',
                ]
            ])
        ->add('name', 'text', array('label' => 'Název produktu'))
        ->add('annotation', 'text', array('label' => 'Krátký text', 'required' => false))
        ->add('code', 'text', array('label' => 'Kód'))
        ->add('icon', 'text', array('label' => 'Ikona', 'required' => false))
        ->add('time', 'text', array('label' => 'Čas platby', 'required' => false))
        ->add('is_active', 'checkbox', array('label' => 'Aktivní', 'required' => false))
        ->add('description', 'textarea', array('label' => 'Popis', 'required' => false, 'attr' => array('class' => 'ckeditor')))
        ->add('priceTable', 'textarea', array('label' => 'Cenová tabulka', 'required' => false))
        ->add('status', null, array('label' => 'Výchozí stav dopravy', 'required' => true))
        ->add('sort', null, array('label' => 'Řazení', 'required' => false))

      ->end()
      ->with('Dopravy', array('class' => 'col-md-3'))
        ->add('shippings', 'sonata_type_model', array('class' => 'AppBundle\Entity\Shipping', 'label' => 'Dopravy', 'expanded' => true, 'multiple' => true))
      ->end()
    ;
    // navod na tiny
    // http://www.techtonet.com/sonata-add-ckeditor-in-admin-textareas/
  }

  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('name', null, array('label' => 'Název dopravy'))
      ->add('locale', 'doctrine_orm_string', array('label' => 'Jazyk', 'show_filter' => true), 'choice',
            ['choices' => ['Čeština' => 'cs', 'Angličtina' => 'en']])
    ;
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('id')
      ->add('name', null, array('label' => 'Název produktu','editable' => true))
      ->add('code', null, array('label' => 'Kód','editable' => true))
      ->add('is_active', 'boolean', array('label' => 'Aktivní', 'editable' => true))
      ->add('locale', null, array('label' => 'Jazyk', 'editable' => true))
      ->add('sort', null, array('label' => 'Řazení', 'editable' => true))
      ->add('shippings', null, array('label' => 'Dopravy', 'editable' => true))
      // add custom action links
      ->add('_action', 'actions', array(
        'actions' => array(
          'edit' => array(),
          'delete' => array(),
        ),
        'label' => 'Akce'
      ))
    ;
  }


  // add this method
  public function validate(ErrorElement $errorElement, $object)
  {
    $errorElement
      ->with('name')
      ->assertLength(array('max' => 32))
      ->end()
    ;
  }

  public function toString($object)
  {
    return $object instanceof Payment
      ? $object->getName()
      : 'Doprava'; // shown in the breadcrumb on the create view
  }

}