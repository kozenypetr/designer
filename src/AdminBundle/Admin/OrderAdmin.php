<?php

// src/AppBundle/Admin/CategoryAdmin.php
namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

use Sonata\CoreBundle\Form\Type\CollectionType;

use Sonata\CoreBundle\Validator\ErrorElement;
use AppBundle\Entity\Order;

class OrderAdmin extends AbstractAdmin
{
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->with('Fakturační adresa', array('class' => 'col-md-6'))
        ->add('email', 'text', array('label' => 'Email'))
        ->add('phone', 'text', array('label' => 'Telefon'))
        ->add('billingName', 'text', array('label' => 'Jméno'))
        ->add('billingAddress', 'text', array('label' => 'Adresa'))
        ->add('billingCity', 'text', array('label' => 'Město'))
        ->add('billingPostcode', 'text', array('label' => 'Město'))
      ->end()
      ->with('Dodací adresa', array('class' => 'col-md-6'))
        ->add('deliveryName', 'text', array('label' => 'Jméno'))
        ->add('deliveryAddress', 'text', array('label' => 'Adresa'))
        ->add('deliveryCity', 'text', array('label' => 'Město'))
        ->add('deliveryPostcode', 'text', array('label' => 'Město'))
      ->end()
      ->with('Celkově', array('class' => 'col-md-12'))
            ->add('subtotal', 'text', array('label' => 'Cena bez DPH'))
            ->add('tax', 'text', array('label' => 'DPH'))
            ->add('total', 'text', array('label' => 'Celkem s DPH'))
      ->end()
      ->with('Objednávka', array('class' => 'col-md-12'))
        ->add('items', CollectionType::class, [
            'type_options' => [
                // Prevents the "Delete" option from being displayed
                'delete' => false,
                'delete_options' => [
                    // You may otherwise choose to put the field but hide it
                    'type'         => HiddenType::class,
                    // In that case, you need to fill in the options as well
                    'type_options' => [
                        'mapped'   => false,
                        'required' => false,
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
    ;
    // navod na tiny
    // http://www.techtonet.com/sonata-add-ckeditor-in-admin-textareas/
  }

  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('email', null, array('label' => 'Email', 'expand' => true))
      ->add('billingName', null, array('label' => 'Jméno', 'expand' => true))
    ;
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('id')
      ->add('created', null, array('label' => 'Vytvořeno'))
      ->add('billingName', null, array('label' => 'Jméno'))
      ->add('billingCity', null, array('label' => 'Město'))
      ->add('total', null, array('label' => 'Celková cena'))
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
    /*$errorElement
      ->with('name')
      ->assertLength(array('max' => 32))
      ->end()
    ;*/
  }

  public function toString($object)
  {
    return $object instanceof Order
      ? 'Objednávka č. ' . $object->getId()
      : 'Objednávka'; // shown in the breadcrumb on the create view
  }

}