<?php

namespace App\Controller;

use App\Entity\Typeusage;
use App\Form\Typeusage1Type;
use App\Repository\TypeusageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/typeusage")
 */
class TypeusageController extends AbstractController
{
    /**
     * @Route("/", name="app_typeusage_index", methods={"GET"})
     */
    public function index(TypeusageRepository $typeusageRepository): Response
    {
        return $this->render('typeusage/index.html.twig', [
            'typeusages' => $typeusageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_typeusage_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typeusage = new Typeusage();
        $form = $this->createForm(Typeusage1Type::class, $typeusage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeusage);
            $entityManager->flush();

            return $this->redirectToRoute('app_typeusage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('typeusage/new.html.twig', [
            'typeusage' => $typeusage,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_typeusage_show", methods={"GET"})
     */
    public function show(Typeusage $typeusage): Response
    {
        return $this->render('typeusage/show.html.twig', [
            'typeusage' => $typeusage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_typeusage_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Typeusage $typeusage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Typeusage1Type::class, $typeusage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_typeusage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('typeusage/edit.html.twig', [
            'typeusage' => $typeusage,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_typeusage_delete", methods={"POST"})
     */
    public function delete(Request $request, Typeusage $typeusage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeusage->getId(), $request->request->get('_token'))) {
            $entityManager->remove($typeusage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_typeusage_index', [], Response::HTTP_SEE_OTHER);
    }
}
