<?php

namespace App\Repository;

use Doctrine\ORM\Query\Expr\Join;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository as ParentRepository;

class ProductRepository extends ParentRepository
{
    public function findForExport(int $limit, int $offset): array
    {
        $query =  $this->createQueryBuilder('p')
            ->select(['p.code', 'p.enabled'])
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery();

        return $query->getResult();
    }
}
