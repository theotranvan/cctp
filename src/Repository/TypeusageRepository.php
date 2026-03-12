<?php

namespace App\Repository;

use App\Entity\Typeusage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Typeusage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Typeusage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Typeusage[]    findAll()
 * @method Typeusage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeusageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Typeusage::class);
    }

    // /**
    //  * @return Typeusage[] Returns an array of Typeusage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Typeusage
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
