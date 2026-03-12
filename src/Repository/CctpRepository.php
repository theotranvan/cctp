<?php

namespace App\Repository;

use App\Entity\Cctp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cctp|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cctp|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cctp[]    findAll()
 * @method Cctp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CctpRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cctp::class);
    }

    // /**
    //  * @return Cctp[] Returns an array of Cctp objects
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
    public function findOneBySomeField($value): ?Cctp
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
