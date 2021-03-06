<?php

namespace App\GameBundle\Repository;

use App\CoreBundle\Repository\PaginationRepository;

/**
 * RoundRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RoundRepository extends PaginationRepository
{
    /**
     * Gets a paginated list of rounds of the given game.
     *
     * @param mixed  $id
     * @param int    $perPage
     * @param int    $page
     * @param string $order
     *
     * @return array
     */
    public function getByGame($id, $perPage = 15, $page = 1, $order = 'asc')
    {
        $qb = $this->createQueryBuilder('r')
            ->where('r.game = :gameId')
            ->setParameter('gameId', $id)
            ->orderBy('r.id', $order);

        $qb = $this->paginate($qb, $perPage, $page);

        return $qb->getQuery()->getResult();
    }
}
