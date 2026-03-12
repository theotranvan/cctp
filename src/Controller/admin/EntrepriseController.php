<?php

namespace App\Controller\admin;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/entreprise")
 */
class EntrepriseController extends AbstractController
{
    /**
     * @Route("/", name="entreprise_index", methods={"GET"})
     * fonction qui liste les entreprises
     */
    public function index(EntrepriseRepository $entrepriseRepository): Response
    {
        
        return $this->render('admin/entreprise/index.html.twig', [
            'entreprises' => $entrepriseRepository->findBy([], ['nom_entreprise' => 'ASC']),
        ]);
    }

    /**
     * @Route("/new", name="entreprise_new", methods={"GET","POST"})
     * fonction de création d'une entreprise
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        //instantiation d'un nouvel objet entreprise
        $entreprise = new Entreprise();
        //appel du form de création d'une entreprise
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        //traitement des données saisies
        $form->handleRequest($request);
        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrement en bdd
            $entityManager = $doctrine->getManager();
            $entityManager->persist($entreprise);
            $entityManager->flush();

            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'entreprise crée avec succès');
            //redirection vers la page qui liste les entreprises
            return $this->redirectToRoute('entreprise_index', [], Response::HTTP_SEE_OTHER);
        }
        //affichage du formulaire
        return $this->renderForm('admin/entreprise/new.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="entreprise_show", methods={"GET"})
     * fonction d'affichage des infos d'une entreprise
     */
    public function show(Entreprise $entreprise): Response
    {
        return $this->render('admin/entreprise/show.html.twig', [
            'entreprise' => $entreprise,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="entreprise_edit", methods={"GET","POST"})
     * fonction de mise à jour d'une entreprise
     */
    public function edit(Request $request, Entreprise $entreprise, ManagerRegistry $doctrine): Response
    {
        //appel du form de création d'entreprise
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        //traitement des données saisies
        $form->handleRequest($request);
        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrement des modifications en bdd
            $doctrine->getManager()->flush();

            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'entreprise modifiée avec succès');
            //redirection vers la page qui liste les entreprises
            return $this->redirectToRoute('entreprise_index', [], Response::HTTP_SEE_OTHER);
        }
        //affichage du formulaire
        return $this->renderForm('admin/entreprise/edit.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="entreprise_delete", methods={"POST"})
     * fonction de suppression d'une entreprise par son id
     */
    public function delete(Request $request, Entreprise $entreprise, ManagerRegistry $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entreprise->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($entreprise);
            $entityManager->flush();
        }
        //affichage d'un message si l'opération s'est déroulée avec succès
        $this->addFlash('message', 'entreprise supprimée avec succès');
        //redirection vers la page qui liste les entreprises
        return $this->redirectToRoute('entreprise_index', [], Response::HTTP_SEE_OTHER);
    }
}
