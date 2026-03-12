<?php

namespace App\Controller\admin;

use App\Entity\Doctype;
use App\Form\DoctypeType;
use App\Repository\DoctypeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/doctype")
 * @package App\Controller\admin
 */
class DoctypeController extends AbstractController
{
    /**
     * @Route("/", name="doctype_index", methods={"GET", "POST"})
     * fonction qui liste les documents type
     */
    public function index(DoctypeRepository $docTypeRepository, Request $request): Response
    {
        
            return $this->render('admin/doctype/index1.html.twig', [
                'doc_types' => $docTypeRepository->findBy([], ['ordre' => 'ASC']),
                'request' => $request
            ]); 
        
        
    }

    /**
    * Resorts an item using it's doctrine sortable property
    * @param integer $id
    * @param integer $ordre
    * @return \Symfony\Component\HttpFoundation\Response
    * @Route("/sort/{id}/{ordre}", name="admin_doctype_sort")
    */
    public function sortAction($id, $ordre, DoctypeRepository $doctypeRepo, ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $doctype = $doctypeRepo->find($id);
        $doctype->setOrdre($ordre);
        $em->persist($doctype);
        $em->flush();
        $request = new Request();
        return $this->index($doctypeRepo, $request);
    }

    /**
     * @Route("/new", name="doctype_new", methods={"GET","POST"})
     * fonction de création d'un nouvel élément du doctype
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        //instanciation d'un nouvel objet doctype
        $docType = new Doctype();
        //appel du form de création d'un doctype
        $form = $this->createForm(DoctypeType::class, $docType);
        //traitement des données saisies
        $form->handleRequest($request);
        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrement en bdd
            $entityManager = $doctrine->getManager();
            $entityManager->persist($docType);
            $entityManager->flush();

            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'document type crée avec succès');
            //redirection vers la page qui liste les paragraphes type du cctp
            return $this->redirectToRoute('doctype_index', [], Response::HTTP_SEE_OTHER);
        }
        //affichage du formulaire
        return $this->renderForm('admin/doctype/new.html.twig', [
            'doc_type' => $docType,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="doctype_show", methods={"GET"})
     * fonction d'affichage d'un paragraphe du doctype
     */
    public function show(Doctype $docType): Response
    {
        return $this->render('admin/doctype/show.html.twig', [
            'doctype' => $docType,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="doctype_edit", methods={"GET","POST"})
     * fonction de mise à jour d'un élément du doctype
     */
    public function edit(Request $request, Doctype $docType, ManagerRegistry $doctrine): Response
    {
        //appel du form de création
        $form = $this->createForm(DoctypeType::class, $docType);
        //traitement des données saisies
        $form->handleRequest($request);
        //enregistrement des modifications en bdd
        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();

            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'document type modifié avec succès');
            //redirection vers la page qui liste les paragraphes type du cctp
            return $this->redirectToRoute('doctype_index', [], Response::HTTP_SEE_OTHER);
        }
        //affichage du formulaire
        return $this->renderForm('admin/doctype/edit.html.twig', [
            'doctype' => $docType,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="doctype_delete", methods={"POST"})
     * fonction de suppression d'un élément par son id
     */
    public function delete(Request $request, Doctype $docType, ManagerRegistry $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$docType->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($docType);
            $entityManager->flush();
        }

        //affichage d'un message si l'opération s'est déroulée avec succès
        $this->addFlash('message', 'document type supprimé avec succès');
        //redirection vers la page qui liste les paragraphes type du cctp
        return $this->redirectToRoute('doctype_index', [], Response::HTTP_SEE_OTHER);
    }
}
