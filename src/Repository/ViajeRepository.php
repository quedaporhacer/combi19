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

    public function findAllGreaterThanPrice(int $price): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT * FROM product p
            WHERE p.price > :price
            ORDER BY p.price ASC
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['price' => $price]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAllAssociative();
    }


    public function findbyRutaySalida($ruta,$form): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
        SELECT * FROM product p
        WHERE p.price > :price
        ORDER BY p.price ASC
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['price' => $price]);

    // returns an array of arrays (i.e. a raw data set)
    return $stmt->fetchAllAssociative();
    }

}
