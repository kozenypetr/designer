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

class OrderItemAdmin extends AbstractAdmin
{
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
        ->add('name', 'text')
        ->add('model', 'text')
        ->add('price', 'text')
        ->add('quantity', 'text')
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
      ->add('model', null, array('label' => 'Model'))
      ->add('price', null, array('label' => 'Cena'))
      ->add('quantity', null, array('label' => 'Cena'))
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
    return $object instanceof OrderItem
      ? $object->getName()
      : 'Položka objednávky'; // shown in the breadcrumb on the create view
  }

}