<?php

namespace App\Controller\Admin;

use App\Entity\Catusage;
use App\Form\CatusageType;
use App\Repository\CatusageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/catusage')]
#[IsGranted('ROLE_ADMIN')]
class CatusageController extends AbstractController
{
    #[Route('', name: 'catusage_index', methods: ['GET'])]
    public function index(CatusageRepository $repo): Response
    {
        return $this->render('admin/catusage/index.html.twig', [
            'catusages' => $repo->findBy([], ['nomCatusage' => 'ASC']),
        ]);
    }

    #[Route('/new', name: 'catusage_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $catusage = new Catusage();
        $form = $this->createForm(CatusageType::class, $catusage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($catusage);
            $em->flush();
            $this->addFlash('message', 'Catégorie d\'usage créée avec succès.');
            return $this->redirectToRoute('catusage_index');
        }

        return $this->render('admin/catusage/new.html.twig', ['form' => $form, 'catusage' => $catusage]);
    }

    #[Route('/{id}/edit', name: 'catusage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Catusage $catusage, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CatusageType::class, $catusage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('message', 'Catégorie d\'usage modifiée.');
            return $this->redirectToRoute('catusage_index');
        }

        return $this->render('admin/catusage/edit.html.twig', ['form' => $form, 'catusage' => $catusage]);
    }

    #[Route('/{id}/delete', name: 'catusage_delete', methods: ['POST'])]
    public function delete(Request $request, Catusage $catusage, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $catusage->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($catusage);
            $em->flush();
            $this->addFlash('message', 'Catégorie d\'usage supprimée.');
        }
        return $this->redirectToRoute('catusage_index');
    }
}
