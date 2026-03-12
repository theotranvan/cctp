<?php

namespace App\Controller\Admin;

use App\Entity\Optionnel;
use App\Form\OptionnelType;
use App\Repository\OptionnelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/optionnel')]
#[IsGranted('ROLE_ADMIN')]
class OptionnelController extends AbstractController
{
    #[Route('', name: 'optionnel_index', methods: ['GET'])]
    public function index(OptionnelRepository $repo): Response
    {
        return $this->render('admin/optionnel/index.html.twig', [
            'optionnels' => $repo->findBy([], ['title' => 'ASC']),
        ]);
    }

    #[Route('/new', name: 'optionnel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $optionnel = new Optionnel();
        $form = $this->createForm(OptionnelType::class, $optionnel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($optionnel);
            $em->flush();
            $this->addFlash('message', 'Clause optionnelle créée avec succès.');
            return $this->redirectToRoute('optionnel_index');
        }

        return $this->render('admin/optionnel/new.html.twig', ['form' => $form, 'optionnel' => $optionnel]);
    }

    #[Route('/{id}/edit', name: 'optionnel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Optionnel $optionnel, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(OptionnelType::class, $optionnel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('message', 'Clause optionnelle modifiée.');
            return $this->redirectToRoute('optionnel_index');
        }

        return $this->render('admin/optionnel/edit.html.twig', ['form' => $form, 'optionnel' => $optionnel]);
    }

    #[Route('/{id}/delete', name: 'optionnel_delete', methods: ['POST'])]
    public function delete(Request $request, Optionnel $optionnel, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $optionnel->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($optionnel);
            $em->flush();
            $this->addFlash('message', 'Clause optionnelle supprimée.');
        }
        return $this->redirectToRoute('optionnel_index');
    }
}
