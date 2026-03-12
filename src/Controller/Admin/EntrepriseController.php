<?php

namespace App\Controller\Admin;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/entreprise')]
#[IsGranted('ROLE_ADMIN')]
class EntrepriseController extends AbstractController
{
    #[Route('', name: 'entreprise_index', methods: ['GET'])]
    public function index(EntrepriseRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $role = $request->query->getString('role');
        $qb = $repo->createQueryBuilder('e')->orderBy('e.nomEntreprise', 'ASC');
        if ($role) {
            $qb->andWhere('e.role = :role')->setParameter('role', $role);
        }
        $entreprises = $paginator->paginate($qb->getQuery(), $request->query->getInt('page', 1), 20);

        return $this->render('admin/entreprise/index.html.twig', [
            'entreprises' => $entreprises,
            'current_role' => $role,
        ]);
    }

    #[Route('/new', name: 'entreprise_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $entreprise = new Entreprise();
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($entreprise);
            $em->flush();
            $this->addFlash('message', 'Entreprise créée avec succès.');
            return $this->redirectToRoute('entreprise_index');
        }

        return $this->render('admin/entreprise/new.html.twig', ['form' => $form, 'entreprise' => $entreprise]);
    }

    #[Route('/{id}/edit', name: 'entreprise_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Entreprise $entreprise, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('message', 'Entreprise modifiée.');
            return $this->redirectToRoute('entreprise_index');
        }

        return $this->render('admin/entreprise/edit.html.twig', ['form' => $form, 'entreprise' => $entreprise]);
    }

    #[Route('/{id}/delete', name: 'entreprise_delete', methods: ['POST'])]
    public function delete(Request $request, Entreprise $entreprise, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $entreprise->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($entreprise);
            $em->flush();
            $this->addFlash('message', 'Entreprise supprimée.');
        }
        return $this->redirectToRoute('entreprise_index');
    }
}
