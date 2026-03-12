<?php

namespace App\Controller\Admin;

use App\Entity\Lot;
use App\Form\LotType;
use App\Repository\LotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/lot')]
#[IsGranted('ROLE_ADMIN')]
class LotController extends AbstractController
{
    #[Route('', name: 'lot_index', methods: ['GET'])]
    public function index(LotRepository $repo): Response
    {
        return $this->render('admin/lot/index.html.twig', [
            'lots' => $repo->findBy([], ['nomLot' => 'ASC']),
        ]);
    }

    #[Route('/new', name: 'lot_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $lot = new Lot();
        $form = $this->createForm(LotType::class, $lot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($lot);
            $em->flush();
            $this->addFlash('message', 'Lot créé avec succès.');
            return $this->redirectToRoute('lot_index');
        }

        return $this->render('admin/lot/new.html.twig', ['form' => $form, 'lot' => $lot]);
    }

    #[Route('/{id}/edit', name: 'lot_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Lot $lot, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(LotType::class, $lot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('message', 'Lot modifié avec succès.');
            return $this->redirectToRoute('lot_index');
        }

        return $this->render('admin/lot/edit.html.twig', ['form' => $form, 'lot' => $lot]);
    }

    #[Route('/{id}/delete', name: 'lot_delete', methods: ['POST'])]
    public function delete(Request $request, Lot $lot, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $lot->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($lot);
            $em->flush();
            $this->addFlash('message', 'Lot supprimé.');
        }
        return $this->redirectToRoute('lot_index');
    }
}
