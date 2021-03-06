<?php

// src/AppBundle/Admin/CategoryAdmin.php
namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

use Sonata\CoreBundle\Form\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


use Sonata\AdminBundle\Route\RouteCollection;

use Sonata\CoreBundle\Validator\ErrorElement;
use AppBundle\Entity\Product;

class ProductAdmin extends AbstractAdmin
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
        ->add('subname', 'text', array('label' => 'Upřesňující název', 'required' => false))

        ->add('model', 'text', array('label' => 'Kód'))
        ->add('annotation', 'text', array('label' => 'Krátký popis'))
        ->add('description', 'textarea', array('label' => 'Popis', 'required' => false, 'attr' => array('class' => 'tiny')))
        ->add('price', null, array('label' => 'Cena'))
        ->add('is_active', 'checkbox', array('label' => 'Aktivní', 'required' => false))
        ->add('availability', null, array('label' => 'Doručení (ve dnech)', 'required' => false))
        ->add('module', 'text', array('label' => 'Modul editace', 'required' => false))
        ->add('sort', 'text', array('label' => 'Řazení', 'required' => true))
      ->end()
      ->with('Kategorie', array('class' => 'col-md-3'))
        ->add('mainCategory', 'sonata_type_model', array('class' => 'AppBundle\Entity\Category', 'label' => 'Hlavní kategorie', 'expanded' => false, 'multiple' => false))
        ->add('categories', 'sonata_type_model', array('class' => 'AppBundle\Entity\Category', 'label' => 'Kategorie produktu', 'expanded' => true, 'multiple' => true))
        ->add('events', 'sonata_type_model', array('class' => 'AppBundle\Entity\Event', 'label' => 'Události', 'expanded' => true, 'multiple' => true))
        ->add('material', null, array('label' => 'Materiál', 'expanded' => false, 'multiple' => false))
      ->end()
      ->with('Feedy', array('class' => 'col-md-9'))
        ->add('feedName', 'text', array('label' => 'Název pro katalogy', 'required' => false))
        ->add('feedDescription', 'text', array('label' => 'Popis v katalogu', 'required' => false))
      ->end()
      ->with('Heureka nastavení', array('class' => 'col-md-9'))
        ->add('isHeureka', 'checkbox', array('label' => 'Export do Heureky', 'required' => false))
        ->add('heurekaCpc', 'text', array('label' => 'Heureka CPC', 'required' => false))
      ->end()
      ->with('Zbozi.cz nastavení', array('class' => 'col-md-9'))
        ->add('isZbozi', 'checkbox', array('label' => 'Export na zbozi.cz', 'required' => false))
        ->add('zboziMaxCpc', 'text', array('label' => 'Maximální CPC', 'required' => false))
        ->add('zboziMaxCpcList', 'text', array('label' => 'Maximální CPC (vyhledávání)', 'required' => false))
      ->end()
      ->with('Google Merchant', array('class' => 'col-md-9'))
        ->add('isGoogle', 'checkbox', array('label' => 'Export na Google', 'required' => false))
        ->add('googleProductCategory', 'text', array('label' => 'Google kategorie', 'required' => false, 'help' => 'https://support.google.com/merchants/answer/6324436'))
      ->end()
      ->with('Atributy', array('class' => 'col-md-9'))
        ->add('attributes', CollectionType::class, [
            'label' => false,
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
                'inline' => 'natural',
                // 'sortable' => 'position'
            ]
        )
      ->end()
      ->with('Metadata', array('class' => 'col-md-9'))
        ->add('customMetatitle', 'text', array('label' => 'Metatitle', 'required' => false))
        ->add('customMetadescription', 'text', array('label' => 'Metadescription', 'required' => false))
        ->add('customMetakeywords', 'text', array('label' => 'Metakeywords', 'required' => false))
      ->end()
      ->with('Přístup', array('class' => 'col-md-9'))
        ->add('hash', 'text', array('label' => 'Hash', 'required' => false))
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
      ->add('locale', 'doctrine_orm_string', array('label' => 'Jazyk', 'show_filter' => true), 'choice',
            ['choices' => ['Čeština' => 'cs', 'Angličtina' => 'en']])
    ;
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('id')
      ->add('name', null, array('label' => 'Název produktu','editable' => true))
      ->add('slug', null, array('label' => 'URL','editable' => true))
      ->add('model', null, array('label' => 'Kód','editable' => true))
      ->add('is_active', 'boolean', array('label' => 'Aktivní', 'editable' => true))
      ->add('locale', null, array('label' => 'Jazyk', 'editable' => true))
      ->add('price', null, array('label' => 'Cena', 'editable' => false))
      ->add('sort', null, array('label' => 'Řazení', 'editable' => true))
      ->add('mainCategory', null, array('label' => 'Hlavní kategorie', 'editable' => false))
      ->add('categories', null, array('label' => 'Kategorie', 'editable' => true))
      // add custom action links
      ->add('_action', 'actions', array(
        'actions' => array(
          'edit' => array(),
          'delete' => array(),
            'clone' => [
                'template' => 'AdminBundle::CRUD/list__action_clone.html.twig'
            ]
        ),
        'label' => 'Akce'
      ))
    ;
  }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('clone', $this->getRouterIdParameter().'/clone');
    }


  // add this method
  public function validate(ErrorElement $errorElement, $object)
  {
    $errorElement
      ->with('name')
      ->assertLength(array('max' => 64))
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