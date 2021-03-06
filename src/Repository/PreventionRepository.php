<?php

namespace App\Repository;

use App\Entity\Prevention;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Prevention|null find($id, $lockMode = null, $lockVersion = null)
 * @method Prevention|null findOneBy(array $criteria, array $orderBy = null)
 * @method Prevention[]    findAll()
 * @method Prevention[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PreventionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prevention::class);
    }

    // /**
    //  * @return Prevention[] Returns an array of Prevention objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Prevention
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
