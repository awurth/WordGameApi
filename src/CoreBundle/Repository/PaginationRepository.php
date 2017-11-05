<?php

namespace App\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class PaginationRepository extends EntityRepository
{
    /**
     * Sets limit and offset on a query builder.
     *
     * @param QueryBuilder $queryBuilder
     * @param int $perPage
     * @param int $page
     *
     * @return QueryBuilder
     */
    public function paginate(QueryBuilder $queryBuilder, $perPage = 15, $page = 1)
    {
        if ($perPage < 1) {
            $perPage = 15;
        }

        if ($page < 1) {
            $page = 1;
        }

        return $queryBuilder
            ->setFirstResult($perPage * $page - $perPage)
            ->setMaxResults($perPage);
    }
}
