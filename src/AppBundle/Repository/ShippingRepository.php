<?php

namespace AppBundle\Repository;

/**
 * ShippingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ShippingRepository extends \Doctrine\ORM\EntityRepository
{
    public function findActive($locale = 'cs')
    {
        $qb = $this->createQueryBuilder('s')
            ->andWhere('s.isActive = 1')
            ->andWhere('s.locale = :locale')
            ->setParameter('locale', $locale)
            ->orderBy('s.sort', 'ASC')
            ->getQuery();

        return $qb->execute();
    }
}
