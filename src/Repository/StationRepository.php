<?php

namespace App\Repository;

use App\Entity\Station;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;


/**
 * @extends ServiceEntityRepository<Station>
 */
class StationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Station::class);
    }

    public function getStationNameById($idStation) //Récupère le nom d'une station par son ID
    {
        // Crée un QueryBuilder pour la classe Station
        return $this->createQueryBuilder("s")
            // Ajoute une condition pour filtrer par ID de la station
            ->andWhere("s.station_id = :idStation")
            // Définit le paramètre idStation avec la valeur fournie
            ->setParameter("idStation", $idStation)
            // Sélectionne uniquement le nom de la station
            ->select("s.name")
            // Exécute la requête et récupère les résultats sous forme de tableau
            ->getQuery()
            ->getArrayResult();
    }

    public function getLatLonById($idStation) //Récupère la latitude et la longitude d'une station par son ID
    {
        return $this->createQueryBuilder("s")
            ->andWhere("s.station_id = :idStation")
            ->setParameter("idStation", $idStation)
            ->select("s.lat", "s.lon")
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Station[] Returns an array of Station objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Station
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
