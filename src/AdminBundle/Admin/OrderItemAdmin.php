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

class OrderItemAdmin extends AbstractAdmin
{
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
        ->add('name', 'text', array('label' => 'Název'))
        ->add('model', 'text', array('label' => 'Kód'))
        ->add('price', 'text', array('label' => 'Cena'))
        ->add('quantity', 'text', array('label' => 'Počet'))
        ->add('attributes', null, array('label' => 'Atributy'))
    ;

    $formMapper->get('attributes')
          ->addModelTransformer(new CallbackTransformer(
              function ($attributesAsArray) {
                  // transform the array to a string
                  // $attributesAsArray = array_map('utf8_encode', $attributesAsArray);
                  return json_encode($attributesAsArray, JSON_UNESCAPED_UNICODE);
              },
              function ($attributesAsString) {
                  return json_decode($attributesAsString, true);
              }
          ))
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