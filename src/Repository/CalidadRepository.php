<?php

namespace App\Repository;

use App\Entity\Calidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Calidad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Calidad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Calidad[]    findAll()
 * @method Calidad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalidadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Calidad::class);
    }

    // /**
    //  * @return Calidad[] Returns an array of Calidad objects
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
    public function findOneBySomeField($value): ?Calidad
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
