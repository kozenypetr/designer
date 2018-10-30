<?php

// src/AppBundle/Admin/CategoryAdmin.php
namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

use Sonata\CoreBundle\Form\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Sonata\CoreBundle\Validator\ErrorElement;
use AppBundle\Entity\Order;

class OrderAdmin extends AbstractAdmin
{
  protected $datagridValues = [

        // display the first page (default = 1)
        '_page' => 1,

        // reverse order (default = 'ASC')
        '_sort_order' => 'DESC',

        // name of the ordered field (default = the model's id field, if any)
        '_sort_by' => 'id',
  ];


  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->with('Fakturační adresa', array('class' => 'billing-address col-md-6'))
        ->add('email', 'text', array('label' => 'Email'))
        ->add('phone', 'text', array('label' => 'Telefon'))
        ->add('billingName', 'text', array('label' => 'Jméno'))
        ->add('billingAddress', 'text', array('label' => 'Adresa'))
        ->add('billingCity', 'text', array('label' => 'Město'))
        ->add('billingPostcode', 'text', array('label' => 'Město'))
      ->end()
      ->with('Dodací adresa', array('class' => ' delivery-address col-md-6'))
        ->add('deliveryName', 'text', array('label' => 'Jméno', 'required' => false))
        ->add('deliveryAddress', 'text', array('label' => 'Adresa', 'required' => false))
        ->add('deliveryCity', 'text', array('label' => 'Město', 'required' => false))
        ->add('deliveryPostcode', 'text', array('label' => 'Město', 'required' => false))
      ->end()
      ->with('Celkově', array('class' => 'col-md-6 clearfix'))
            ->add('subtotal', 'text', array('label' => 'Cena bez DPH'))
            ->add('tax', 'text', array('label' => 'DPH'))
            ->add('total', 'text', array('label' => 'Celkem s DPH'))

      ->end()
      ->with('Doprava a platba', array('class' => 'col-md-6'))
        ->add('shippingName', 'text', array('label' => 'Doprava'))
        ->add('shippingCode', 'text', array('label' => 'Doprava kód'))
        ->add('paymentName', 'text', array('label' => 'Platba'))
        ->add('paymentCode', 'text', array('label' => 'Platba kód'))
      ->end()
      ->with('Objednávka', array('class' => 'col-md-12'))
        ->add('items', CollectionType::class, [
            'label' => 'Položky objednávky',
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
      ->with('Stav objednávky', array('class' => 'col-md-12'))
        ->add('status', null, array('label' => 'Stav objednávky'))
        ->add('history', CollectionType::class, [
            'label' => 'Historie objednávky',
            'by_reference' => false,
            'type_options' => [
                // Prevents the "Delete" option from being displayed
                'delete' => false,
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
      ->add('created', null, array('label' => 'Vytvořeno',
          'pattern' => 'dd MMM y G',
          'locale' => 'cs',
          ))
      ->add('billingName', null, array('label' => 'Jméno'))
      ->add('billingCity', null, array('label' => 'Město'))
      ->add('status', null, array('label' => 'Stav objednávky', 'editable' => true))
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

  private $original = null;

  public function preUpdate($order) {

      $original = $this->om->getOriginalData($order);

      // dump($this->original); die();
      foreach ($order->getHistory() as $history)
      {
          if (is_null($history->getId()))
          {
             $order->setStatus($history->getStatus());
             if ($history->getEmail()) {
                 $this->om->sendUpdateStatusMail($order, $history->getStatus(), $history->getMessage());
             }
          }
      }

      parent::preUpdate($order); // TODO: Change the autogenerated stub
  }

  /**
   * Akce po zmene objednavky v adminu
   * @param object $order
  */
  /*public function postUpdate($order)
  {
      if ($order->getStatus()->getId() != $this->original['status_id'])
      {

      }

      parent::postUpdate($order);
  }*/

    /**
     * @param \AppBundle\Manager\OrderManager $om
     */
  public function setOrderManager($om)
  {
      $this->om = $om;
  }

  public function getOrderManager()
  {
      return $this->om;
  }

}