<?php

namespace App\Controller\admin;

use App\Entity\Lot;
use App\Form\LotType;
use App\Repository\LotRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/lot")
 * @package App\Controller\admin
 */
class LotController extends AbstractController
{
    /**
     * @Route("/", name="lot_index", methods={"GET"})
     * fonction qui liste les lots
     */
    public function index(LotRepository $lotRepository): Response
    {
        return $this->render('admin/lot/index.html.twig', [
            'lots' => $lotRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="lot_new", methods={"GET","POST"})
     * fonction de création d'un lot
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        //instanciation d'un nouvel objet lot 
        $lot = new Lot();
        //appel du form de création
        $form = $this->createForm(LotType::class, $lot);
        //traitement des données saisies
        $form->handleRequest($request);
        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrement en bdd
            $entityManager = $doctrine->getManager();
            $entityManager->persist($lot);
            $entityManager->flush();
            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'lot crée avec succès');
            //redirection vers la page qui liste les lots
            return $this->redirectToRoute('lot_index', [], Response::HTTP_SEE_OTHER);
        }
        //affichage du formulaire
        return $this->renderForm('admin/lot/new.html.twig', [
            'lot' => $lot,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="lot_show", methods={"GET"})
     * fonction d'affichage des infos d'un lot par son id
     */
    public function show(Lot $lot): Response
    {
        return $this->render('admin/lot/show.html.twig', [
            'lot' => $lot,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="lot_edit", methods={"GET","POST"})
     * fonction de mise à jour d'un lot
     */
    public function edit(Request $request, Lot $lot, ManagerRegistry $doctrine): Response
    {
        //appel du form de création
        $form = $this->createForm(LotType::class, $lot);
        //traitement des données saisies
        $form->handleRequest($request);
        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrement des modifications en bdd
            $doctrine->getManager()->flush();
            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'lot modifié avec succès');
            //redirection vers la page qui liste les lots
            return $this->redirectToRoute('lot_index', [], Response::HTTP_SEE_OTHER);
        }
        //affichage du formulaire 
        return $this->renderForm('admin/lot/edit.html.twig', [
            'lot' => $lot,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="lot_delete", methods={"POST"})
     * fonction de suppression d'un lot par son id
     */
    public function delete(Request $request, Lot $lot, ManagerRegistry $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lot->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($lot);
            $entityManager->flush();
        }
        //affichage d'un message si l'opération s'est déroulée avec succès
        $this->addFlash('message', 'lot supprimé avec succès');
        //redirection vers la page qui liste les lots
        return $this->redirectToRoute('lot_index', [], Response::HTTP_SEE_OTHER);
    }
}
