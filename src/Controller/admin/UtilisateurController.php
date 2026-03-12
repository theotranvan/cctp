<?php

namespace App\Controller\admin;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/utilisateur")
 * @package App\Controller\admin
 */
class UtilisateurController extends AbstractController
{
    /**
     * @Route("/", name="utilisateur_index", methods={"GET"})
     * fonction qui liste les utilisateurs
     */
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('admin/utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="utilisateur_new", methods={"GET","POST"})
     * fonction de création d'un utilisateur
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        //instanciation d'un nouvel objet utilisateur
        $utilisateur = new Utilisateur();
        //appel du form de création
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        //traitement des données saisies
        $form->handleRequest($request);
        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrement en bdd
            $entityManager = $doctrine->getManager();
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'utilisateur crée avec succès');
            //redirection vers la page qui liste les utilisateurs
            return $this->redirectToRoute('utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }
        //affichage du formulaire
        return $this->renderForm('admin/utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="utilisateur_show", methods={"GET"})
     * fonction d'affichage des infos d'un utilisateur
     */
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('admin/utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="utilisateur_edit", methods={"GET","POST"})
     * fonction de mise à jour d'un utilisteur
     */
    public function edit(Request $request, Utilisateur $utilisateur, ManagerRegistry $doctrine): Response
    {
        //appel du formulaire de création
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        //traitement des données saisies
        $form->handleRequest($request);
        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrement des modifications en bdd
            $doctrine->getManager()->flush();

            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'Mise à jour effectuée avec succès');
            //redirection vers la page qui liste les utilisateurs
            return $this->redirectToRoute('utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }
        //affichage du formulaire
        return $this->renderForm('admin/utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="utilisateur_delete", methods={"POST"})
     * fonction de suppression d'un utilisateur par son id
     */
    public function delete(Request $request, Utilisateur $utilisateur, ManagerRegistry $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }
        //affichage d'un message si l'opération s'est déroulée avec succès
        $this->addFlash('message', 'utilisateur supprimé avec succès');
        //redirection vers la page qui liste les utilisateurs
        return $this->redirectToRoute('utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }
}