<?php

namespace App\Controller\Admin;

use App\Entity\Moe;
use App\Form\MoeType;
use App\Repository\MoeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/moe')]
#[IsGranted('ROLE_ADMIN')]
class MoeController extends AbstractController
{
    #[Route('', name: 'moe_index', methods: ['GET'])]
    public function index(MoeRepository $repo): Response
    {
        return $this->render('admin/moe/index.html.twig', [
            'moes' => $repo->findBy([], ['nomMoe' => 'ASC']),
        ]);
    }

    #[Route('/new', name: 'moe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $moe = new Moe();
        $form = $this->createForm(MoeType::class, $moe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($moe);
            $em->flush();
            $this->addFlash('message', 'MOE créé avec succès.');
            return $this->redirectToRoute('moe_index');
        }

        return $this->render('admin/moe/new.html.twig', ['form' => $form, 'moe' => $moe]);
    }

    #[Route('/{id}/edit', name: 'moe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Moe $moe, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(MoeType::class, $moe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('message', 'MOE modifié.');
            return $this->redirectToRoute('moe_index');
        }

        return $this->render('admin/moe/edit.html.twig', ['form' => $form, 'moe' => $moe]);
    }

    #[Route('/{id}/delete', name: 'moe_delete', methods: ['POST'])]
    public function delete(Request $request, Moe $moe, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $moe->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($moe);
            $em->flush();
            $this->addFlash('message', 'MOE supprimé.');
        }
        return $this->redirectToRoute('moe_index');
    }
}
