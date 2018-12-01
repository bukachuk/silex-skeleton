<?php
namespace Project\Entities\Repository;
use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    public function total()
    {
        $qb = $this->createQueryBuilder('c')->select('COUNT(c.id)');
        return $qb->getQuery()->getResult()->getScalarResult();
    }
}
