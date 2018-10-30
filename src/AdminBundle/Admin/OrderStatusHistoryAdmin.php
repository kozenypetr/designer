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

use Symfony\Component\Form\CallbackTransformer;

class OrderStatusHistoryAdmin extends AbstractAdmin
{
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
        ->add('status', null, array('label' => 'Stav'))
        // ->add('order', 'hidden')
        ->add('email', null, array('label' => 'Zaslat email'))
        ->add('message', null, array('label' => 'Poznámka do mailu'))
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
      ->add('status', null, array('label' => 'Stav'))
      ->add('email', null, array('label' => 'Zaslat email'))
      ->add('message', null, array('label' => 'Poznámka do mailu'))

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
    return $object instanceof OrderStatusHistory
      ? 'Historie objednavky č. ' . $object->getId()
      : 'Historie objednávky'; // shown in the breadcrumb on the create view
  }

}