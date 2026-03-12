<?php

namespace App\Controller\Admin;

use App\Entity\Devis;
use App\Form\DevisType;
use App\Repository\DevisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/devis')]
#[IsGranted('ROLE_ADMIN')]
class DevisController extends AbstractController
{
    #[Route('', name: 'devis_index', methods: ['GET'])]
    public function index(DevisRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $repo->createQueryBuilder('d')
            ->leftJoin('d.chantier', 'c')
            ->leftJoin('d.entreprise', 'e')
            ->orderBy('d.createdAt', 'DESC')
            ->getQuery();

        $devis = $paginator->paginate($query, $request->query->getInt('page', 1), 15);

        return $this->render('admin/devis/index.html.twig', ['devis' => $devis]);
    }

    #[Route('/new', name: 'devis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $devis = new Devis();
        $form = $this->createForm(DevisType::class, $devis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $devis->setUtilisateur($this->getUser());
            $em->persist($devis);
            $em->flush();
            $this->addFlash('message', 'Devis créé avec succès.');
            return $this->redirectToRoute('devis_show', ['id' => $devis->getId()]);
        }

        return $this->render('admin/devis/new.html.twig', ['form' => $form, 'devis' => $devis]);
    }

    #[Route('/{id}', name: 'devis_show', methods: ['GET'])]
    public function show(Devis $devis): Response
    {
        return $this->render('admin/devis/show.html.twig', ['devis' => $devis]);
    }

    #[Route('/{id}/edit', name: 'devis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Devis $devis, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(DevisType::class, $devis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('message', 'Devis modifié.');
            return $this->redirectToRoute('devis_show', ['id' => $devis->getId()]);
        }

        return $this->render('admin/devis/edit.html.twig', ['form' => $form, 'devis' => $devis]);
    }

    #[Route('/{id}/delete', name: 'devis_delete', methods: ['POST'])]
    public function delete(Request $request, Devis $devis, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $devis->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($devis);
            $em->flush();
            $this->addFlash('message', 'Devis supprimé.');
        }
        return $this->redirectToRoute('devis_index');
    }
}
