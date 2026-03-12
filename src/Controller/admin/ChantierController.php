<?php

namespace App\Controller\admin;

use App\Entity\Chantier;
use App\Form\ChantierType;
use App\Repository\ChantierRepository;
use App\Repository\DevisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @Route("admin/chantier")
 * @package App\Controller\admin
 */
class ChantierController extends AbstractController
{
    /**
     * @Route("/", name="chantier_index", methods={"GET"})
     * fonction qui liste les chantiers
     */
    public function index(ChantierRepository $chantierRepository): Response
    {
        return $this->render('admin/chantier/index.html.twig', [
            'chantiers' => $chantierRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="chantier_new", methods={"GET","POST"})
     * fonction de création d'un chantier
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        //instanciation d'un nouvel objet chantier
        $chantier = new Chantier();
        //récupération du form de création d'un chantier
        $form = $this->createForm(ChantierType::class, $chantier);
        //traitement des données saisies
        $form->handleRequest($request);
        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrement en bdd
            $entityManager = $doctrine->getManager();
            $entityManager->persist($chantier);
            $entityManager->flush();
            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'Chantier crée avec succès');
            //redirection vers la page qui liste les chantiers
            return $this->redirectToRoute('chantier_index', [], Response::HTTP_SEE_OTHER);
        }
        //affichage du form
        return $this->renderForm('admin/chantier/new.html.twig', [
            'chantier' => $chantier,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="chantier_show", methods={"GET"})
     * fonction qui affiche les informations d'un chantier
     */
    public function show(Chantier $chantier, DevisRepository $devisRepo): Response
    {
        $totalP = $devisRepo->totalPrice($chantier->getId());

        $nbElem = [];
        $nomInstal = [];
        $prixT = [];

        foreach($totalP as $totP){
            $nbElem[] = $totP['1'];
            $nomInstal[] = $totP['nom_entreprise'];
            $prixT[] = $totP['prix_total'];
        };
        //dd($totalP);
        return $this->render('admin/chantier/show.html.twig', [
            'chantier' => $chantier,
            'nbElem' => json_encode($nbElem),
            'nomInstal' => json_encode($nomInstal),
            'prixT' => json_encode($prixT)

        ]);
    }

    /**
     * @Route("/{id}/edit", name="chantier_edit", methods={"GET","POST"})
     * fonction de mise à jour d'un chantier
     */
    public function edit(Request $request, Chantier $chantier, ManagerRegistry $doctrine): Response
    {
        //appel du form de création d'un chantier
        $form = $this->createForm(ChantierType::class, $chantier);
        //traitement des données saisies
        $form->handleRequest($request);
        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrement des modifications en bdd
            $doctrine->getManager()->flush();
            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'Mise à jour effectuée avec succès');
            //redirection vers la page qui liste les chantiers
            return $this->redirectToRoute('chantier_index', [], Response::HTTP_SEE_OTHER);
        }
        //affichage du form
        return $this->renderForm('admin/chantier/edit.html.twig', [
            'chantier' => $chantier,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="chantier_delete", methods={"POST"})
     * fonction de suppression d'un chantier par son id
     */
    public function delete(Request $request, Chantier $chantier, ManagerRegistry $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chantier->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($chantier);
            $entityManager->flush();
        }
        //affichage d'un message si l'opération s'est déroulée avec succès
        $this->addFlash('message', 'Chantier supprimé avec succès');
        //redirection vers la page qui liste les chantiers
        return $this->redirectToRoute('chantier_index', [], Response::HTTP_SEE_OTHER);
    }
}
