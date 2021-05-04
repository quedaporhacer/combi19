<?php

namespace App\Repository;

use App\Entity\Imprevisto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Imprevisto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Imprevisto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Imprevisto[]    findAll()
 * @method Imprevisto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImprevistoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Imprevisto::class);
    }

    // /**
    //  * @return Imprevisto[] Returns an array of Imprevisto objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Imprevisto
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
