<?php

namespace App\Controller\Admin;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/utilisateur')]
#[IsGranted('ROLE_ADMIN')]
class UtilisateurController extends AbstractController
{
    #[Route('', name: 'utilisateur_index', methods: ['GET'])]
    public function index(UtilisateurRepository $repo): Response
    {
        return $this->render('admin/utilisateur/index.html.twig', [
            'utilisateurs' => $repo->findBy([], ['nomUser' => 'ASC']),
        ]);
    }

    #[Route('/new', name: 'utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur, ['include_password' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur->setPassword($hasher->hashPassword($utilisateur, $form->get('plainPassword')->getData()));
            $utilisateur->setIsVerified(true);
            $em->persist($utilisateur);
            $em->flush();
            $this->addFlash('message', 'Utilisateur créé avec succès.');
            return $this->redirectToRoute('utilisateur_index');
        }

        return $this->render('admin/utilisateur/new.html.twig', ['form' => $form, 'utilisateur' => $utilisateur]);
    }

    #[Route('/{id}/edit', name: 'utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('message', 'Utilisateur modifié.');
            return $this->redirectToRoute('utilisateur_index');
        }

        return $this->render('admin/utilisateur/edit.html.twig', ['form' => $form, 'utilisateur' => $utilisateur]);
    }

    #[Route('/{id}/change-password', name: 'utilisateur_change_password', methods: ['GET', 'POST'])]
    public function changePassword(Request $request, Utilisateur $utilisateur, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur, ['include_password' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur->setPassword($hasher->hashPassword($utilisateur, $form->get('plainPassword')->getData()));
            $em->flush();
            $this->addFlash('message', 'Mot de passe modifié.');
            return $this->redirectToRoute('utilisateur_index');
        }

        return $this->render('admin/utilisateur/change_password.html.twig', ['form' => $form, 'utilisateur' => $utilisateur]);
    }

    #[Route('/{id}/delete', name: 'utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateur $utilisateur, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $utilisateur->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($utilisateur);
            $em->flush();
            $this->addFlash('message', 'Utilisateur supprimé.');
        }
        return $this->redirectToRoute('utilisateur_index');
    }
}
