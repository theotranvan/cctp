<?php

namespace App\Repository;

use App\Entity\Moa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Moa|null find($id, $lockMode = null, $lockVersion = null)
 * @method Moa|null findOneBy(array $criteria, array $orderBy = null)
 * @method Moa[]    findAll()
 * @method Moa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Moa::class);
    }

    // /**
    //  * @return Moa[] Returns an array of Moa objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Moa
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
