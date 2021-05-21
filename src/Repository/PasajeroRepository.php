<?php

namespace App\Repository;

use App\Entity\Pasajero;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pasajero|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pasajero|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pasajero[]    findAll()
 * @method Pasajero[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PasajeroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pasajero::class);
    }

    // /**
    //  * @return Pasajero[] Returns an array of Pasajero objects
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
    public function findOneBySomeField($value): ?Pasajero
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
