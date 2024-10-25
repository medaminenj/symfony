<?php

namespace App\Repository;

use App\Entity\Serveur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serveur>
 */
class ServeurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serveur::class);
    }


    public function findServeursByDateRange(\DateTime $date1, \DateTime $date2)
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.restaurant', 'r') 
            ->addSelect('r') 
            ->where('s.datenaissance BETWEEN :date1 AND :date2')
            ->setParameter('date1', $date1)
            ->setParameter('date2', $date2)
            ->orderBy('s.id', 'DESC')
            ->getQuery()
            ->getResult();
    }





//    /**
//     * @return Serveur[] Returns an array of Serveur objects
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

//    public function findOneBySomeField($value): ?Serveur
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
