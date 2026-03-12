<?php

namespace App\Repository;

use App\Entity\Devis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Devis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Devis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Devis[]    findAll()
 * @method Devis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DevisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Devis::class);
    }

    /**
     * Returns total price devis per installeur
     * 
     */
    public function totalPrice($idChantier){
        $query = $this->createQueryBuilder('d')
            ->select('count(l.id), e.nom_entreprise, sum(l.quantite * s.prix_unitaire) as prix_total')
            ->innerJoin('d.lignedevis', 'l', 'WITH', 'l.devis = d.id')
            ->innerJoin('d.entreprise', 'e', 'WITH', 'd.entreprise = e.id')
            ->innerJoin('d.chantier', 'c', 'WITH', 'd.chantier = c.id')
            ->innerJoin('l.specification', 's', 'WITH', 'l.specification = s.id')
            ->where('c.id = :idChantier')
            ->setParameter(':idChantier', $idChantier)
            ->groupBy('e.nom_entreprise')
            ;
            return $query->getQuery()->getResult();
        }
    // /**
    //  * @return Devis[] Returns an array of Devis objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Devis
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
