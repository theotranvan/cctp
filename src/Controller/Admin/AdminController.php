<?php
namespace App\Controller\Admin;

use App\Repository\CctpRepository;
use App\Repository\ProduitRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\DevisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('', name: 'admin_dashboard')]
    public function dashboard(
        CctpRepository $cctpRepo,
        ProduitRepository $produitRepo,
        EntrepriseRepository $entrepriseRepo,
        DevisRepository $devisRepo,
    ): Response {
        $recentCctps = $cctpRepo->findBy([], ['createdAt' => 'DESC'], 5);

        return $this->render('admin/dashboard.html.twig', [
            'stats' => [
                'cctps' => $cctpRepo->count([]),
                'cctps_open' => $cctpRepo->count(['close' => false]),
                'produits' => $produitRepo->count([]),
                'entreprises' => $entrepriseRepo->count([]),
                'devis' => $devisRepo->count([]),
            ],
            'recent_cctps' => $recentCctps,
        ]);
    }
}
