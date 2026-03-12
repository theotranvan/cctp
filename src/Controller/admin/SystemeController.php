<?php

namespace App\Controller\admin;

use App\Entity\Systeme;
use App\Form\SystemeType;
use App\Repository\SystemeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/systeme")
 * @package App\Controller\admin
 */
class SystemeController extends AbstractController
{
    /**
     * @Route("/", name="systeme_index", methods={"GET"})
     * fonction qui liste les systèmes
     */
    public function index(SystemeRepository $systemeRepository): Response
    {
        return $this->render('admin/systeme/index.html.twig', [
            'systemes' => $systemeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="systeme_new", methods={"GET","POST"})
     * fonction de création d'un système
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        //instanciation d'un nouvel objet systeme
        $systeme = new Systeme();
        //appel du form de création
        $form = $this->createForm(SystemeType::class, $systeme);
        //traitement des données saisies
        $form->handleRequest($request);
        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrement en bdd
            $entityManager = $doctrine->getManager();
            $entityManager->persist($systeme);
            $entityManager->flush();

            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'Ce systeme est inséré avec succès');
            //redirection vers la page qui liste les systemes
            return $this->redirectToRoute('systeme_index', [], Response::HTTP_SEE_OTHER);
        }
        //affichage du formulaire
        return $this->renderForm('admin/systeme/new.html.twig', [
            'systeme' => $systeme,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="systeme_show", methods={"GET"})
     * fonction d'affichage des infos d'un système
     */
    public function show(Systeme $systeme): Response
    {
        return $this->render('admin/systeme/show.html.twig', [
            'systeme' => $systeme,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="systeme_edit", methods={"GET","POST"})
     * fonction de mise à jour d'un système
     */
    public function edit(Request $request, Systeme $systeme, ManagerRegistry $doctrine): Response
    {
        //appel du form de création
        $form = $this->createForm(SystemeType::class, $systeme);
        //traitement des données saisies
        $form->handleRequest($request);
        //si le form est soumis et valide 
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrement des modifications en bdd
            $doctrine->getManager()->flush();

            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'Mise à jour effectuée avec succès');
            //redirection vers la page qui liste les systèmes
            return $this->redirectToRoute('systeme_index', [], Response::HTTP_SEE_OTHER);
        }
        //affichage du formulaire
        return $this->renderForm('admin/systeme/edit.html.twig', [
            'systeme' => $systeme,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="systeme_delete", methods={"POST"})
     * fonction de suppression d'un système par son id
     */
    public function delete(Request $request, Systeme $systeme, ManagerRegistry $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$systeme->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($systeme);
            $entityManager->flush();
        }
        //affichage d'un message si l'opération s'est déroulée avec succès
        $this->addFlash('message', 'système supprimé avec succès');
        //redirection vers la page qui liste les systèmes
        return $this->redirectToRoute('systeme_index', [], Response::HTTP_SEE_OTHER);
    }
}
