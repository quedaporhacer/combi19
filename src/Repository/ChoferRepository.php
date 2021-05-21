<?php

namespace App\Repository;

use App\Entity\Chofer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Chofer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chofer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chofer[]    findAll()
 * @method Chofer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChoferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chofer::class);
    }

    // /**
    //  * @return Chofer[] Returns an array of Chofer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Chofer
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}