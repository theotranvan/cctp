<?php

namespace App\Controller\admin;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\LotRepository;
use App\Repository\ProduitRepository;
use App\Repository\SystemeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/produit", name="produit_")
 * @package App\Controller\admin
 */
class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * fonction qui liste les éléments
     */
    public function index(ProduitRepository $produitRepository, LotRepository $lotRepository, SystemeRepository $systemeRepository, Request $request): Response
    {
        
        return $this->render('admin/produit/index2.html.twig', [
        'produits' => $produitRepository->getProdOrder(/*[], ['ordre' => 'ASC']*/),
            //'lots' => $lotRepository->findAll(),
            //'systemes' => $systemeRepository->findAll(),
            'request' => $request
        ]);
    }

    /**
     * @Route("/details/{id}", name="details")
     * fonction qui affiche le document lié a l'élément
     */
    public function details(Produit $produit)
    {
        return $this->render('admin/produit/details.html.twig',[
            'produit' => $produit
        ]);

    }

    /**
    * Resorts an item using it's doctrine sortable property
    * @param integer $id
    * @param integer $ordre
    * @return \Symfony\Component\HttpFoundation\Response
    * @Route("/sort/{id}/{ordre}", name="admin_produit_sort")
    * méthode de réorganisation de l'ordre d'affichage des éléments
    */
    public function sortAction($id, $ordre, ProduitRepository $produitRepo, LotRepository $lotRepository, SystemeRepository $systemeRepository, ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $produit = $produitRepo->find($id);
        $produit->setOrdre($ordre);
        $em->persist($produit);
        $em->flush();
        $request = new Request();
        return $this->index($produitRepo, $lotRepository, $systemeRepository, $request);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     * fonction de création d'un produit
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        //instanciation d'un nouvel objet produit
        $produit = new Produit();
        //appel du form de création
        $form = $this->createForm(ProduitType::class, $produit);
        //traitement des données saisies
        $form->handleRequest($request);
        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrement en bdd
            $entityManager = $doctrine->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();
            
            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'Cet élément est inséré avec succès');
            //redirection vers la page qui liste les produits
            return $this->redirectToRoute('produit_index', [], Response::HTTP_SEE_OTHER);
        }
        //affichage du formulaire
        return $this->renderForm('admin/produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * fonction de mise à jour d'un produit
     */
    public function edit(Request $request, Produit $produit, ManagerRegistry $doctrine): Response
    {
        //appel du form de création
        $form = $this->createForm(ProduitType::class, $produit);
        //traitement des données saisies
        $form->handleRequest($request);
        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrement des modifications en bdd
            $doctrine->getManager()->flush();

            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'Mise à jour effectuée avec succès');
            //redirection vers la page qui liste les produits
            return $this->redirectToRoute('produit_index', [], Response::HTTP_SEE_OTHER);
        }
        //affichage du formulaire
        return $this->renderForm('admin/produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     * fonction de suppression d'un produit par son id
     */
    public function delete(Request $request, Produit $produit, ManagerRegistry $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($produit);
            $entityManager->flush();
        }
        //affichage d'un message si l'opération s'est déroulée avec succès
        $this->addFlash('message', 'Elément supprimé avec succès');
        //redirection vers la page qui liste les produits
        return $this->redirectToRoute('produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
