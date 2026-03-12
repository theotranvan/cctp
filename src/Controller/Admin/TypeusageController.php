<?php

namespace App\Controller\Admin;

use App\Entity\Typeusage;
use App\Form\TypeusageType;
use App\Repository\TypeusageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/typeusage')]
#[IsGranted('ROLE_ADMIN')]
class TypeusageController extends AbstractController
{
    #[Route('', name: 'typeusage_index', methods: ['GET'])]
    public function index(TypeusageRepository $repo): Response
    {
        return $this->render('admin/typeusage/index.html.twig', [
            'typeusages' => $repo->findBy([], ['nomUsage' => 'ASC']),
        ]);
    }

    #[Route('/new', name: 'typeusage_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $typeusage = new Typeusage();
        $form = $this->createForm(TypeusageType::class, $typeusage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($typeusage);
            $em->flush();
            $this->addFlash('message', 'Type d\'usage créé avec succès.');
            return $this->redirectToRoute('typeusage_index');
        }

        return $this->render('admin/typeusage/new.html.twig', ['form' => $form, 'typeusage' => $typeusage]);
    }

    #[Route('/{id}/edit', name: 'typeusage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Typeusage $typeusage, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TypeusageType::class, $typeusage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('message', 'Type d\'usage modifié.');
            return $this->redirectToRoute('typeusage_index');
        }

        return $this->render('admin/typeusage/edit.html.twig', ['form' => $form, 'typeusage' => $typeusage]);
    }

    #[Route('/{id}/delete', name: 'typeusage_delete', methods: ['POST'])]
    public function delete(Request $request, Typeusage $typeusage, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $typeusage->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($typeusage);
            $em->flush();
            $this->addFlash('message', 'Type d\'usage supprimé.');
        }
        return $this->redirectToRoute('typeusage_index');
    }
}
