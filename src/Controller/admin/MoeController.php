<?php

namespace App\Controller\admin;

use App\Entity\Moe;
use App\Form\MoeType;
use App\Repository\MoeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/moe")
 */
class MoeController extends AbstractController
{
    /**
     * @Route("/", name="moe_index", methods={"GET"})
     * fonction qui liste les moe
     */
    public function index(MoeRepository $moeRepository): Response
    {
        return $this->render('admin/moe/index.html.twig', [
            'moes' => $moeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="moe_new", methods={"GET","POST"})
     * fonction de création d'un moe
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        //instanciation d'un nouvel objet moe
        $moe = new Moe();
        //appel du from de création
        $form = $this->createForm(MoeType::class, $moe);
        //traitement des données saisies
        $form->handleRequest($request);
        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrement en bdd
            $entityManager = $doctrine->getManager();
            $entityManager->persist($moe);
            $entityManager->flush();

            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'moe crée avec succès');
            //redirection vers la page qui liste les moes
            return $this->redirectToRoute('moe_index', [], Response::HTTP_SEE_OTHER);
        }
        //affichage du formulaire
        return $this->renderForm('admin/moe/new.html.twig', [
            'moe' => $moe,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="moe_show", methods={"GET"})
     * fonction d'affichage des infos d'un moe
     */
    public function show(Moe $moe): Response
    {
        return $this->render('admin/moe/show.html.twig', [
            'moe' => $moe,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="moe_edit", methods={"GET","POST"})
     * fonction de mise à jour d'un moe
     */
    public function edit(Request $request, Moe $moe, ManagerRegistry $doctrine): Response
    {
        //appel du formulaire de création
        $form = $this->createForm(MoeType::class, $moe);
        //traitement des données saisies
        $form->handleRequest($request);
        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrement des modifications
            $doctrine->getManager()->flush();

            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'moe modifié avec succès');
            //redirection vers la page qui liste les moes
            return $this->redirectToRoute('moe_index', [], Response::HTTP_SEE_OTHER);
        }
        //affichage du formulaire
        return $this->renderForm('admin/moe/edit.html.twig', [
            'moe' => $moe,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="moe_delete", methods={"POST"})
     * fonction de suppression d'un moe par son id
     */
    public function delete(Request $request, Moe $moe, ManagerRegistry $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$moe->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($moe);
            $entityManager->flush();
        }
        //affichage d'un message si l'opération s'est déroulée avec succès
        $this->addFlash('message', 'moe supprimé avec succès');
        //redirection vers la page qui liste les moes
        return $this->redirectToRoute('moe_index', [], Response::HTTP_SEE_OTHER);
    }
}
