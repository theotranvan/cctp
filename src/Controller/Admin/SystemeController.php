<?php

namespace App\Controller\Admin;

use App\Entity\Systeme;
use App\Form\SystemeType;
use App\Repository\SystemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/systeme')]
#[IsGranted('ROLE_ADMIN')]
class SystemeController extends AbstractController
{
    #[Route('', name: 'systeme_index', methods: ['GET'])]
    public function index(SystemeRepository $repo): Response
    {
        return $this->render('admin/systeme/index.html.twig', [
            'systemes' => $repo->findBy([], ['nomSysteme' => 'ASC']),
        ]);
    }

    #[Route('/new', name: 'systeme_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $systeme = new Systeme();
        $form = $this->createForm(SystemeType::class, $systeme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($systeme);
            $em->flush();
            $this->addFlash('message', 'Système créé avec succès.');
            return $this->redirectToRoute('systeme_index');
        }

        return $this->render('admin/systeme/new.html.twig', ['form' => $form, 'systeme' => $systeme]);
    }

    #[Route('/{id}/edit', name: 'systeme_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Systeme $systeme, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SystemeType::class, $systeme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('message', 'Système modifié avec succès.');
            return $this->redirectToRoute('systeme_index');
        }

        return $this->render('admin/systeme/edit.html.twig', ['form' => $form, 'systeme' => $systeme]);
    }

    #[Route('/{id}/delete', name: 'systeme_delete', methods: ['POST'])]
    public function delete(Request $request, Systeme $systeme, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $systeme->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($systeme);
            $em->flush();
            $this->addFlash('message', 'Système supprimé.');
        }
        return $this->redirectToRoute('systeme_index');
    }
}
