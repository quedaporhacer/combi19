<?php

namespace App\Repository;

use App\Entity\Viaje;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;


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


    public function findbyRutaySalida($origen,$destino,$salida): array
    {
       $conn = $this->getEntityManager()->getConnection();
        $sql = '
        SELECT DISTINCT v.id FROM viaje v 
        INNER JOIN ruta r ON(v.ruta_id = r.id)
        INNER JOIN lugar lo ON(r.origen_id = lo.id)
        INNER JOIN lugar ld ON(r.destino_id = ld.id)
        WHERE lo.nombre LIKE :origen  AND ld.nombre LIKE :destino 
            AND year(v.salida) = :ano
            AND month(v.salida) = :mes
            AND day(v.salida) = :dia
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'origen' => ("%".$origen."%"),
            'destino' => ("%".$destino."%"),
            'ano' =>$salida->format('Y'),
            'mes' =>$salida->format('m'),
            'dia' =>$salida->format('d'),
        ));
        

    // returns an array of arrays (i.e. a raw data set)
    return $stmt->fetchAllAssociative();

  /*  $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT DISTINCT v.id 
            FROM App\Entity\Viaje v 
            INNER JOIN App\Entity\Ruta r ON v.ruta_id = r.id
            INNER JOIN App\Entity\Lugar lo ON r.origen_id = lo.id
            INNER JOIN App\Entity\Lugar ld ON r.destino_id = ld.id
            WHERE lo.nombre = :origen AND ld.nombre = :destino'
        )->setParameters(array(
            'origen' => $origen,
            'destino' => $destino,
        ));

        // returns an array of Product objects
        return $query->getResult();*/
    }

    public function findByRuta($origen,$destino,$salida): array
    {
        $qb = $this->createQueryBuilder('v')
        ->innerJoin('v.ruta','r')
        ->innerJoin('r.origen','o')
        ->innerJoin('r.destino','d')
        ->where('o.nombre = :origen')
        ->andWhere('d.nombre = :destino')
        ->andWhere("DATE_FORMAT(v.salida, '%Y-%m-%d') = :salida")
        ->setParameters(new ArrayCollection([
            new Parameter('origen', $origen),
            new Parameter('destino', $destino),
            new Parameter('salida', $salida->format('Y-m-d'))
            ]));
        return $qb->getQuery()->execute();
    }

    public function ultimoViajeDe($combi): Viaje
    {
        $offset = 0;
        $limit = 1;
        $qb = $this->createQueryBuilder('v')
        ->where('v.combi = :combi')
        ->andWhere("v.estado LIKE 'No iniciado'")
        ->orderBy('v.salida', 'ASC')
        ->setFirstResult($offset)
        ->setMaxResults($limit)
        ->setParameters(new ArrayCollection([
            new Parameter('combi', $combi->getId())
            ]));
        return $qb->getQuery()->execute()['0'];
    }

}

