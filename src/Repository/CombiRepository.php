<?php

namespace App\Repository;

use App\Entity\Combi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Combi|null find($id, $lockMode = null, $lockVersion = null)
 * @method Combi|null findOneBy(array $criteria, array $orderBy = null)
 * @method Combi[]    findAll()
 * @method Combi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CombiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Combi::class);
    }

    // /**
    //  * @return Combi[] Returns an array of Combi objects
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
    public function findOneBySomeField($value): ?Combi
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
