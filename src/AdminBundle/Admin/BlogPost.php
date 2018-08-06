<?php

// src/AppBundle/Admin/BlogPostAdmin.php
namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

use AppBundle\Entity\BlogPost as Post;


class BlogPost extends AbstractAdmin
{
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->tab('Post')
        ->with('Obsah', array('class' => 'col-md-9'))
        ->add('title', 'text')
        ->add('body', 'textarea')
        ->end()

        ->with('Kategorie', array('class' => 'col-md-3'))
        ->add('category', 'sonata_type_model', array(
          'class' => 'AppBundle\Entity\Category',
          'property' => 'name',
        ))
        ->end()
      ->end()
      ->tab('Publish Options')
      // ...
      ->end()
    ;
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('title', 'text', array('label' => 'Název'))
      ->add('category.name', null, ['label' => 'Kategorie'])
      ->add('draft', null, ['label' => 'Koncept'])
    ;
  }

  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('title')
      ->add('category', null, array(), 'entity', array(
        'class'    => 'AppBundle\Entity\Category',
        'choice_label' => 'name', // In Symfony2: 'property' => 'name',
      ))
    ;
  }

  public function toString($object)
  {
    return $object instanceof Post
      ? $object->getTitle()
      : 'Článek'; // shown in the breadcrumb on the create view
  }

}