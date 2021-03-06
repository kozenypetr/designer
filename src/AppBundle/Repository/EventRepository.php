<?php

namespace AppBundle\Repository;

/**
 * EventRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllActive($locale = 'cs')
    {
        return $this->findBy(array('isActive' => true, 'locale' => 'cs'), array('sort' => 'ASC'));
    }
}
