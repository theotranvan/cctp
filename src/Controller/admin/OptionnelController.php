<?php

namespace App\Controller\admin;

use App\Entity\Optionnel;
use App\Form\OptionnelType;
use App\Repository\OptionnelRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/optionnel")
 * @package App\Controller\admin
 */
class OptionnelController extends AbstractController
{
    /**
     * @Route("/", name="optionnel_index", methods={"GET"})
     * fonction qui liste les options
     */
    public function index(OptionnelRepository $optionnelRepository): Response
    {
        return $this->render('admin/optionnel/index.html.twig', [
            'optionnels' => $optionnelRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="optionnel_new", methods={"GET","POST"})
     * fonction de création d'une option
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        //instanciation d'un nouvel objet optionnel
        $optionnel = new Optionnel();
        //appel du formulaire de création
        $form = $this->createForm(OptionnelType::class, $optionnel);
        //traitement des données saisies
        $form->handleRequest($request);
        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrement en bdd
            $entityManager = $doctrine->getManager();
            $entityManager->persist($optionnel);
            $entityManager->flush();

            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'option crée avec succès');
            //redirection vers la page qui liste les options
            return $this->redirectToRoute('optionnel_index', [], Response::HTTP_SEE_OTHER);
        }
        //affichage du formulaire
        return $this->renderForm('admin/optionnel/new.html.twig', [
            'optionnel' => $optionnel,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="optionnel_show", methods={"GET"})
     * fonction qui affiche les infos d'une option
     */
    public function show(Optionnel $optionnel): Response
    {
        return $this->render('admin/optionnel/show.html.twig', [
            'optionnel' => $optionnel,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="optionnel_edit", methods={"GET","POST"})
     * fonction de mise à jour d'une option
     */
    public function edit(Request $request, Optionnel $optionnel, ManagerRegistry $doctrine): Response
    {
        //appel du form de création
        $form = $this->createForm(OptionnelType::class, $optionnel);
        //traitement des données saisies
        $form->handleRequest($request);
        //si le form est soumis et valide 
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrement des modifications en bdd
            $doctrine->getManager()->flush();

            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'option modifiée avec succès');
            //redirection vers la page qui liste les options
            return $this->redirectToRoute('optionnel_index', [], Response::HTTP_SEE_OTHER);
        }
        //affichage du formulaire
        return $this->renderForm('admin/optionnel/edit.html.twig', [
            'optionnel' => $optionnel,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="optionnel_delete", methods={"POST"})
     * fonction de suppression d'une option par son id
     */
    public function delete(Request $request, Optionnel $optionnel, ManagerRegistry $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$optionnel->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($optionnel);
            $entityManager->flush();
        }
        //affichage d'un message si l'opération s'est déroulée avec succès
        $this->addFlash('message', 'option supprimée avec succès');
        //redirection vers la page qui liste les options
        return $this->redirectToRoute('optionnel_index', [], Response::HTTP_SEE_OTHER);
    }
}
