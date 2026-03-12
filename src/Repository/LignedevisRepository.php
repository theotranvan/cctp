<?php
namespace App\Repository;
use App\Entity\Lignedevis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LignedevisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lignedevis::class);
    }

    public function getPrixMoyen(int $produitId, string $type, int $year): float
    {
        $result = $this->createQueryBuilder('l')
            ->select('AVG(s.prixUnitaire)')
            ->join('l.specification', 's')
            ->join('l.devis', 'd')
            ->where('l.produit = :produitId')
            ->andWhere('s.type = :type')
            ->andWhere('YEAR(d.createdAt) = :year')
            ->setParameter('produitId', $produitId)
            ->setParameter('type', $type)
            ->setParameter('year', $year)
            ->getQuery()
            ->getSingleScalarResult();

        return (float) ($result ?? 0);
    }
}
