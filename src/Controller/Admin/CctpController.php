<?php

namespace App\Controller\Admin;

use App\Entity\Cctp;
use App\Form\CctpType;
use App\Repository\CctpRepository;
use App\Repository\ProduitRepository;
use App\Repository\SystemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/cctp')]
#[IsGranted('ROLE_ADMIN')]
class CctpController extends AbstractController
{
    #[Route('', name: 'cctp_index', methods: ['GET'])]
    public function index(CctpRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $repo->createQueryBuilder('c')
            ->orderBy('c.numAffaire', 'ASC')
            ->getQuery();

        $cctps = $paginator->paginate($query, $request->query->getInt('page', 1), 15);

        return $this->render('admin/cctp/index.html.twig', ['cctps' => $cctps]);
    }

    #[Route('/new', name: 'cctp_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $cctp = new Cctp();
        $form = $this->createForm(CctpType::class, $cctp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cctp->setUtilisateur($this->getUser());
            $cctp->setClose(false);
            $em->persist($cctp);
            $em->flush();
            $this->addFlash('message', 'CCTP créé avec succès.');
            return $this->redirectToRoute('cctp_show', ['id' => $cctp->getId()]);
        }

        return $this->render('admin/cctp/new.html.twig', ['form' => $form, 'cctp' => $cctp]);
    }

    #[Route('/{id}', name: 'cctp_show', methods: ['GET'])]
    public function show(Cctp $cctp, SystemeRepository $systemeRepo): Response
    {
        return $this->render('admin/cctp/show.html.twig', [
            'cctp' => $cctp,
            'systemes' => $systemeRepo->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'cctp_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cctp $cctp, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CctpType::class, $cctp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('message', 'CCTP modifié avec succès.');
            return $this->redirectToRoute('cctp_show', ['id' => $cctp->getId()]);
        }

        return $this->render('admin/cctp/edit.html.twig', ['form' => $form, 'cctp' => $cctp]);
    }

    #[Route('/{id}/toggle', name: 'cctp_toggle', methods: ['POST'])]
    public function toggle(Request $request, Cctp $cctp, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('toggle' . $cctp->getId(), $request->getPayload()->getString('_token'))) {
            $cctp->setClose(!$cctp->isClose());
            $em->flush();
            $status = $cctp->isClose() ? 'clôturé' : 'réouvert';
            $this->addFlash('message', "CCTP $status.");
        }
        return $this->redirectToRoute('cctp_show', ['id' => $cctp->getId()]);
    }

    #[Route('/{id}/add-produit', name: 'cctp_add_produit', methods: ['POST'])]
    public function addProduit(Request $request, Cctp $cctp, ProduitRepository $produitRepo, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('add_produit_' . $cctp->getId(), $request->getPayload()->getString('_token'))) {
            $produitIds = $request->request->all('produits');
            if (!empty($produitIds)) {
                foreach ($produitIds as $produitId) {
                    $produit = $produitRepo->find((int) $produitId);
                    if ($produit && !$cctp->getProduits()->contains($produit)) {
                        $cctp->addProduit($produit);
                    }
                }
                $em->flush();
                $this->addFlash('message', count($produitIds) . ' produit(s) ajouté(s) au CCTP.');
            }
        }
        return $this->redirectToRoute('cctp_show', ['id' => $cctp->getId()]);
    }

    #[Route('/{id}/remove-produit/{produitId}', name: 'cctp_remove_produit', methods: ['POST'])]
    public function removeProduit(Request $request, Cctp $cctp, int $produitId, ProduitRepository $produitRepo, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('remove_produit_' . $produitId, $request->getPayload()->getString('_token'))) {
            $produit = $produitRepo->find($produitId);
            if ($produit) {
                $cctp->removeProduit($produit);
                $em->flush();
                $this->addFlash('message', 'Produit retiré du CCTP.');
            }
        }
        return $this->redirectToRoute('cctp_show', ['id' => $cctp->getId()]);
    }

    #[Route('/{id}/delete', name: 'cctp_delete', methods: ['POST'])]
    public function delete(Request $request, Cctp $cctp, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $cctp->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($cctp);
            $em->flush();
            $this->addFlash('message', 'CCTP supprimé.');
        }
        return $this->redirectToRoute('cctp_index');
    }
}
