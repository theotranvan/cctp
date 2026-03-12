<?php

namespace App\Controller\admin;

use App\Entity\Moa;
use App\Form\MoaType;
use App\Repository\MoaRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/moa")
 */
class MoaController extends AbstractController
{
    /**
     * @Route("/", name="moa_index", methods={"GET"})
     * fonction qui liste les moas
     */
    public function index(MoaRepository $moaRepository): Response
    {
        return $this->render('admin/moa/index.html.twig', [
            'moas' => $moaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="moa_new", methods={"GET","POST"})
     * fonction de création d'un moa
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        //instanciation d'un nouvel objet moa
        $moa = new Moa();
        //apple du formulaire de création
        $form = $this->createForm(MoaType::class, $moa);
        //traitement des données saisies
        $form->handleRequest($request);
        //si le form est soumis et vaide
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrement en bdd
            $entityManager = $doctrine->getManager();
            $entityManager->persist($moa);
            $entityManager->flush();

            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'moa crée avec succès');
            //redirection vers la page qui liste les moas
            return $this->redirectToRoute('moa_index', [], Response::HTTP_SEE_OTHER);
        }
        //affichage du formulaire
        return $this->renderForm('admin/moa/new.html.twig', [
            'moa' => $moa,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="moa_show", methods={"GET"})
     * fonction d'affichage des infos d'un moa
     */
    public function show(Moa $moa): Response
    {
        return $this->render('admin/moa/show.html.twig', [
            'moa' => $moa,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="moa_edit", methods={"GET","POST"})
     * fonction de mise à jour d'un moa
     */
    public function edit(Request $request, Moa $moa, ManagerRegistry $doctrine): Response
    {
        //appel du formulaire de création
        $form = $this->createForm(MoaType::class, $moa);
        //traitement des données saisies
        $form->handleRequest($request);
        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrement des modifications en bdd
            $doctrine->getManager()->flush();

            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'moa modifié avec succès');
            //redirection vers la page qui liste les moas
            return $this->redirectToRoute('moa_index', [], Response::HTTP_SEE_OTHER);
        }
        //affichage du formulaire
        return $this->renderForm('admin/moa/edit.html.twig', [
            'moa' => $moa,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="moa_delete", methods={"POST"})
     * fonction de suppression d'un moa par on id
     */
    public function delete(Request $request, Moa $moa, ManagerRegistry $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$moa->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($moa);
            $entityManager->flush();
        }

        //affichage d'un message si l'opération s'est déroulée avec succès
        $this->addFlash('message', 'moa supprimé avec succès');
        //redirection vers la page qui liste les moas
        return $this->redirectToRoute('moa_index', [], Response::HTTP_SEE_OTHER);
    }
}
