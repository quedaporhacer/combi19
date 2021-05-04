<?php

namespace App\Repository;

use App\Entity\Viaje;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Viaje|null find($id, $lockMode = null, $lockVersion = null)
 * @method Viaje|null findOneBy(array $criteria, array $orderBy = null)
 * @method Viaje[]    findAll()
 * @method Viaje[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ViajeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Viaje::class);
    }

    // /**
    //  * @return Viaje[] Returns an array of Viaje objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Viaje
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
