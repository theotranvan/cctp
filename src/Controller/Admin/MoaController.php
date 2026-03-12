<?php

namespace App\Controller\Admin;

use App\Entity\Moa;
use App\Form\MoaType;
use App\Repository\MoaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/moa')]
#[IsGranted('ROLE_ADMIN')]
class MoaController extends AbstractController
{
    #[Route('', name: 'moa_index', methods: ['GET'])]
    public function index(MoaRepository $repo): Response
    {
        return $this->render('admin/moa/index.html.twig', [
            'moas' => $repo->findBy([], ['nomMoa' => 'ASC']),
        ]);
    }

    #[Route('/new', name: 'moa_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $moa = new Moa();
        $form = $this->createForm(MoaType::class, $moa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($moa);
            $em->flush();
            $this->addFlash('message', 'MOA créé avec succès.');
            return $this->redirectToRoute('moa_index');
        }

        return $this->render('admin/moa/new.html.twig', ['form' => $form, 'moa' => $moa]);
    }

    #[Route('/{id}/edit', name: 'moa_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Moa $moa, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(MoaType::class, $moa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('message', 'MOA modifié.');
            return $this->redirectToRoute('moa_index');
        }

        return $this->render('admin/moa/edit.html.twig', ['form' => $form, 'moa' => $moa]);
    }

    #[Route('/{id}/delete', name: 'moa_delete', methods: ['POST'])]
    public function delete(Request $request, Moa $moa, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $moa->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($moa);
            $em->flush();
            $this->addFlash('message', 'MOA supprimé.');
        }
        return $this->redirectToRoute('moa_index');
    }
}
