<?php

// src/AppBundle/Admin/CategoryAdmin.php
namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

use AppBundle\Entity\Category;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class MaterialAdmin extends AbstractAdmin
{
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->add('locale', 'choice',
            ['label' => 'Jazyk', 'required' => false,
                'choices' => [
                    'Čeština' => 'cs',
                    'Angličtina' => 'en',
                ]
      ])
      ->add('name', 'text')
      ->add('list_name', 'text')
      ->add('description', 'textarea', ['label' => 'Popis', 'required' => false, 'attr' => ['class' => 'tiny']])
      ->add('is_active', 'checkbox', array('label' => 'Aktivní', 'required' => false))
      ->add('icon', null, array('label' => 'Ikona', 'required' => false))
      ->add('sort', 'text', array('label' => 'Řazení', 'required' => false))
    ;
  }

  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
        ->add('name', null, array('label' => 'Název kategorie', 'show_filter' => false))
        ->add('locale', 'doctrine_orm_string', array('label' => 'Jazyk', 'show_filter' => false), 'choice',
             ['choices' => ['Čeština' => 'cs', 'Angličtina' => 'en']])
        ;
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('id')
      ->add('name', null, array('label' => 'Název kategorie', 'editable' => true))
      ->add('list_name', null, array('label' => 'Název kategorie - výpis', 'editable' => true))
      ->add('is_active', 'boolean', array('label' => 'Aktivní', 'editable' => true))
      ->add('sort', null, array('label' => 'Řazení', 'editable' => true))
      ->add('locale', null, array('label' => 'Jazyk', 'editable' => true))
      ->add('_action', 'actions', array(
        'actions' => array(
          'edit' => array(),
          'delete' => array(),
        ),
        'label' => 'Akce'
      ))
    ;
  }
}