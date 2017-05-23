<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CommandRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommandRepository extends EntityRepository
{
    public function countTicketsByDays (string $date)
    {
        $query = $this->_em->createQueryBuilder()
            ->select('SUM(c.ticketsBought)')
            ->from('AppBundle:Command', 'c')
            ->where('c.visitDay = :date')
            ->setParameter('date', $date)
        ;

        return $query->getQuery()->getSingleScalarResult();
    }
}
