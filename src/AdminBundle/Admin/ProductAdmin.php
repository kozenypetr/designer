<?php

// src/AppBundle/Admin/CategoryAdmin.php
namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

use Sonata\CoreBundle\Validator\ErrorElement;
use AppBundle\Entity\Product;

class ProductAdmin extends AbstractAdmin
{
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->with('Základní informace', array('class' => 'col-md-9'))

        ->add('name', 'text', array('label' => 'Název produktu'))
        ->add('annotation', 'text', array('label' => 'Krátký popis'))
        ->add('description', 'textarea', array('label' => 'Popis', 'required' => false, 'attr' => array('class' => 'ckeditor')))
        ->add('price')
        ->add('is_active', 'checkbox', array('label' => 'Aktivní', 'required' => false))
      ->end()
      ->with('Kategorie', array('class' => 'col-md-3'))
        ->add('categories', 'sonata_type_model', array('class' => 'AppBundle\Entity\Category', 'label' => 'Kategorie produktu', 'expanded' => true, 'multiple' => true))
      ->end()
    ;
    // navod na tiny
    // http://www.techtonet.com/sonata-add-ckeditor-in-admin-textareas/
  }

  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('name', null, array('label' => 'Název produktu'))
      ->add('categories', null, array('label' => 'Kategorie', 'expand' => true))
    ;
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('id')
      ->add('name', null, array('label' => 'Název produktu','editable' => true))
      ->add('is_active', 'boolean', array('label' => 'Aktivní', 'editable' => true))
      ->add('categories', null, array('label' => 'Kategorie', 'editable' => true))
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
    return $object instanceof Product
      ? $object->getName()
      : 'Produkt'; // shown in the breadcrumb on the create view
  }

}