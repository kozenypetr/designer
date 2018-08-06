<?php

// src/AppBundle/Admin/CategoryAdmin.php
namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

use Sonata\UserBundle\Admin\Model\UserAdmin as SonataUserAdmin;

class User extends SonataUserAdmin
{
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper->add('name', 'text');
  }

  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper->add('name');
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper->addIdentifier('name');
  }
}