<?php

namespace App\Repository;

use App\Entity\Lignedevis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lignedevis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lignedevis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lignedevis[]    findAll()
 * @method Lignedevis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LignedevisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lignedevis::class);
    }

    public function moyenneAnnuelle(string $idProd, $nomType = null)
    {
        $query = $this->createQueryBuilder('l')
        ->select('AVG(s.prix_unitaire), SUBSTRING(d.created_At,1,4) as year')
        ->innerJoin('l.specification', 's', 'WITH', 'l.specification = s.id')
        ->innerJoin('l.devis', 'd', 'WITH', 'l.devis = d.id')
        ->where('s.produit = :idProd')
        ->setParameter(':idProd', $idProd);
        if($nomType != null){
            $query->andWhere('s.type = :type')
            ->setParameter(':type', $nomType);
        };
        $query->groupBy('year')
        ->orderBy('year', 'DESC');

        return $query->getQuery()->getResult();
    }

    public function prixMoyen(string $idProd, $nomType = null, $year)
    {
        $query = $this->createQueryBuilder('l')
        ->select('AVG(s.prix_unitaire)')
        ->innerJoin('l.specification', 's', 'WITH', 'l.specification = s.id')
        ->innerJoin('l.devis', 'd', 'WITH', 'l.devis = d.id')
        ->where('SUBSTRING(d.created_At,1,4) = :year')
        ->setParameter(':year', $year)
        ->andWhere('s.produit = :idProd')
        ->setParameter(':idProd', $idProd);
        if($nomType != null){
            $query->andWhere('s.type = :type')
            ->setParameter(':type', $nomType);
        };

        return $query->getQuery()->getResult();
    }

    public function filtre($idProd, $type)
    {
        $query = $this->createQueryBuilder('l')
        
        ->innerJoin('l.specification', 's', 'WITH', 'l.specification = s.id')
        ->where('s.produit = :idProd')
        ->setParameter(':idProd', $idProd)
        ->andWhere('s.type = :type')
        ->setParameter(':type', $type);
        
        return $query->getQuery()->getResult();
    }
    // /**
    //  * @return Lignedevis[] Returns an array of Lignedevis objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Lignedevis
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
