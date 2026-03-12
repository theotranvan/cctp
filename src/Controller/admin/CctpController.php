<?php

namespace App\Controller\admin;

use Amp\Http\Client\Request as ClientRequest;
use App\Entity\Cctp;
use App\Entity\Produit;
use App\Form\CctpType;
use App\Repository\CctpRepository;
use App\Repository\DoctypeRepository;
use App\Repository\DocFinalRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\OptionnelRepository;
use App\Controller\admin\ProduitRepository;
use App\Repository\ProduitRepository as RepositoryProduitRepository;
use App\Repository\SystemeRepository;
use PhpOffice\PhpWord\TemplateProcessor;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use PhpOffice\PhpWord\IOFactory;

/**
 * @Route("admin/cctp")
 * @package App\Controller\admin
 */
class CctpController extends AbstractController
{
    /**
     * @Route("/", name="cctp_index", methods={"GET"})
     * fonction qui liste tous les cctp
     */
    public function index(CctpRepository $cctpRepository): Response
    {
        return $this->render('admin/cctp/index.html.twig', [
            'cctps' => $cctpRepository->findBy([], ['NumAffaire' => 'ASC']),
        ]);
    }

    /**
     * @Route("/new", name="cctp_new", methods={"GET","POST"})
     * fonction de création d'un nouveau cctp
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        //création d'un nouvel objet cctp
        $cctp = new Cctp();
        //appel du formulaire de création
        $form = $this->createForm(CctpType::class, $cctp);
        //traitement de la saisie du form
        $form->handleRequest($request);

        //si le form est soumis et valide 
        if ($form->isSubmitted() && $form->isValid()) {
            //récupération de l'utilisateur
            $cctp->setUtilisateur($this->getUser());
            $cctp->setClose(0);
            //enregistrement en bdd
            $entityManager = $doctrine->getManager();
            $entityManager->persist($cctp);
            $entityManager->flush();

            //affichage d'un message en cas d'opération réalisée avec succès
            $this->addFlash('message', 'Cctp crée avec succès');
            //redirection vers la liste des cctp
            return $this->redirectToRoute('cctp_index', [], Response::HTTP_SEE_OTHER);
        }
        //affichage du form 
        return $this->renderForm('admin/cctp/new.html.twig', [
            'cctp' => $cctp,
            'form' => $form
        ]);
    }

    /**
     * @route("/ajout_prod/{id}", name="cctp_ajout_prod", methods={"GET","POST"})
     * fonction d'ajout d'éléments au cctp
     */
    public function ajoutProd(Cctp $cctp, Request $request, ManagerRegistry $doctrine)
    {
        $lots = $cctp->getLotCctp();

        $data = array();

        //s'il y'a 6 lots
        if ($lots[1] != null and $lots[2] != null and $lots[3] != null and $lots[4] != null and $lots[5] != null) {
            //création du formulaire d'ajout de produits au cctp
            $form = $this->createFormBuilder($data)
                //appel des produits liés au 1er lot
                ->add('produits', EntityType::class, [
                    'class' => Produit::class,
                    'choices' => $lots[0]->getProduits(),
                    'group_by' => 'systeme.nom_systeme',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Produits du lot: ' . $lots[0],
                    'by_reference' => false
                ])
                //appel des produits liés au 2eme lot
                ->add('produits1', EntityType::class, [
                    'class' => Produit::class,
                    'choices' => $lots[1]->getProduits(),
                    'group_by' => 'systeme.nom_systeme',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Produits du lot: ' . $lots[1],
                    'by_reference' => false
                ])
                //appel des produits liés au 3eme lot
                ->add('produits2', EntityType::class, [
                    'class' => Produit::class,
                    'choices' => $lots[2]->getProduits(),
                    'group_by' => 'systeme.nom_systeme',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Produits du lot: ' . $lots[2],
                    'by_reference' => false
                ])
                //appel des produits liés au 4eme lot
                ->add('produits3', EntityType::class, [
                    'class' => Produit::class,
                    'choices' => $lots[3]->getProduits(),
                    'group_by' => 'systeme.nom_systeme',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Produits du lot: ' . $lots[3],
                    'by_reference' => false
                ])
                //appel des produits liés au 5eme lot
                ->add('produits4', EntityType::class, [
                    'class' => Produit::class,
                    'choices' => $lots[4]->getProduits(),
                    'group_by' => 'systeme.nom_systeme',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Produits du lot: ' . $lots[4],
                    'by_reference' => false
                ])
                //appel des produits liés au 6eme lot
                ->add('produits5', EntityType::class, [
                    'class' => Produit::class,
                    'choices' => $lots[5]->getProduits(),
                    'group_by' => 'systeme.nom_systeme',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Produits du lot: ' . $lots[5],
                    'by_reference' => false
                ])
                ->add('Valider', SubmitType::class)
                ->getForm();

            //si le formulaire est soumis
            if ($request->isMethod('POST')) {
                //traitement des données saisies
                $form->handleRequest($request);

                //récupération des éléments en tableau
                $data = $form->getData();
                $data1 = $data['produits'];
                $data2 = $data['produits1'];
                $data3 = $data['produits2'];
                $data4 = $data['produits3'];
                $data5 = $data['produits4'];
                $data6 = $data['produits5'];

                //ajout de chaque élément des tableaux au cctp
                for ($i = 0; $i < count($data1); $i++) {
                    $cctp->addProduit($data1[$i]);
                }
                for ($i = 0; $i < count($data2); $i++) {
                    $cctp->addProduit($data2[$i]);
                }
                for ($i = 0; $i < count($data3); $i++) {
                    $cctp->addProduit($data3[$i]);
                }
                for ($i = 0; $i < count($data4); $i++) {
                    $cctp->addProduit($data4[$i]);
                }
                for ($i = 0; $i < count($data5); $i++) {
                    $cctp->addProduit($data5[$i]);
                }
                for ($i = 0; $i < count($data6); $i++) {
                    $cctp->addProduit($data6[$i]);
                }
                //enregistrement en bdd
                $entityManager = $doctrine->getManager();
                $entityManager->persist($cctp);
                $entityManager->flush();

                //affichage d'un message en cas d'action réalisée avec succès
                $this->addFlash('message', 'Elément ajouté avec succès');
                //redirection vers la page qui liste les cctp
                return $this->redirectToRoute('cctp_show', ['id' => $cctp->getId()], Response::HTTP_SEE_OTHER);
            }
        }
        //s'il y'a 5 lots
        elseif ($lots[1] != null and $lots[2] != null and $lots[3] != null and $lots[4] != null) {
            //création du formulaire d'ajout de produits au cctp
            $form = $this->createFormBuilder($data)
                //appel des produits liés au 1er lot
                ->add('produits', EntityType::class, [
                    'class' => Produit::class,
                    'choices' => $lots[0]->getProduits(),
                    'group_by' => 'systeme.nom_systeme',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Produits du lot: ' . $lots[0],
                    'by_reference' => false
                ])
                //appel des produits liés au 2eme lot
                ->add('produits1', EntityType::class, [
                    'class' => Produit::class,
                    'choices' => $lots[1]->getProduits(),
                    'group_by' => 'systeme.nom_systeme',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Produits du lot: ' . $lots[1],
                    'by_reference' => false
                ])
                //appel des produits liés au 3eme lot
                ->add('produits2', EntityType::class, [
                    'class' => Produit::class,
                    'choices' => $lots[2]->getProduits(),
                    'group_by' => 'systeme.nom_systeme',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Produits du lot: ' . $lots[2],
                    'by_reference' => false
                ])
                //appel des produits liés au 4eme lot
                ->add('produits3', EntityType::class, [
                    'class' => Produit::class,
                    'choices' => $lots[3]->getProduits(),
                    'group_by' => 'systeme.nom_systeme',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Produits du lot: ' . $lots[3],
                    'by_reference' => false
                ])
                //appel des produits liés au 5eme lot
                ->add('produits4', EntityType::class, [
                    'class' => Produit::class,
                    'choices' => $lots[4]->getProduits(),
                    'group_by' => 'systeme.nom_systeme',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Produits du lot: ' . $lots[4],
                    'by_reference' => false
                ])
                ->add('Valider', SubmitType::class)
                ->getForm();

            //si le formulaire est soumis
            if ($request->isMethod('POST')) {
                //traitement des données saisies
                $form->handleRequest($request);

                //récupération des éléments en tableau
                $data = $form->getData();
                $data1 = $data['produits'];
                $data2 = $data['produits1'];
                $data3 = $data['produits2'];
                $data4 = $data['produits3'];
                $data5 = $data['produits4'];

                //ajout de chaque élément des tableaux au cctp
                for ($i = 0; $i < count($data1); $i++) {
                    $cctp->addProduit($data1[$i]);
                }
                for ($i = 0; $i < count($data2); $i++) {
                    $cctp->addProduit($data2[$i]);
                }
                for ($i = 0; $i < count($data3); $i++) {
                    $cctp->addProduit($data3[$i]);
                }
                for ($i = 0; $i < count($data4); $i++) {
                    $cctp->addProduit($data4[$i]);
                }
                for ($i = 0; $i < count($data5); $i++) {
                    $cctp->addProduit($data5[$i]);
                }
                //enregistrement en bdd
                $entityManager = $doctrine->getManager();
                $entityManager->persist($cctp);
                $entityManager->flush();

                //affichage d'un message en cas d'action réalisée avec succès
                $this->addFlash('message', 'Elément ajouté avec succès');
                //redirection vers la page qui liste les cctp
                return $this->redirectToRoute('cctp_show', ['id' => $cctp->getId()], Response::HTTP_SEE_OTHER);
            }
        }
        //s'il y'a 4 lots
        elseif ($lots[1] != null and $lots[2] != null and $lots[3] != null) {
            //création du formulaire d'ajout de produits au cctp
            $form = $this->createFormBuilder($data)
                //appel des produits liés au 1er lot
                ->add('produits', EntityType::class, [
                    'class' => Produit::class,
                    'choices' => $lots[0]->getProduits(),
                    'group_by' => 'systeme.nom_systeme',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Produits du lot: ' . $lots[0],
                    'by_reference' => false
                ])
                //appel des produits liés au 2eme lot
                ->add('produits1', EntityType::class, [
                    'class' => Produit::class,
                    'choices' => $lots[1]->getProduits(),
                    'group_by' => 'systeme.nom_systeme',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Produits du lot: ' . $lots[1],
                    'by_reference' => false
                ])
                //appel des produits liés au 3eme lot
                ->add('produits2', EntityType::class, [
                    'class' => Produit::class,
                    'choices' => $lots[2]->getProduits(),
                    'group_by' => 'systeme.nom_systeme',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Produits du lot: ' . $lots[2],
                    'by_reference' => false
                ])
                //appel des produits liés au 3eme lot
                ->add('produits3', EntityType::class, [
                    'class' => Produit::class,
                    'choices' => $lots[3]->getProduits(),
                    'group_by' => 'systeme.nom_systeme',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Produits du lot: ' . $lots[3],
                    'by_reference' => false
                ])
                ->add('Valider', SubmitType::class)
                ->getForm();

            //si le formulaire est soumis
            if ($request->isMethod('POST')) {
                //traitement des données saisies
                $form->handleRequest($request);

                //récupération des éléments en tableau
                $data = $form->getData();
                $data1 = $data['produits'];
                $data2 = $data['produits1'];
                $data3 = $data['produits2'];
                $data4 = $data['produits3'];

                //ajout de chaque élément des tableaux au cctp
                for ($i = 0; $i < count($data1); $i++) {
                    $cctp->addProduit($data1[$i]);
                }
                for ($i = 0; $i < count($data2); $i++) {
                    $cctp->addProduit($data2[$i]);
                }
                for ($i = 0; $i < count($data3); $i++) {
                    $cctp->addProduit($data3[$i]);
                }
                for ($i = 0; $i < count($data4); $i++) {
                    $cctp->addProduit($data4[$i]);
                }
                //enregistrement en bdd
                $entityManager = $doctrine->getManager();
                $entityManager->persist($cctp);
                $entityManager->flush();

                //affichage d'un message en cas d'action réalisée avec succès
                $this->addFlash('message', 'Elément ajouté avec succès');
                //redirection vers la page qui liste les cctp
                return $this->redirectToRoute('cctp_show', ['id' => $cctp->getId()], Response::HTTP_SEE_OTHER);
            }
        }
        //si il y'a 3 lots pour ce cctp
        elseif ($lots[1] != null and $lots[2] != null) {
            //création du formulaire d'ajout de produits au cctp
            $form = $this->createFormBuilder($data)
                //appel des produits liés au 1er lot
                ->add('produits', EntityType::class, [
                    'class' => Produit::class,
                    'choices' => $lots[0]->getProduits(),
                    'group_by' => 'systeme.nom_systeme',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Produits du lot: ' . $lots[0],
                    'by_reference' => false
                ])
                //appel des produits liés au 2eme lot
                ->add('produits1', EntityType::class, [
                    'class' => Produit::class,
                    'choices' => $lots[1]->getProduits(),
                    'group_by' => 'systeme.nom_systeme',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Produits du lot: ' . $lots[1],
                    'by_reference' => false
                ])
                //appel des produits liés au 3eme lot
                ->add('produits2', EntityType::class, [
                    'class' => Produit::class,
                    'choices' => $lots[2]->getProduits(),
                    'group_by' => 'systeme.nom_systeme',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Produits du lot: ' . $lots[2],
                    'by_reference' => false
                ])
                ->add('Valider', SubmitType::class)
                ->getForm();

            //si le formulaire est soumis
            if ($request->isMethod('POST')) {
                //traitement des données saisies
                $form->handleRequest($request);

                //récupération des éléments en tableau
                $data = $form->getData();
                $data1 = $data['produits'];
                $data2 = $data['produits1'];
                $data3 = $data['produits2'];

                //ajout de chaque élément des tableaux au cctp
                for ($i = 0; $i < count($data1); $i++) {
                    $cctp->addProduit($data1[$i]);
                }
                for ($i = 0; $i < count($data2); $i++) {
                    $cctp->addProduit($data2[$i]);
                }
                for ($i = 0; $i < count($data3); $i++) {
                    $cctp->addProduit($data3[$i]);
                }
                //enregistrement en bdd
                $entityManager = $doctrine->getManager();
                $entityManager->persist($cctp);
                $entityManager->flush();

                //affichage d'un message en cas d'action réalisée avec succès
                $this->addFlash('message', 'Elément ajouté avec succès');
                //redirection vers la page qui liste les cctp
                return $this->redirectToRoute('cctp_show', ['id' => $cctp->getId()], Response::HTTP_SEE_OTHER);
            }
        }
        //si il y'a 2 lots pour ce cctp
        else if ($lots[1] != null and !isset($lots[2])) {
            //création du formulaire d'ajout de produits au cctp
            $form = $this->createFormBuilder($data)
                //appel des produits liés au 1er lot
                ->add('produits', EntityType::class, [
                    'class' => Produit::class,
                    'choices' => $lots[0]->getProduits(),
                    'group_by' => 'systeme.nom_systeme',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Produits du lot: ' . $lots[0],
                    'by_reference' => false
                ])
                //appel des produits liés au 2eme lot
                ->add('produits1', EntityType::class, [
                    'class' => Produit::class,
                    'choices' => $lots[1]->getProduits(),
                    'group_by' => 'systeme.nom_systeme',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Produits du lot: ' . $lots[1],
                    'by_reference' => false
                ])
                ->add('Valider', SubmitType::class)
                ->getForm();

            //si le formulaire est soumis
            if ($request->isMethod('POST')) {
                //traitement des données saisies
                $form->handleRequest($request);

                //récupération des éléments en tableau
                $data = $form->getData();
                $data1 = $data['produits'];
                $data2 = $data['produits1'];

                //ajout de chaque élément des tableaux au cctp
                for ($i = 0; $i < count($data1); $i++) {
                    $cctp->addProduit($data1[$i]);
                }
                for ($i = 0; $i < count($data2); $i++) {
                    $cctp->addProduit($data2[$i]);
                }
                //enregistrement en bdd
                $entityManager = $doctrine->getManager();
                $entityManager->persist($cctp);
                $entityManager->flush();

                //affichage d'un message en cas d'action réalisée avec succès
                $this->addFlash('message', 'Elément ajouté avec succès');
                //redirection vers la page qui liste les cctp
                return $this->redirectToRoute('cctp_show', ['id' => $cctp->getId()], Response::HTTP_SEE_OTHER);
            }
            //si il y'a un seul lot pour ce cctp
        } else {
            //création du formulaire d'ajout de produits au cctp
            $form = $this->createFormBuilder($data)
                ->add('produits', EntityType::class, [
                    'class' => Produit::class,
                    'choices' => $lots[0]->getProduits(),
                    'group_by' => 'systeme.nom_systeme',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Produits du lot: ' . $lots[0],
                    'by_reference' => false
                ])
                ->add('Valider', SubmitType::class)
                ->getForm();


            //si le formulaire est soumis
            if ($request->isMethod('POST')) {
                //traitement des données saisies
                $form->handleRequest($request);

                //récupération des éléments en tableau
                $data = $form->getData();
                $data1 = $data['produits'];

                //ajout de chaque élément des tableaux au cctp
                for ($i = 0; $i < count($data1); $i++) {
                    $cctp->addProduit($data1[$i]);
                }
                //enregistrement en bdd
                $entityManager = $doctrine->getManager();
                $entityManager->persist($cctp);
                $entityManager->flush();

                //affichage d'un message en cas d'action réalisée avec succès
                $this->addFlash('message', 'Elément ajouté avec succès');
                //redirection vers la page qui liste les cctp
                return $this->redirectToRoute('cctp_show', ['id' => $cctp->getId()], Response::HTTP_SEE_OTHER);
            }
        }

        /*$form = $this->createFormBuilder($data)
            ->add('produits', EntityType::class, [
                'class' => Produit::class,
                'choices' => $lot[0]->getProduits(),
                'group_by' => 'systeme.nom_systeme',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Produits du lot: ' . $lot[0],
                'by_reference' => false
            ])
            ->add('Valider', SubmitType::class)
            ->getForm();*/

        //affichage du form en focntion du nombre de lots
        return $this->render('admin/cctp/ajout_prod.html.twig', [
            'cctp' => $cctp,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/remove_prod/{id}/{idCctp}", name="cctp_remove_prod", methods={"GET"})
     * fonction qui supprime un élément de la liste des éléments sélectionnés
     */
    public function deleteProd(CctpRepository $cctpRepo, $id, $idCctp, ManagerRegistry $doctrine): Response
    {
        //récupération du cctp par son id
        $cctp = $cctpRepo->findOneBy(['id' => $idCctp]);
        //récupération de la liste des éléments liés au cctp
        $listeProd = $cctp->getProduits();
        //boucle sur les élémnents liés au cctp
        foreach ($listeProd as $prod) {
            //si l'id correspond à l'élémnt séléctionné
            if ($prod->getId() == $id) {
                //suppression de l'élément de la liste
                $cctp->removeProduit($prod);
            }
        }
        //enregistrement en bdd
        $entityManager = $doctrine->getManager();
        $entityManager->persist($cctp);
        $entityManager->flush();

        //affichage d'un message en cas d'action réalisée avec succès
        $this->addFlash('message', 'Elémént supprimé avec succès');
        //redirection vers la page listant les éléments liés au cctp
        return $this->redirectToRoute('cctp_show', ['id' => $cctp->getId()], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/{id}", name="cctp_show", methods={"GET"})
     * fonction qui affiche toutes les informations d'un cctp
     */
    public function show(Cctp $cctp, SystemeRepository $systemeRepo, SessionInterface $session): Response
    {
        $compteur = $session->get("comteur ajout", []);

        return $this->render('admin/cctp/show.html.twig', [
            'cctp' => $cctp,
            'systemes' => $systemeRepo->findAll(),
            'compteur' => $compteur
        ]);
    }

    /**
     * @Route("/activer/{id}", name="activer")
     */
    public function activer(Cctp $cctp, ManagerRegistry $doctrine)
    {
        $cctp->setClose(($cctp->getClose()) ? false : true);

        $em = $doctrine->getManager();
        $em->persist($cctp);
        $em->flush();

        return new Response("true");
    }

    /**
     * @Route("/okdoc/{id}", name="cctp_okdoc", methods={"GET" , "POST"})
     * fonction qui génère la page d'acceuil du doc word
     */
    public function newAcceuilWord(CctpRepository $cctpRepo, $id, ManagerRegistry $doctrine): Response
    {

        $cctp = $cctpRepo->findOneBy(['id' => $id]);

        $cctp->setClose(1);
        //enregistrement en bdd
        $entityManager = $doctrine->getManager();
        $entityManager->persist($cctp);
        $entityManager->flush();

        //dd($cctp);
        //concatenation des noms de lots s'il y'en a plusieurs
        $lots = $cctp->getLotCctp();
        if (isset($lots[2]) && $lots[2] != null) {
            $nomLot = $lots[0]->getNomLot() . " , " . $lots[1]->getNomLot() . " , " . $lots[2]->getNomLot();
        } else if (isset($lots[1]) && $lots[1] != null && !isset($lots[2])) {
            $nomLot = $lots[0]->getNomLot() . " , " . $lots[1]->getNomLot();
        } else {
            $nomLot = $lots[0]->getNomLot();
        }

        //récupération des différents éléments à afficher
        /*$produits = $cctp->getProduits();
         
         $docstype = $doctype->findAll();
 
         $options = $optionRepo->findAll();
 
         $panier = $session->get("panier", []);*/

        //génération de la page d'acceuil
        $templateProcessor = new TemplateProcessor('uploads/CctpType2.docx');
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
        $templateProcessor->setValue('Nom_Operation', $cctp->getNomOperation());
        $templateProcessor->setValue('Date', 'Le ' . date('d-m-Y'));
        foreach($cctp->getEntreprise() as $entreprise)
        {
            if($entreprise->getRole() == 'MOA')
            {
                $templateProcessor->setValue('Nom_MOA', $entreprise->getNomEntreprise());
                $templateProcessor->setValue('Num_rue_Moa', $entreprise->getNumRueEntreprise());
                $templateProcessor->setValue('Nom_rue_Moa', $entreprise->getNomRueEntreprise());
                $templateProcessor->setValue('Cp_Moa', $entreprise->getCpEntreprise());
                $templateProcessor->setValue('Ville_Moa', $entreprise->getVilleEntreprise());
            }
            if($entreprise->getRole() != null && $entreprise->getRole() == 'MOE')
            {
                $templateProcessor->setValue('Nom_MOE', $entreprise->getNomEntreprise());
                $templateProcessor->setValue('Num_rue_Moe', $entreprise->getNumRueEntreprise());
                $templateProcessor->setValue('Nom_rue_Moe', $entreprise->getNomRueEntreprise());
                $templateProcessor->setValue('Cp_Moe', $entreprise->getCpEntreprise());
                $templateProcessor->setValue('Ville_Moe', $entreprise->getVilleEntreprise());
            }
        }
        
        //$templateProcessor->setValue('Nom_MOE', $cctp->getEntreprise());
        $templateProcessor->setValue('Nom_Lots', $nomLot);
        //$pathToSave = "uploads/".date("Y")."_".'pageAcceuil_'.$cctp->getNomOperation().'.docx';
        //$templateProcessor->saveAs($pathToSave);

        header("Content-Disposition: attachment; filename=" . date('Y') . "_" . 'pageAcceuil_' . $cctp->getNomOperation() . '.docx');
        $templateProcessor->saveAs('php://output');

        //affichage d'un message si l'opération s"est déroulée avec succès
        $this->addFlash('message', 'Document crée avec succès');
        //redirection vers la page qui liste les cctp
        return $this->redirectToRoute('cctp_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/newWord/{id}", name="cctp_newWord", methods={"GET", "POST"})
     * fonction qui affiche les différents éléments, pour les classer avant génération du doc
     */
    public function newWord(Cctp $cctp, OptionnelRepository $optionRepo, SessionInterface $session): Response
    {
        //recuperation des lots
        $lots = $cctp->getLotCctp();
        $nomLot = '';
        //boucle qui concatene les noms de lots 
        if (count($lots) > 1) {
            foreach ($lots as $lot) {
                $nomLot .= $lot . '; ';
            }
        } else {
            $nomLot = $lots[0]->getNomLot();
        }

        //récupération des différents éléments à afficher
        $produits = $cctp->getProduits();

        //$docstype = $doctype->findAll();

        $options = $optionRepo->findAll();

        $panier = $session->get("panier", []);

        //génération des différentes pages
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
        $phpWord->getSettings()->setUpdateFields(true);

        $section = $phpWord->addSection();

        $footer = $section->addFooter();

        //style du pied de page
        $tableStyle = array(
            'cellMarginTop' => 50,
            'align' => 'center'
        );
        //organisation du footer en tableau
        $table = $footer->addTable($tableStyle);
        $row = $table->addRow();
        $row->addCell(3000);
        $row->addCell(3000, array('vMerge' => 'restart'))->addImage('uploads/logoCombiosol.png', array('align' => 'center'));
        $row->addCell(3000);

        $row = $table->addRow();
        $row->addCell(3000, array('borderBottomSize' => 9, 'borderBottomColor' => 'ff8000'))->addPreserveText('Le ' . date('d-m-Y'));
        $row->addCell(3000, array('vMerge' => 'continue'));
        $row->addCell(3000, array('borderBottomSize' => 9, 'borderBottomColor' => 'ff8000'))->addtext($cctp->getNomOperation());

        $row = $table->addRow();
        $row->addCell(3000)->addPreserveText('Page {PAGE} of {NUMPAGES}.',);
        $row->addCell(3000, array('vMerge' => 'continue'));
        $row->addCell(3000)->addText('Lot : ' . $nomLot);

        $phpWord->setDefaultParagraphStyle(
            array(
                'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(10),
            )
        );
        //style des titres
        $phpWord->addTitleStyle(1, array('size' => 25, 'bold' => true, 'valign' => 'center'));
        $phpWord->addTitleStyle(2, array('size' => 20, 'color' => '#0A9B14', 'bold' => true));
        $phpWord->addTitleStyle(3, array('size' => 16, 'color' => '#1abc9c'));
        $phpWord->addTitleStyle(4, array('size' => 14, 'color' => '#1abc9c'));


        //table des matières
        $section->addTOC(['tabPos']);
        $section->addPageBreak();

        //début doctype
        //généralité
        
        //$section->addTitle('INTRODUCTION', 1);
        $section->addTextBreak();

        foreach ($produits as $prod) {
            if ($prod->getLot() == 'Spécifications générales communes') {

                $section->addTitle($prod->getTitle(), 1);
                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                //vérification dans la session, si l'élément a une partie modifiable enregistrée
                if (isset($panier[$prod->getId()])) {
                    //affichage de la partie modifiée
                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $panier[$prod->getId()], false, false);
                }
                //sinon affichage de la partie modifiable d'origine
                else {
                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getModifiable(), false, false);
                }
            }
        }
        

        //affichage des normes liées aux lots du cctp
        //$countTitle = 0;
        /* ($lots as $lot) {
            if ($lot != 'Spécifications générales communes') {
                $countTitle = 1;
                $countContentG = 1;
                $countContentD = 1;
                $countContentE = 1;
                foreach ($lot->getProduits() as $prod) {

                    if (substr($prod->getNomProduit(), 0, 6) == 'Normes' && $countTitle == 1) {
                        $section->addTitle(strtoupper($lot->getNomLot()), 3);
                        $countTitle += 1;
                    }

                    if ($prod->getLot() == $lot && $prod->getSysteme()->getNomSysteme() == "Générateur" && substr($prod->getNomProduit(), 0, 6) == 'Normes' && $countContentG == 1) {
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                        $countContentG += 1;
                    }
                    if ($prod->getLot() == $lot && $prod->getSysteme()->getNomSysteme() == "Distribution" && substr($prod->getNomProduit(), 0, 6) == 'Normes' && $countContentD == 1) {
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                        $countContentD += 1;
                    }
                    if ($prod->getLot() == $lot && $prod->getSysteme()->getNomSysteme() == "Emétteur" && substr($prod->getNomProduit(), 0, 6) == 'Normes' && $countContentE == 1) {
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                        $countContentE += 1;
                    }
                }
            }
        }*/
        //suite doctype
        //acoustique
        /*foreach($docstype as $doctype){
            $section->addTitle($doctype->getTitle(), 2);
            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $doctype->getContent(), false, false);
            if($doctype->getTitle() == 'ACOUSTIQUE'){
                foreach ($options as $option) {
                    $section->addTitle($option->getTitle(), 3);
                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $option->getContent(), false, false);
                }
            }
        
        }
        $section->addTitle($doctype->getTitle(), 2);
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $doctype->getContent(), false, false);
        
        $section->addPageBreak();*/



        $countLot = 0;
        $nomLocalisation = null;
        foreach ($lots as $lot) {
            if($lot != 'Spécifications générales communes' && $lot != "	Condition de réception de l'Installation	Condition de réception de l'Installation")
            {
                $section->addTitle($lot->getNomLot(), 1);
                foreach ($lot->getSystemes() as $systeme) {

                    //dd($systeme);
                    if (isset($systeme) && substr($systeme->getNomSysteme(), 0, 5) == 'Carac'){
                        $section->addTitle($systeme->getNomSysteme(), 2);
                        foreach ($produits as $prod) {
    
                            if ($prod->getSysteme() != null && $prod->getSysteme()->getNomSysteme() == $systeme && $prod->getLot()->getNomLot() == $lot) {
                                //ajout +1 au compteur de sous paragraphes
                                //$count += 1;
                                if($prod->getTitle() != null){
                                    $section->addTitle($prod->getTitle(), 3);
                                }
                                
                                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                                if ($prod->getNomProduit() == "Module thermique d'appartement chauffage indirect seul") {
                                    $textrun1 = $section->addTextRun();
                                    $source1 = file_get_contents('../public/uploads/images/moduleThermique1.png');
                                    $textrun1->addImage($source1, array('width' => 450));
                                    $textrun2 = $section->addTextRun();
                                    $source2 = file_get_contents('../public/uploads/images/moduleThermique2.png');
                                    $textrun2->addImage($source2, array('width' => 450));
                                    $textrun3 = $section->addTextRun();
                                    $source3 = file_get_contents('../public/uploads/images/moduleThermique3.png');
                                    $textrun3->addImage($source3, array('width' => 450));
                                    $textrun4 = $section->addTextRun();
                                    $source4 = file_get_contents('../public/uploads/images/moduleThermique4.png');
                                    $textrun4->addImage($source4, array('width' => 450));
                                    $textrun5 = $section->addTextRun();
                                    $source5 = file_get_contents('../public/uploads/images/moduleThermique5.png');
                                    $textrun5->addImage($source5, array('width' => 450));
                                    $textrun6 = $section->addTextRun();
                                    $source6 = file_get_contents('../public/uploads/images/moduleThermique6.png');
                                    $textrun6->addImage($source6, array('width' => 450));
                                    $textrun7 = $section->addTextRun();
                                    $source7 = file_get_contents('../public/uploads/images/moduleThermique7.png');
                                    $textrun7->addImage($source7, array('width' => 450));
                                    $textrun8 = $section->addTextRun();
                                    $source8 = file_get_contents('../public/uploads/images/moduleThermique8.png');
                                    $textrun8->addImage($source8, array('width' => 450));
                                    $textrun9 = $section->addTextRun();
                                    $source9 = file_get_contents('../public/uploads/images/moduleThermique9.png');
                                    $textrun9->addImage($source9, array('width' => 450));
                                }
                                //vérification dans la session, si l'élément a une partie modifiable enregistrée
                                if (isset($panier[$prod->getId()])) {
                                    //affichage de la partie modifiée
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $panier[$prod->getId()], false, false);
                                }
                                //sinon affichage de la partie modifiable d'origine
                                else {
                                    if($prod->getModifiable() != null){
                                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getModifiable(), false, false);
                                    }
                                    
                                }
                                //boucle sur les données renseignées à la création du cctp
                                foreach ($prod->getDocFinals() as $info) {
                                    //si l'id du cctp correspond aux données renseignées
                                    if ($info->getCctpId() == $cctp->getId()) {
                                        //si les 3 champs sont renseignés
                                        if ($info->getType() != null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                            
                                            //affichage localisation
                                            if($nomLocalisation != $info->getLocalisation()){
                                                $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                                $nomLocalisation = $info->getLocalisation();
                                            }
                                            
                                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                            //affichage type
                                            $section->addText('Type : ' . $info->getType());
                                            //affichage quantité
                                            $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                        }
                                        //si la localisation n'est pas renseignée
                                        else if ($info->getType() != null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                            //affichage type
                                            $section->addText('Type : ' . $info->getType());
                                            //affichage quantité
                                            $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                        }
                                        //si le type n'est pas renseigné
                                        else if ($info->getType() == null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                            //affichage localisation
                                            if($nomLocalisation != $info->getLocalisation()){
                                            $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                            $nomLocalisation = $info->getLocalisation();
                                            }
                                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                            //affichage quantité
                                            $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                        }
                                        //s'il n'y a que la quantié de renseignée
                                        else if ($info->getType() == null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                            //affichage quantité
                                            $qte = '<p>Quantité : ' . $info->getQuantite() . '</p>';
                                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                }
                foreach ($lot->getSystemes() as $systeme) {

                    //dd($systeme);
                    if (isset($systeme) && substr($systeme->getNomSysteme(), 0, 5) != 'Carac'){
                        $section->addTitle($systeme->getNomSysteme(), 2);
                        foreach ($produits as $prod) {
    
                            if ($prod->getSysteme() != null && $prod->getSysteme()->getNomSysteme() == $systeme && $prod->getLot()->getNomLot() == $lot) {
                                //ajout +1 au compteur de sous paragraphes
                                //$count += 1;
                                if($prod->getTitle() != null){
                                    $section->addTitle($prod->getTitle(), 3);
                                }
                                
                                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                                if ($prod->getNomProduit() == "Module thermique d'appartement chauffage indirect seul") {
                                    $textrun1 = $section->addTextRun();
                                    $source1 = file_get_contents('../public/uploads/images/moduleThermique1.png');
                                    $textrun1->addImage($source1, array('width' => 450));
                                    $textrun2 = $section->addTextRun();
                                    $source2 = file_get_contents('../public/uploads/images/moduleThermique2.png');
                                    $textrun2->addImage($source2, array('width' => 450));
                                    $textrun3 = $section->addTextRun();
                                    $source3 = file_get_contents('../public/uploads/images/moduleThermique3.png');
                                    $textrun3->addImage($source3, array('width' => 450));
                                    $textrun4 = $section->addTextRun();
                                    $source4 = file_get_contents('../public/uploads/images/moduleThermique4.png');
                                    $textrun4->addImage($source4, array('width' => 450));
                                    $textrun5 = $section->addTextRun();
                                    $source5 = file_get_contents('../public/uploads/images/moduleThermique5.png');
                                    $textrun5->addImage($source5, array('width' => 450));
                                    $textrun6 = $section->addTextRun();
                                    $source6 = file_get_contents('../public/uploads/images/moduleThermique6.png');
                                    $textrun6->addImage($source6, array('width' => 450));
                                    $textrun7 = $section->addTextRun();
                                    $source7 = file_get_contents('../public/uploads/images/moduleThermique7.png');
                                    $textrun7->addImage($source7, array('width' => 450));
                                    $textrun8 = $section->addTextRun();
                                    $source8 = file_get_contents('../public/uploads/images/moduleThermique8.png');
                                    $textrun8->addImage($source8, array('width' => 450));
                                    $textrun9 = $section->addTextRun();
                                    $source9 = file_get_contents('../public/uploads/images/moduleThermique9.png');
                                    $textrun9->addImage($source9, array('width' => 450));
                                }
                                //vérification dans la session, si l'élément a une partie modifiable enregistrée
                                if (isset($panier[$prod->getId()])) {
                                    //affichage de la partie modifiée
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $panier[$prod->getId()], false, false);
                                }
                                //sinon affichage de la partie modifiable d'origine
                                else {
                                    if($prod->getModifiable() != null){
                                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getModifiable(), false, false);
                                    }
                                    
                                }
                                //boucle sur les données renseignées à la création du cctp
                                foreach ($prod->getDocFinals() as $info) {
                                    //si l'id du cctp correspond aux données renseignées
                                    if ($info->getCctpId() == $cctp->getId()) {
                                        //si les 3 champs sont renseignés
                                        if ($info->getType() != null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                            
                                            //affichage localisation
                                            if($nomLocalisation != $info->getLocalisation()){
                                                $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                                $nomLocalisation = $info->getLocalisation();
                                            }
                                            
                                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                            //affichage type
                                            $section->addText('Type : ' . $info->getType());
                                            //affichage quantité
                                            $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                        }
                                        //si la localisation n'est pas renseignée
                                        else if ($info->getType() != null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                            //affichage type
                                            $section->addText('Type : ' . $info->getType());
                                            //affichage quantité
                                            $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                        }
                                        //si le type n'est pas renseigné
                                        else if ($info->getType() == null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                            //affichage localisation
                                            if($nomLocalisation != $info->getLocalisation()){
                                            $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                            $nomLocalisation = $info->getLocalisation();
                                            }
                                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                            //affichage quantité
                                            $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                        }
                                        //s'il n'y a que la quantié de renseignée
                                        else if ($info->getType() == null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                            //affichage quantité
                                            $qte = '<p>Quantité : ' . $info->getQuantite() . '</p>';
                                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                }
            }
            
        }

        foreach ($produits as $prod) {
            if ($prod->getLot() == "Condition de réception de l'Installation") {
                $section->addTitle($prod->getTitle(), 3);
                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                //vérification dans la session, si l'élément a une partie modifiable enregistrée
                if (isset($panier[$prod->getId()])) {
                    //affichage de la partie modifiée
                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $panier[$prod->getId()], false, false);
                }
                //sinon affichage de la partie modifiable d'origine
                else {
                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getModifiable(), false, false);
                }
            }
        }





        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header("Content-Disposition: attachment; filename=" . date('Y') . "_" . $cctp->getNomOperation() . ".docx");
        header('Cache-Control: max-age=0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $xmlWriter->save("php://output");

        //return $this->redirectToRoute('cctp_new_acceuil_word', [], Response::HTTP_SEE_OTHER);


        //header('Content-Type: application/octet-stream');
        //génération du document
        //$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007'); 
        //header("Content-Disposition: attachment; filename=".date('Y')."_".$cctp->getNomOperation().".docx");
        //$objWriter->save("C:\docs\\".date("Y")."_".$cctp->getNomOperation().'.docx');


        //affichage d'un message si l'opération s"est déroulée avec succès
        $this->addFlash('message', 'Document crée avec succès');
        //redirection vers la page qui liste les cctp
        return $this->redirectToRoute('cctp_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/newWord1/{id}", name="cctp_newWord1", methods={"GET", "POST"})
     * fonction qui génère le document en word
     */
    public function newWord1(Cctp $cctp, OptionnelRepository $optionRepo, SessionInterface $session): Response
    {
        //concatenation des noms de lots s'il y'en a plusieurs
        $lots = $cctp->getLotCctp();
        if (isset($lots[2]) && $lots[2] != null) {
            $nomLot = $lots[0]->getNomLot() . " , " . $lots[1]->getNomLot() . " , " . $lots[2]->getNomLot();
        } else if (isset($lots[1]) && $lots[1] != null && !isset($lots[2])) {
            $nomLot = $lots[0]->getNomLot() . " , " . $lots[1]->getNomLot();
        } else {
            $nomLot = $lots[0]->getNomLot();
        }

        //récupération des différents éléments à afficher
        $produits = $cctp->getProduits();

        $options = $optionRepo->findAll();

        $panier = $session->get("panier", []);


        //génération des différentes pages
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
        $phpWord->getSettings()->setUpdateFields(true);

        $section = $phpWord->addSection();
        //$pageAcceuil = $phpWord->loadTemplate($pathToSave);
        //$section->addOLEObject($objWriter);

        //\PhpOffice\PhpWord\Shared\Html::addHtml($section, $pageAcceuil, false, false);
        $footer = $section->addFooter();

        //style du pied de page
        $tableStyle = array(
            'cellMarginTop' => 50,
            'align' => 'center'
        );
        //organisation du footer en tableau
        $table = $footer->addTable($tableStyle);
        $row = $table->addRow();
        $row->addCell(3000);
        $row->addCell(3000, array('vMerge' => 'restart'))->addImage('uploads/logoCombiosol.png', array('align' => 'center'));
        $row->addCell(3000);

        $row = $table->addRow();
        $row->addCell(3000, array('borderBottomSize' => 9, 'borderBottomColor' => 'ff8000'))->addPreserveText('Le ' . date('d-m-Y'));
        $row->addCell(3000, array('vMerge' => 'continue'));
        $row->addCell(3000, array('borderBottomSize' => 9, 'borderBottomColor' => 'ff8000'))->addtext($cctp->getNomOperation());

        $row = $table->addRow();
        $row->addCell(3000)->addPreserveText('Page {PAGE} of {NUMPAGES}.',);
        $row->addCell(3000, array('vMerge' => 'continue'));
        $row->addCell(3000)->addText('Lot : ' . $nomLot);

        /*$styleCell =
            [
                'borderColor' => 'ff0000',
                'borderSize' => 6,
            ];*/

        $phpWord->setDefaultParagraphStyle(
            array(
                'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(10),
            )
        );
        //style des titres
        $phpWord->addTitleStyle(1, array('size' => 25, 'bold' => true, 'valign' => 'center'));
        $phpWord->addTitleStyle(2, array('size' => 20, 'color' => '#0A9B14', 'bold' => true));
        $phpWord->addTitleStyle(3, array('size' => 16, 'color' => '#1abc9c'));
        $phpWord->addTitleStyle(4, array('size' => 14, 'color' => '#1abc9c'));


        //table des matières
        //$section->addTitle('SOMMAIRE', 1);
        $section->addTOC(['tabPos']);
        $section->addPageBreak();

        

        //affichage des normes liées aux lots du cctp
        foreach ($lots as $lot) {
            if ($lot == 'Chauffage') {
                $section->addTitle('CHAUFFAGE', 3);
                foreach ($produits as $prod) {
                    if ($prod->getId() == 1) {
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                    }
                    if ($prod->getId() == 4) {
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                    }
                    if ($prod->getId() == 8) {
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                    }
                }
            }
            if ($lot == 'Plomberie') {
                $section->addTitle('PLOMBERIE', 3);
                foreach ($produits as $prod) {
                    if ($prod->getId() == 50) {
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                    }
                    if ($prod->getId() == 54) {
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                    }
                    if ($prod->getId() == 58) {
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                    }
                }
            }
            if ($lot == 'Ventilation') {
                $section->addTitle('VENTILATION', 3);
                foreach ($produits as $prod) {
                    if ($prod->getId() == 99) {
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                    }
                    if ($prod->getId() == 103) {
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                    }
                    if ($prod->getId() == 106) {
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                    }
                }
            }
        }
        

        //initialisation du compteur de paragraphes
        $countLot = 4;

        //boucle sur les lots du cctp
        foreach ($lots as $lot) {

            //si lot chauffage
            if ($lot->getNomLot() == "Chauffage") {
                //ajout +1 au comteur de paragraphes
                $countLot += 1;
                /*$section->addTitle($docstype[19]->getTitle(), 2);
                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $docstype[19]->getContent(), false, false);
                $section->addTitle($docstype[20]->getTitle(), 3);
                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $docstype[20]->getContent(), false, false);
                $section->addPageBreak();*/
                $section->addTitle(/*$countLot.*/'PRODUCTION DE CHAUFFAGE', 2);

                //initialisation du compteur de sous paragraphes
                $count = 0;
                //boucle sur les éléments du cctp
                foreach ($produits as $prod) {
                    //si l'élément est du lot chauffage et du système générateur affichage ici
                    if ($prod->getSysteme() == "Générateur" && $prod->getLot() == "Chauffage" && $prod->getId() != 1) {
                        //ajout +1 au compteur de sous paragraphes
                        $count += 1;
                        $section->addTitle(/*$countLot.'.2.'.$count.'. '.*/$prod->getTitle(), 3);
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                        //vérification dans la session, si l'élément a une partie modifiable enregistrée
                        if (isset($panier[$prod->getId()])) {
                            //affichage de la partie modifiée
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $panier[$prod->getId()], false, false);
                        }
                        //sinon affichage de la partie modifiable d'origine
                        else {
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getModifiable(), false, false);
                        }
                        //boucle sur les données renseignées à la création du cctp
                        foreach ($prod->getDocFinals() as $info) {
                            //si l'id du cctp correspond aux données renseignées
                            if ($info->getCctpId() == $cctp->getId()) {
                                //si les 3 champs sont renseignés
                                if ($info->getType() != null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affichage localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si la localisation n'est pas renseignée
                                else if ($info->getType() != null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si le type n'est pas renseigné
                                else if ($info->getType() == null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affichage localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //s'il n'y a que la quantié de renseignée
                                else if ($info->getType() == null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                            }
                        }
                    }
                }
                $section->addPageBreak();
                $section->addTitle(/*$countLot.*/' DISTRIBUTION DE CHAUFFAGE', 2);
                //réintialisation du compteur de sous paragraphes
                $count = 0;
                //boucle sur les éléments
                foreach ($produits as $prod) {
                    //si l'élément est du lot chauffage et du système distribution affichage ici
                    if ($prod->getSysteme() == "Distribution" && $prod->getLot() == "Chauffage" && $prod->getId() != 4) {
                        //ajout +1 au compteur de sous paragraphes
                        $count += 1;
                        $section->addTitle(/*$countLot.'.3.'.$count.'. '.*/$prod->getTitle(), 3);
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                        //si l'élément a l'id 49 affichage des images liées au document (car elles prennent trop de place encodées en base64)
                        if ($prod->getId() == 49) {
                            $textrun1 = $section->addTextRun();
                            $source1 = file_get_contents('../public/uploads/images/moduleThermique1.png');
                            $textrun1->addImage($source1, array('width' => 450));
                            $textrun2 = $section->addTextRun();
                            $source2 = file_get_contents('../public/uploads/images/moduleThermique2.png');
                            $textrun2->addImage($source2, array('width' => 450));
                            $textrun3 = $section->addTextRun();
                            $source3 = file_get_contents('../public/uploads/images/moduleThermique3.png');
                            $textrun3->addImage($source3, array('width' => 450));
                            $textrun4 = $section->addTextRun();
                            $source4 = file_get_contents('../public/uploads/images/moduleThermique4.png');
                            $textrun4->addImage($source4, array('width' => 450));
                            $textrun5 = $section->addTextRun();
                            $source5 = file_get_contents('../public/uploads/images/moduleThermique5.png');
                            $textrun5->addImage($source5, array('width' => 450));
                            $textrun6 = $section->addTextRun();
                            $source6 = file_get_contents('../public/uploads/images/moduleThermique6.png');
                            $textrun6->addImage($source6, array('width' => 450));
                            $textrun7 = $section->addTextRun();
                            $source7 = file_get_contents('../public/uploads/images/moduleThermique7.png');
                            $textrun7->addImage($source7, array('width' => 450));
                            $textrun8 = $section->addTextRun();
                            $source8 = file_get_contents('../public/uploads/images/moduleThermique8.png');
                            $textrun8->addImage($source8, array('width' => 450));
                            $textrun9 = $section->addTextRun();
                            $source9 = file_get_contents('../public/uploads/images/moduleThermique9.png');
                            $textrun9->addImage($source9, array('width' => 450));
                        }
                        //vérification dans la session, si l'élément a une partie modifiable enregistrée
                        if (isset($panier[$prod->getId()])) {
                            //affichage de la partie modifiée
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $panier[$prod->getId()], false, false);
                        }
                        //sinon affichage de la partie modifiable d'origine
                        else {
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getModifiable(), false, false);
                        }
                        //boucle sur les données renseignées à la création du cctp
                        foreach ($prod->getDocFinals() as $info) {
                            //si l'id du cctp correspond aux données renseignées
                            if ($info->getCctpId() == $cctp->getId()) {
                                //si les 3 champs sont renseignés
                                if ($info->getType() != null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affichage localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si la localisation n'est pas renseignée
                                else if ($info->getType() != null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si le type n'est pas renseigné
                                else if ($info->getType() == null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affichage localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //s'il n'y a que la quantié de renseignée
                                else if ($info->getType() == null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                            }
                        }
                    }
                }
                $section->addTitle(/*$countLot.".4. */"SYSTEME D'EMISSION DE CHAUFFAGE", 2);
                //réinitialisation du compteur de sous chapitres
                $count = 0;
                //boucle sur les éléments
                foreach ($produits as $prod) {
                    //si l'élément est du lot chauffage et du système émétteur affichage ici
                    if ($prod->getSysteme() == "Emétteur" && $prod->getLot() == "Chauffage" && $prod->getId() != 8) {
                        //ajout +1 au compteur de sous chapitres
                        $count += 1;
                        $section->addTitle(/*$countLot.'.4.'.$count.'. '.*/$prod->getTitle(), 3);
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                        //vérification dans la session, si l'élément a une partie modifiable enregistrée
                        if (isset($panier[$prod->getId()])) {
                            //affichage de la partie modifiée
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $panier[$prod->getId()], false, false);
                        }
                        //sinon affichage de la partie modifiable d'origine
                        else {
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getModifiable(), false, false);
                        }
                        //boucle sur les données renseignées à la création du cctp
                        foreach ($prod->getDocFinals() as $info) {
                            //si l'id du cctp correspond aux données renseignées
                            if ($info->getCctpId() == $cctp->getId()) {
                                //si les 3 champs sont renseignés
                                if ($info->getType() != null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affichage localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si la localisation n'est pas renseignée
                                else if ($info->getType() != null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si le type n'est pas renseigné
                                else if ($info->getType() == null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affchage localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //s'il n'y a que la quantité de renseignée
                                else if ($info->getType() == null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                            }
                        }
                    }
                }
            }
            //si lot chauffage
            else if ($lot->getNomLot() == "Plomberie") {
                //ajout +1 au compteur de paragraphes
                $countLot += 1;
                $section->addPageBreak();
                

                $section->addTitle(/*$countLot.'.*/'PRODUCTION D ECS', 2);
                //réinitialisation du compteur de sous paragraphes
                $count = 0;
                //boucle sur les éléments
                foreach ($produits as $prod) {
                    //si l'élément est du lot plomberie et du système générateur
                    if ($prod->getSysteme() == "Générateur" && $prod->getLot() == "Plomberie" && $prod->getId() != 50) {
                        //ajout +1 au compteur de sous paragraphes
                        $count += 1;
                        $section->addTitle(/*$countLot.'.4.'.$count.'. '.*/$prod->getTitle(), 3);
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                        //vérification dans la session, si l'élément a une partie modifiable enregistrée
                        if (isset($panier[$prod->getId()])) {
                            //affichage de la partie modifiée
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $panier[$prod->getId()], false, false);
                        }
                        //sinon affichage de la partie modifiable d'origine
                        else {
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getModifiable(), false, false);
                        }
                        //boucle sur les données renseignées à la création du cctp
                        foreach ($prod->getDocFinals() as $info) {
                            //si l'id du cctp correspond aux données renseignées
                            if ($info->getCctpId() == $cctp->getId()) {
                                //si les 3 champs sont renseignés
                                if ($info->getType() != null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affichage localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si la localisation n"est pas renseignée
                                else if ($info->getType() != null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si le type n'est pas renseigné
                                else if ($info->getType() == null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affichage localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //s'il n'y a que la quantité de renseignée
                                else if ($info->getType() == null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                            }
                        }
                    }
                }
                $section->addTitle(/*$countLot.'.'5.*/'DISTRIBUTION', 2);
                //réinitialisation du compteur de sous paragraphes
                $count = 0;
                //boucle sur les éléments
                foreach ($produits as $prod) {
                    //si l'élément est du lot plomberie et du système distribution affichage ici
                    if ($prod->getSysteme() == "Distribution" && $prod->getLot() == "Plomberie" && $prod->getId() != 54) {
                        //ajout +1 au compteur de sous paragraphes
                        $count += 1;
                        $section->addTitle(/*$countLot.'.5.'.$count.'. '.*/$prod->getTitle(), 3);
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                        //vérification dans la session, si l'élément a une partie modifiable enregistrée
                        if (isset($panier[$prod->getId()])) {
                            //affichage de la partie modifiée
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $panier[$prod->getId()], false, false);
                        }
                        //sinon affichage de la partie modifiable d'origine
                        else {
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getModifiable(), false, false);
                        }
                        //boucle sur les données renseignées à la création du cctp
                        foreach ($prod->getDocFinals() as $info) {
                            //si l'id du cctp correspond aux données renseignées
                            if ($info->getCctpId() == $cctp->getId()) {
                                //si les 3 champs sont renseignés
                                if ($info->getType() != null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affichage de la localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage du type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage de la quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si la localisation n'est pas renseignée
                                else if ($info->getType() != null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage du type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage de la quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si le type n'est pas renseigné
                                else if ($info->getType() == null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affichage de la localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage de la quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //s'il n'y a que la quantité de renseignée
                                else if ($info->getType() == null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage de la quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                            }
                        }
                    }
                }
                $section->addTitle(/*$countLot.'.6. */'APPAREILS SANITAIRES', 2);
                //réinitialisation du compteur de sous paragraphes
                $count = 0;
                //boucle sur les éléments
                foreach ($produits as $prod) {
                    //si l'élément est du lot plomberie et du système émétteur affichage ici
                    if ($prod->getSysteme() == "Emétteur" && $prod->getLot() == "Plomberie" && $prod->getId() != 58) {
                        //ajout +1 au compteur de sous paragraphes
                        $count += 1;
                        $section->addTitle(/*$countLot.'.6.'.$count.'. '.*/$prod->getTitle(), 3);
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                        //vérification dans la session, si l'élément a une partie modifiable enregistrée
                        if (isset($panier[$prod->getId()])) {
                            //affichage de la partie modifiée
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $panier[$prod->getId()], false, false);
                        }
                        //sinon affichage de la partie modifiable d'origine
                        else {
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getModifiable(), false, false);
                        }
                        //boucle sur les données renseignées à la création du cctp
                        foreach ($prod->getDocFinals() as $info) {
                            //si l'id du cctp correspond aux données renseignées
                            if ($info->getCctpId() == $cctp->getId()) {
                                //si les 3 champs sont renseignés
                                if ($info->getType() != null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affichage localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage du type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si la localisation n'est pas renseignée
                                else if ($info->getType() != null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage du type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si le type n'est pas renseigné
                                else if ($info->getType() == null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affichage de la localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //s'il n'y a que la quantité de renseignée
                                else if ($info->getType() == null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                            }
                        }
                    }
                }
                
            }
            //si lot ventilation
            else if ($lot->getNomLot() == "Ventilation") {
                

                $section->addTitle(/*$countLot.'.3. */'EXTRACTEURS', 2);
                //réinitialisation du compteur de sous paragraphes
                $count = 0;
                //boucle sur les éléments
                foreach ($produits as $prod) {
                    //si l'élément est du lot ventilation et du système générateur
                    if ($prod->getSysteme() == "Générateur" && $prod->getLot() == "Ventilation" && $prod->getId() != 99) {
                        //ajout +1 au compteur de sous paragraphes
                        $count += 1;
                        $section->addTitle(/*$countLot.'.3.'.$count.'. '.*/$prod->getTitle(), 3);
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                        //vérification dans la session, si l'élément a une partie modifiable enregistrée
                        if (isset($panier[$prod->getId()])) {
                            //afichage de la partie modifiée
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $panier[$prod->getId()], false, false);
                        }
                        //sinon affichage de la partie modifiable d'origine
                        else {
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getModifiable(), false, false);
                        }
                        //boucle sur les données renseignées à la création du cctp
                        foreach ($prod->getDocFinals() as $info) {
                            //si l'id du cctp correspond aux données renseignées
                            if ($info->getCctpId() == $cctp->getId()) {
                                //si les 3 champs sont renseignés
                                if ($info->getType() != null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //afichage localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage du type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si la localisation n'est pas renseignée
                                else if ($info->getType() != null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage du type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si le type n'est pas renseigné
                                else if ($info->getType() == null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //afichage localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //s'il n'y a que la quantité de renseignée
                                else if ($info->getType() == null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                            }
                        }
                    }
                }
                $section->addTitle(/*$countLot.'.4. */'RESEAUX', 2);
                //réinitialisation du compteur de sous paragraphes
                $count = 0;
                //boucle sur les éléments
                foreach ($produits as $prod) {
                    //si l'élément est du lot ventilation et du système distribution
                    if ($prod->getSysteme() == "Distribution" && $prod->getLot() == "Ventilation" && $prod->getId() != 103) {
                        //ajout +1 au compteur de sous paragraphes
                        $count += 1;
                        $section->addTitle(/*$countLot.'.4.'.$count.'. '.*/$prod->getTitle(), 3);
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                        //vérification dans la session, si l'élément a une partie modifiable enregistrée
                        if (isset($panier[$prod->getId()])) {
                            //affichage de la partie modifiée
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $panier[$prod->getId()], false, false);
                        }
                        //sinon affichage de la partie modifiable d'origine
                        else {
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getModifiable(), false, false);
                        }
                        //boucle sur les données renseignées à la création du cctp
                        foreach ($prod->getDocFinals() as $info) {
                            //si l'id du cctp correspond aux données renseignées
                            if ($info->getCctpId() == $cctp->getId()) {
                                //si les 3 champs sont renseignés
                                if ($info->getType() != null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affichage localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si la localisation n"est pas renseignée
                                else if ($info->getType() != null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si le type n'est pas renseigné
                                else if ($info->getType() == null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affichage localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //s'il n'y a que la quantité de renseignée
                                else if ($info->getType() == null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                            }
                        }
                    }
                }
                $section->addTitle(/*$countLot.'.5. */'TERMINAUX', 2);
                //réinitialisation du compteur de sous paragraphes
                $count = 0;
                //boucle sur les éléments
                foreach ($produits as $prod) {
                    //si l'élément est du lot ventilation et du système émétteur affichage ici
                    if ($prod->getSysteme() == "Emétteur" && $prod->getLot() == "Ventilation" && $prod->getId() != 106 && $prod->getId() != 115) {
                        //ajout +1 au compteur de sous paragraphes
                        $count += 1;
                        $section->addTitle(/*$countLot.'.5.'.$count.'. '.*/$prod->getTitle(), 3);
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                        //vérification dans la session, si l'élément a une partie modifiable enregistrée
                        if (isset($panier[$prod->getId()])) {
                            //affichage de la partie modifiée
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $panier[$prod->getId()], false, false);
                        }
                        //sinon affichage de la partie modifiable d'origine
                        else {
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getModifiable(), false, false);
                        }
                        //boucle sur les données renseignées à la création du cctp
                        foreach ($prod->getDocFinals() as $info) {
                            //si l'id du cctp correspond aux données renseignées
                            if ($info->getCctpId() == $cctp->getId()) {
                                //si les 3 champs sont renseignés
                                if ($info->getType() != null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affichage localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage du type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage de la quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si la localisation n"est pas renseignée
                                else if ($info->getType() != null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage du type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage de la quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si le type n'est pas renseigné
                                else if ($info->getType() == null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affichage localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage de la quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //s'il n'y a que la quantité de renseignée
                                else if ($info->getType() == null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage de la quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                            }
                        }
                    }
                }
                
            }
            //si lot éléctricité
            if ($lot->getNomLot() == "Electricité") {
                //ajout +1 au compteur de paragraphes
                $countLot += 1;
                $section->addTitle(/*$countLot.'. */'ELECTRICITE', 1);
                
            }
        }
        //ajout +1 au compteur de paragraphes
        $countLot += 1;
       

        //boucle sur les lots 
        foreach ($lots as $lot) {
            //si lot désenfumage
            if ($lot->getNomLot() == "Désenfumage") {
                $section->addTitle('Générateurs désenfumage', 3);
                //réinitialisation du compteur de sous paragraphes
                $count = 0;
                //boucle sur les éléments
                foreach ($produits as $prod) {
                    //si l'élément est du lot désenfumage et du système générateur
                    if ($prod->getSysteme() == "Générateur" && $prod->getLot() == "Désenfumage" && $prod->getId() != 125) {
                        //ajout +1 au compteur de sous paragraphes
                        $count += 1;
                        $section->addTitle(/*$count.'. '.*/$prod->getTitle(), 3);
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                        //vérification dans la session, si l'élément a une partie modifiable enregistrée
                        if (isset($panier[$prod->getId()])) {
                            //afichage de la partie modifiée
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $panier[$prod->getId()], false, false);
                        }
                        //sinon affichage de la partie modifiable d'origine
                        else {
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getModifiable(), false, false);
                        }
                        //boucle sur les données renseignées à la création du cctp
                        foreach ($prod->getDocFinals() as $info) {
                            //si l'id du cctp correspond aux données renseignées
                            if ($info->getCctpId() == $cctp->getId()) {
                                //si les 3 champs sont renseignés
                                if ($info->getType() != null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affichage localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si la localisation n'est pas renseignée
                                else if ($info->getType() != null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage du type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si le type n'est pas renseigné
                                else if ($info->getType() == null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affichage localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //s'il n'y a que la quantité de renseignée
                                else if ($info->getType() == null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                            }
                        }
                    }
                }
                $section->addTitle('Distribution désenfumage', 2);
                //réinitialisation du compteur de sous paragraphes
                $count = 0;
                //boucle sur les éléments
                foreach ($produits as $prod) {
                    //si l'élément est du lot désenfumage et du système distribution affichage ici
                    if ($prod->getSysteme() == "Distribution" && $prod->getLot() == "Désenfumage" && $prod->getId() != 128) {
                        //ajout +1 au compteur de sous paragraphes
                        $count += 1;
                        $section->addTitle(/*$count.'. '.*/$prod->getTitle(), 3);
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                        //vérification dans la session, si l'élément a une partie modifiable enregistrée
                        if (isset($panier[$prod->getId()])) {
                            //affichage de la partie modifiée
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $panier[$prod->getId()], false, false);
                        }
                        //sinon affichage de la partie modifiable d'origine
                        else {
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getModifiable(), false, false);
                        }
                        //boucle sur les données renseignées à la création du cctp
                        foreach ($prod->getDocFinals() as $info) {
                            //si l'id du cctp correspond aux données renseignées
                            if ($info->getCctpId() == $cctp->getId()) {
                                //si les 3 champs sont renseignés
                                if ($info->getType() != null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affichage localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si la localisation n'est pas renseignée
                                else if ($info->getType() != null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si le type n'est pas renseigné
                                else if ($info->getType() == null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affichage localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //s'il n'y a que la quantité de renseignée
                                else if ($info->getType() == null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                            }
                        }
                    }
                }
                $section->addTitle('Emétteur désenfumage', 2);
                //réinitialisation du compteur de sous paragraphes
                $count = 0;
                //boucle sur les éléments
                foreach ($produits as $prod) {
                    //si l'élément est du lot désenfumage et du système émétteur affichage ici
                    if ($prod->getSysteme() == "Emétteur" && $prod->getLot() == "Désenfumage" && $prod->getId() != 131) {
                        //ajout +1 au compteur de sous paragraphes
                        $count += 1;
                        $section->addTitle(/*$count.'. '.*/$prod->getTitle(), 3);
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                        //vérification dans la session, si l'élément a une partie modifiable enregistrée
                        if (isset($panier[$prod->getId()])) {
                            //affichage de la partie modifiée
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $panier[$prod->getId()], false, false);
                        }
                        //sinon affichage de la partie modifiable d'origine
                        else {
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getModifiable(), false, false);
                        }
                        //boucle sur les données renseignées à la création du cctp
                        foreach ($prod->getDocFinals() as $info) {
                            //si l'id du cctp correspond aux données renseignées
                            if ($info->getCctpId() == $cctp->getId()) {
                                //si les 3 champs sont renseignés
                                if ($info->getType() != null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affichage localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si la localisation n'est pas renseignée
                                else if ($info->getType() != null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si le type n'est pas renseigné
                                else if ($info->getType() == null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affichage localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //s'il n'ya que la quantité de renseignée
                                else if ($info->getType() == null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                            }
                        }
                    }
                }
            }
            //si lot air comprimé
            if ($lot->getNomLot() == "Air Comprimé") {
                $section->addTitle('Générateurs Air Comprimé', 2);
                //réinitialisation du compteur de sous paragraphes
                $count = 0;
                //boucle sur les éléments
                foreach ($produits as $prod) {
                    //si l'élément est du lot air comprimé et du système générateur affichage ici
                    if ($prod->getSysteme() == "Générateur" && $prod->getLot() == "Air Comprimé" && $prod->getId() != 134) {
                        //ajout +1 au compteur de sous paragraphes
                        $count += 1;
                        $section->addTitle(/*$count.'. '.*/$prod->getTitle(), 3);
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                        //vérification dans la session, si l'élément a une partie modifiable enregistrée
                        if (isset($panier[$prod->getId()])) {
                            //afichage de la partie modifiée
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $panier[$prod->getId()], false, false);
                        }
                        //sinon affichage de la partie modifiable d'origine
                        else {
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getModifiable(), false, false);
                        }
                        //boucle sur les données renseignées à la création du cctp
                        foreach ($prod->getDocFinals() as $info) {
                            //si l'id du cctp correspond aux données renseignées
                            if ($info->getCctpId() == $cctp->getId()) {
                                //si les 3 champs sont renseignés
                                if ($info->getType() != null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affichage localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si la localisation n'est pas renseignée
                                else if ($info->getType() != null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si le type n'est pas renseigné
                                else if ($info->getType() == null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affichage localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //s'il n'y a que la quantité de renseignée
                                else if ($info->getType() == null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                            }
                        }
                    }
                }
                $section->addTitle('Distribution Air Comprimé', 2);
                //réinitialisation du compteur de sous paragraphes
                $count = 0;
                //boucle sur les éléments
                foreach ($produits as $prod) {
                    //si l'élément est du lot air comprimé et du système distribution affichage ici
                    if ($prod->getSysteme() == "Distribution" && $prod->getLot() == "Air Comprimé" && $prod->getId() != 136) {
                        //ajout +1 au compteur de sous paragraphes
                        $count += 1;
                        $section->addTitle(/*$count.'. '.*/$prod->getTitle(), 3);
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                        //vérification dans la session, si l'élément a une partie modifiable enregistrée
                        if (isset($panier[$prod->getId()])) {
                            //affichage de la partie modifiée
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $panier[$prod->getId()], false, false);
                        }
                        //sinon affichage de la partie modifiable d'origine
                        else {
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getModifiable(), false, false);
                        }
                        //boucle sur les données renseignées à la création du cctp
                        foreach ($prod->getDocFinals() as $info) {
                            //si l'id du cctp correspond aux données renseignées
                            if ($info->getCctpId() == $cctp->getId()) {
                                //si les 3 champs sont renseignés
                                if ($info->getType() != null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affichage localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si la localisation n'est pas renseignée
                                else if ($info->getType() != null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si le type n'est pas renseigné
                                else if ($info->getType() == null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affichage localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //s'il n'y a que la quantité de renseignée
                                else if ($info->getType() == null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                            }
                        }
                    }
                }
                $section->addTitle('Emétteur Air Comprimé', 2);
                //réinitialisation du compteur de sous paragraphes
                $count = 0;
                //boucle sur les éléments
                foreach ($produits as $prod) {
                    //si l'élément est du lot air comprimé et du sytème émétteur affichage ici
                    if ($prod->getSysteme() == "Emétteur" && $prod->getLot() == "Air Comprimé" && $prod->getId() != 140) {
                        //ajout +1 au compteur de sous paragraphes
                        $count += 1;
                        $section->addTitle(/*$count.'. '.*/$prod->getTitle(), 3);
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getContent(), false, false);
                        //vérification dans la session, si l'élément a une partie modifiable enregistrée
                        if (isset($panier[$prod->getId()])) {
                            //affichage de la partie modifiée
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $panier[$prod->getId()], false, false);
                        }
                        //sinon affichage de la partie modifiable d'origine
                        else {
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $prod->getModifiable(), false, false);
                        }
                        //boucle sur les données renseignées à la création du cctp
                        foreach ($prod->getDocFinals() as $info) {
                            //si l'id du cctp correspond aux données renseignées
                            if ($info->getCctpId() == $cctp->getId()) {
                                //si les 3 champs sont renseignés
                                if ($info->getType() != null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affichage localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si la localisation n'est pas renseignée
                                else if ($info->getType() != null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage type
                                    $section->addText('Type : ' . $info->getType());
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //si le type n'est pas renseigné
                                else if ($info->getType() == null && $info->getLocalisation() != null && $info->getQuantite() != null) {
                                    //affichage localisation
                                    $loca = '<p>Localisation : ' . $info->getLocalisation() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $loca, false, false);
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                                //s'il n'y a que la quantité de renseignée
                                else if ($info->getType() == null && $info->getLocalisation() == null && $info->getQuantite() != null) {
                                    //affichage quantité
                                    $qte = '<p>Quantité : ' . $info->getQuantite() . ' ' . $prod->getUnite() . '</p>';
                                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $qte, false, false);
                                }
                            }
                        }
                    }
                }
            }
        }
        //$file = date("Y")."_".$cctp->getNomOperation().'.docx';
        //header("Content-Description: File Transfer");
        //header('Content-Disposition: attachment; filename="' . $file . '"');
        //header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        //header('Content-Transfer-Encoding: binary');
        //header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        //header('Expires: 0');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header("Content-Disposition: attachment; filename=" . date('Y') . "_" . $cctp->getNomOperation() . ".docx");
        header('Cache-Control: max-age=0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $xmlWriter->save("php://output");

        //return $this->redirectToRoute('cctp_new_acceuil_word', [], Response::HTTP_SEE_OTHER);


        //header('Content-Type: application/octet-stream');
        //génération du document
        //$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007'); 
        //header("Content-Disposition: attachment; filename=".date('Y')."_".$cctp->getNomOperation().".docx");
        //$objWriter->save("C:\docs\\".date("Y")."_".$cctp->getNomOperation().'.docx');


        //affichage d'un message si l'opération s"est déroulée avec succès
        $this->addFlash('message', 'Document crée avec succès');
        //redirection vers la page qui liste les cctp
        return $this->redirectToRoute('cctp_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/edit", name="cctp_edit", methods={"GET","POST"})
     * fonction de mise à jour d'un cctp
     */
    public function edit(Request $request, Cctp $cctp, ManagerRegistry $doctrine): Response
    {
        //récupération du formulaire d'ajout
        $form = $this->createForm(CctpType::class, $cctp);
        //traitement des données saisies
        $form->handleRequest($request);

        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrement des modifications en bdd
            $doctrine->getManager()->flush();

            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'Mise à jour effectuée avec succès');
            //redirection vers la page qui liste les cctp
            return $this->redirectToRoute('cctp_index', [], Response::HTTP_SEE_OTHER);
        }
        //affichage du form 
        return $this->renderForm('admin/cctp/edit.html.twig', [
            'cctp' => $cctp,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="cctp_delete", methods={"POST"})
     * fonction de suppression d'un cctp par son id
     */
    public function delete(Request $request, Cctp $cctp, ManagerRegistry $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete' . $cctp->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($cctp);
            $entityManager->flush();
        }
        //affichage d'un message si l'opération s'est déroulée avec succès
        $this->addFlash('message', 'Cctp supprimé avec succès');
        //redirection vers la page qui liste les cctp
        return $this->redirectToRoute('cctp_index', [], Response::HTTP_SEE_OTHER);
    }
}
