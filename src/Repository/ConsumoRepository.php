<?php

namespace App\Repository;

use App\Entity\Consumo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Consumo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Consumo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Consumo[]    findAll()
 * @method Consumo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsumoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Consumo::class);
    }

    // /**
    //  * @return Consumo[] Returns an array of Consumo objects
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
    public function findOneBySomeField($value): ?Consumo
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
