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

    public function save(Prevention $prevention): void
    {
        $this->getEntityManager()->persist($prevention);
        $this->getEntityManager()->flush();

    }

    public function delete(Prevention $prevention): void
    {
        $this->getEntityManager()->remove($prevention);
        $this->getEntityManager()->flush();
    }
}
