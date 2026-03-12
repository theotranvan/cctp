<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\LotRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/produit')]
#[IsGranted('ROLE_ADMIN')]
class ProduitController extends AbstractController
{
    #[Route('', name: 'produit_index', methods: ['GET'])]
    public function index(ProduitRepository $repo, LotRepository $lotRepo, PaginatorInterface $paginator, Request $request): Response
    {
        $lotId = $request->query->getInt('lot');
        $search = $request->query->getString('q');

        $qb = $repo->createQueryBuilder('p')
            ->leftJoin('p.lot', 'l')
            ->leftJoin('p.systeme', 's')
            ->orderBy('l.nomLot', 'ASC')
            ->addOrderBy('p.ordre', 'ASC');

        if ($lotId) {
            $qb->andWhere('p.lot = :lot')->setParameter('lot', $lotId);
        }
        if ($search) {
            $qb->andWhere('p.nomProduit LIKE :q OR p.title LIKE :q')->setParameter('q', '%' . $search . '%');
        }

        $produits = $paginator->paginate($qb->getQuery(), $request->query->getInt('page', 1), 20);

        return $this->render('admin/produit/index.html.twig', [
            'produits' => $produits,
            'lots' => $lotRepo->findBy([], ['nomLot' => 'ASC']),
            'current_lot' => $lotId,
            'search' => $search,
        ]);
    }

    #[Route('/new', name: 'produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($produit);
            $em->flush();
            $this->addFlash('message', 'Produit créé avec succès.');
            return $this->redirectToRoute('produit_show', ['id' => $produit->getId()]);
        }

        return $this->render('admin/produit/new.html.twig', ['form' => $form, 'produit' => $produit]);
    }

    #[Route('/{id}', name: 'produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('admin/produit/show.html.twig', ['produit' => $produit]);
    }

    #[Route('/{id}/edit', name: 'produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('message', 'Produit modifié avec succès.');
            return $this->redirectToRoute('produit_show', ['id' => $produit->getId()]);
        }

        return $this->render('admin/produit/edit.html.twig', ['form' => $form, 'produit' => $produit]);
    }

    #[Route('/{id}/delete', name: 'produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($produit);
            $em->flush();
            $this->addFlash('message', 'Produit supprimé.');
        }
        return $this->redirectToRoute('produit_index');
    }
}
