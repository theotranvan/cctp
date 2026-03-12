<?php

namespace App\Repository;

use App\Entity\Installeur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Installeur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Installeur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Installeur[]    findAll()
 * @method Installeur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstalleurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Installeur::class);
    }

    // /**
    //  * @return Installeur[] Returns an array of Installeur objects
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
    public function findOneBySomeField($value): ?Installeur
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
