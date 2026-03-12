<?php

namespace App\Controller\Admin;

use App\Entity\Chantier;
use App\Form\ChantierType;
use App\Repository\ChantierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/chantier')]
#[IsGranted('ROLE_ADMIN')]
class ChantierController extends AbstractController
{
    #[Route('', name: 'chantier_index', methods: ['GET'])]
    public function index(ChantierRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $repo->createQueryBuilder('c')
            ->orderBy('c.nomChantier', 'ASC')
            ->getQuery();

        $chantiers = $paginator->paginate($query, $request->query->getInt('page', 1), 20);

        return $this->render('admin/chantier/index.html.twig', ['chantiers' => $chantiers]);
    }

    #[Route('/new', name: 'chantier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $chantier = new Chantier();
        $form = $this->createForm(ChantierType::class, $chantier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($chantier);
            $em->flush();
            $this->addFlash('message', 'Chantier créé avec succès.');
            return $this->redirectToRoute('chantier_index');
        }

        return $this->render('admin/chantier/new.html.twig', ['form' => $form, 'chantier' => $chantier]);
    }

    #[Route('/{id}/edit', name: 'chantier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chantier $chantier, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ChantierType::class, $chantier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('message', 'Chantier modifié.');
            return $this->redirectToRoute('chantier_index');
        }

        return $this->render('admin/chantier/edit.html.twig', ['form' => $form, 'chantier' => $chantier]);
    }

    #[Route('/{id}/delete', name: 'chantier_delete', methods: ['POST'])]
    public function delete(Request $request, Chantier $chantier, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $chantier->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($chantier);
            $em->flush();
            $this->addFlash('message', 'Chantier supprimé.');
        }
        return $this->redirectToRoute('chantier_index');
    }
}
