<?php

namespace App\Controller\admin;

use App\Entity\Installeur;
use App\Form\InstalleurType;
use App\Repository\InstalleurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/installeur")
 * @package App\Controller\admin
 */
class InstalleurController extends AbstractController
{
    /**
     * @Route("/", name="installeur_index", methods={"GET"})
     * fonction qui liste les installeurs
     */
    public function index(InstalleurRepository $installeurRepository): Response
    {
        return $this->render('admin/installeur/index.html.twig', [
            'installeurs' => $installeurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="installeur_new", methods={"GET","POST"})
     * fonction de création d'un installeur
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        //instanciation d'un nouvel objet installeur
        $installeur = new Installeur();
        //appel du form de création d'un installeur
        $form = $this->createForm(InstalleurType::class, $installeur);
        //traitement des données saisies
        $form->handleRequest($request);
        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrement en bdd
            $entityManager = $doctrine->getManager();
            $entityManager->persist($installeur);
            $entityManager->flush();

            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'installeur crée avec succès');
            //redirection vers la page qui liste les installeurs
            return $this->redirectToRoute('installeur_index', [], Response::HTTP_SEE_OTHER);
        }
        //affichage du formulaire
        return $this->renderForm('admin/installeur/new.html.twig', [
            'installeur' => $installeur,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="installeur_show", methods={"GET"})
     * fonction d'affichage des infos d'un installeur
     */
    public function show(Installeur $installeur): Response
    {
        return $this->render('admin/installeur/show.html.twig', [
            'installeur' => $installeur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="installeur_edit", methods={"GET","POST"})
     * fonction de mise à jour d'un installeur
     */
    public function edit(Request $request, Installeur $installeur, ManagerRegistry $doctrine): Response
    {
        //appel du form de création d'un installeur
        $form = $this->createForm(InstalleurType::class, $installeur);
        //traitement des données saisies
        $form->handleRequest($request);
        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrement des modifications en bdd
            $doctrine->getManager()->flush();

            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'installeur modifié avec succès');
            //redirection vers la page qui liste les installeurs
            return $this->redirectToRoute('installeur_index', [], Response::HTTP_SEE_OTHER);
        }
        //affichage du formulaire
        return $this->renderForm('admin/installeur/edit.html.twig', [
            'installeur' => $installeur,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="installeur_delete", methods={"POST"})
     * fonction de suppression d'un installeur par son id
     */
    public function delete(Request $request, Installeur $installeur, ManagerRegistry $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$installeur->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($installeur);
            $entityManager->flush();
        }
        //affichage d'un message si l'opération s'est déroulée avec succès
        $this->addFlash('message', 'installeur supprimé avec succès');
        //redirection vers la page qui liste les installeurs
        return $this->redirectToRoute('installeur_index', [], Response::HTTP_SEE_OTHER);
    }
}
