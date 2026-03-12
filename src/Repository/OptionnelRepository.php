<?php

namespace App\Repository;

use App\Entity\Optionnel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Optionnel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Optionnel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Optionnel[]    findAll()
 * @method Optionnel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OptionnelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Optionnel::class);
    }

    // /**
    //  * @return Optionnel[] Returns an array of Optionnel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    /**
     * @return Optionnel[] Returns an array of Optionnel objects
     */
    public function findByCategorie($value)
    {
        return $this->createQueryBuilder('o')
            ->select('o','o.title')
            ->where('o.categorie = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Optionnel
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
