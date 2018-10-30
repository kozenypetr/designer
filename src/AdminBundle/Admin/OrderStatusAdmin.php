<?php

// src/AppBundle/Admin/CategoryAdmin.php
namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

use Sonata\CoreBundle\Validator\ErrorElement;
use AppBundle\Entity\Shipping;

class OrderStatusAdmin extends AbstractAdmin
{
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->with('Základní informace', array('class' => 'col-md-9'))
        ->add('name', 'text', array('label' => 'Název stavu'))
        ->add('annotation', 'text', array('label' => 'Krátký text', 'required' => false))
        ->add('is_active', 'checkbox', array('label' => 'Aktivní', 'required' => false))
        ->add('email', 'text', array('label' => 'Šablona emailu', 'required' => false))
        ->add('color', 'text', array('label' => 'Barva', 'required' => false))
        ->add('sort', 'text', array('label' => 'Řazení'))
      ->end()
    ;
    // navod na tiny
    // http://www.techtonet.com/sonata-add-ckeditor-in-admin-textareas/
  }

  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('name', null, array('label' => 'Název stavu'))
    ;
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('id')
      ->add('name', null, array('label' => 'Název stavu','editable' => true))
      ->add('is_active', 'boolean', array('label' => 'Aktivní', 'editable' => true))
      ->add('email', null, array('label' => 'Email', 'editable' => true))
      ->add('color', null, array('label' => 'Barva', 'editable' => true))
      ->add('sort', null, array('label' => 'Řazení', 'editable' => true))
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
    return $object instanceof Shipping
      ? $object->getName()
      : 'Doprava'; // shown in the breadcrumb on the create view
  }

}