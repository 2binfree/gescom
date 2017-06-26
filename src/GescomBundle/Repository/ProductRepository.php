<?php

namespace GescomBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use GescomBundle\Tools\AbstractEntityRepository;
use GescomBundle\Tools\Navigator;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends EntityRepository
{
    const MAX_RESULT = 10;

    public function getProductById($id)
    {
        $query = $this->createQueryBuilder('p')
                      ->select("p, ps, s")
                      ->join("p.productSupplier", "ps")
                      ->join('ps.supplier', "s")
                      ->where("p.id = $id")
                      ->getQuery();
        return $query->getResult()[0];
    }

    /**
     * @param int $page
     * @return \Doctrine\ORM\Query
     */
    public function getRowsByPage(int $page = 1):Query
    {
        return $this->createQueryBuilder('p')
                      ->join("p.category", "c")
                      ->join("p.productSupplier", "ps")
                      ->join("ps.supplier", "s")
                      ->select("p, c, ps, s")
                      ->setFirstResult(($page - 1) * self::MAX_RESULT)
                      ->setMaxResults(self::MAX_RESULT)
                      ->getQuery();
    }
}
