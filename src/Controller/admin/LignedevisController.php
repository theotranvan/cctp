<?php

namespace App\Controller\admin;

use App\Entity\Catusage;
use App\Entity\Lignedevis;
use App\Entity\Specification;
use App\Entity\Typeusage;
use App\Form\CatusageType;
use App\Form\LignedevisType;
use App\Form\Lot2Type;
use App\Form\LotType;
use App\Form\ProduitSearchType;
use App\Form\SpecificationType;
use App\Repository\CatusageRepository;
use App\Repository\DevisRepository;
use App\Repository\LignedevisRepository;
use App\Repository\LotRepository;
use App\Repository\ProduitRepository;
use App\Repository\SpecificationRepository;
use App\Repository\TypeusageRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/lignedevis")
 * @package App\Controller\admin
 */
class LignedevisController extends AbstractController
{
    /**
     * @Route("/", name="lignedevis_index", methods={"GET","POST"})
     * fonction de recherche des informations d'un élément
     */
    public function index(ProduitRepository $produitRepo, LotRepository $lotRepo, LignedevisRepository $lignedevisRepo, SpecificationRepository $specificationRepo, Request $request): Response
    {
        /*$nomLot = null;
        //récupération des lots à afficher dans la liste déroulante
        $lot = $lotRepo->findAll();

        $form1 = $this->createForm(Lot2Type::class, $lot);
        //traitement des données saisies
        $form1->handleRequest($request);

        //si le form est soumis et valide
        if ($form1->isSubmitted() && $form1->isValid()) {
            //récupération des champs
            $data1 = $form1->getData();
            $nomLot = $data1['nom_lot'];
            //dd($lot);
        }
        
        //récupération des produits à afficher dans la liste déroulante
        if($nomLot != null){
            $produit = $nomLot->getProduits()->toArray();
            $form = $this->createForm(ProduitSearchType::class, $produit);
        }
        else{
            $produit = $produitRepo->findAll();
            $form = $this->createForm(ProduitSearchType::class, $produit);
        }*/
        
        //dd($produit);
        $produit = $produitRepo->findAll();
        //création du form 
        $form = $this->createForm(ProduitSearchType::class, $produit);
        //traitement des données saisies
        $form->handleRequest($request);
        
        //initialisation des variables
        $prod = null;
        $typeProd = null;
        $infoProd = null;
        $moyenne[0] = null;
        $year = [];

        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            //récupération des champs
            $data = $form->getData();
            $prod = $data['produit'];
            
            //si le champ types est renseigné
            if (isset($data['types'])) {
                $typeProd = $data['types'];
            }
            //récupération des données à afficher
            if ($prod != null) {
                //si le produit n'a pas de types différents
                if ($typeProd == null) {
                    $infoProd = $lignedevisRepo->findBy(['produit' => $prod]);

                    //récupération des moyennes
                    $moyenne = $specificationRepo->moyenne($prod->getId());
                    $moyAnn = $lignedevisRepo->moyenneAnnuelle($prod->getId());

                    //moyennes annuelles triées par années
                    for ($i = 0; $i < count($moyAnn); $i++) {

                        if ($moyAnn[$i]['year'] == date("Y")) {
                            $year[date("Y")] = $moyAnn[$i][1];
                        }
                        if ($moyAnn[$i]['year'] == date("Y")-1) {
                            $year[date("Y")-1] = $moyAnn[$i][1];
                        }
                        if ($moyAnn[$i]['year'] == date("Y")-2) {
                            $year[date("Y")-2] = $moyAnn[$i][1];
                        }
                        if ($moyAnn[$i]['year'] == date("Y")-3) {
                            $year[date("Y")-2] = $moyAnn[$i][1];
                        }
                    }
                    //si le produit a plusieurs types différents
                } else {
                    $infoProd = $lignedevisRepo->filtre($prod->getId(), $typeProd->getType());

                    //récupération des moyennes
                    $moyenne = $specificationRepo->moyenne($prod->getId(), $typeProd->getType());
                    $moyAnn = $lignedevisRepo->moyenneAnnuelle($prod->getId(), $typeProd->getType());

                    //dd($moyAnn);
                    for ($i = 0; $i < count($moyAnn); $i++) {

                        if ($moyAnn[$i]['year'] == date("Y")) {
                            $year[date("Y")] = $moyAnn[$i][1];
                        }
                        if ($moyAnn[$i]['year'] == date("Y") - 1) {
                            $year[date("Y") - 1] = $moyAnn[$i][1];
                        }
                        if ($moyAnn[$i]['year'] == date("Y") - 2) {
                            $year[date("Y") - 2] = $moyAnn[$i][1];
                        }
                        if ($moyAnn[$i]['year'] == date("Y") - 3) {
                            $year[date("Y") - 3] = $moyAnn[$i][1];
                        }
                    }
                }
            }
        }
        
        //affichage du formulaire
        return $this->render('admin/lignedevis/index1.html.twig', [
            'form' => $form->createView(),
            //'form1' => $form1->createView(),
            'prod' => $prod,
            'typeProd' => $typeProd,
            'infoProd' => $infoProd,
            'moyenne' => $moyenne[0],
            'year' => $year
        ]);
    }

    /**
     * @Route("/estimation_rapide", name="estimation", methods={"GET","POST"})
     * fonction d'estimation du cout des travaux
     */
    public function estim(Request $request, CatusageRepository $catUsageRepo, TypeusageRepository $typeusageRepo):Response
    {
        $typeusage = $typeusageRepo->findAll();

        $usageNom = [];
        $usageColor = [];
        $usageCount = [];

        foreach($typeusage as $typeUse){
            $usageNom[] = $typeUse->getNomUsage();
            $usageColor[] = $typeUse->getColor();
            $usageCount[] = count($typeUse->getChantiers());
        }
        
        $catUsage = $catUsageRepo->findAll();
        //dd($catUsage[0]->getTypeusages());
        $form = $this->createForm(CatusageType::class, $catUsage);
        $form->handleRequest($request);

        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $usage = $data['usage'];
            //dd($usage);
        }
        return $this->render("admin/devis/estimation.html.twig", [
            'form' => $form->createView(),
            'usageNom' => json_encode($usageNom),
            'usageColor' => json_encode($usageColor),
            'usageCount' => json_encode($usageCount)
        ]);
    }


    /**
     * @Route("/new/{id}", name="lignedevis_new", methods={"GET","POST"})
     * fonction de création d'une ligne devis
     */
    public function new(Request $request, DevisRepository $devisRepo, SpecificationRepository $specificationRepo, ManagerRegistry $doctrine, $id): Response
    {
        //instancitaion d'un nouvel objet lignedevis
        $lignedevi = new Lignedevis();
        //instancitaion d'un nouvel objet specifications
        $specification = new Specification();
        //récupération du devis par son id
        $devis = $devisRepo->findOneBy(['id' => $id]);
        //appel des 2 formulaires de création
        $form = $this->createForm(LignedevisType::class, $lignedevi);
        $form1 = $this->createForm(SpecificationType::class, $specification);
        //traitement des données saisies sur les 2 formulaires
        $form->handleRequest($request);
        $form1->handleRequest($request);

        //si le form est soumis et valide
        if ($form1->isSubmitted() && $form1->isValid()) {
            //enregistrement en bdd
            $entityManager = $doctrine->getManager();
            $entityManager->persist($specification);
            $entityManager->flush();
            //récupération de l'id de la ligne crée
            $lastId = $specification->getId();
            //récupération de l'id de l'élément lié a cette nouvelle ligne
            $produitId = $specification->getProduit();
        }
        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //récupération des informations nécessaires
            $lignedevi->setDevis($devis);
            $lignedevi->setSpecification($specificationRepo->findOneBy(['id' => $lastId]));
            $lignedevi->setProduit($produitId);
            //enregistrement en bdd
            $entityManager = $doctrine->getManager();
            $entityManager->persist($lignedevi);
            $entityManager->flush();

            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'ligne devis enregistrée avec succès');
            //redirection vers la page qui liste les éléments du devis
            return $this->redirectToRoute('devis_show', ['id' => $devis->getId()], Response::HTTP_SEE_OTHER);
        }
        //affichage des formulaires imbriqués
        return $this->renderForm('admin/lignedevis/new.html.twig', [
            'lignedevi' => $lignedevi,
            'form' => $form,
            'specification' => $specification,
            'form1' => $form1
        ]);
    }

    /**
     * @Route("/newexel/{id}/{data}", name="lignedevis_new_exel", methods={"GET","POST"})
     * fonction de création d'une ligne devis par lecture automatique d'exel
     */
    public function newExel(ProduitRepository $produitRepo, DevisRepository $devisRepo, SpecificationRepository $specificationRepo, $id, $data, SessionInterface $session, ManagerRegistry $doctrine) //: Response
    {
        //récupération du tableau exel dans la session
        $tabExel = $session->get("tabExel", []);

        //récupération du produit par son nom
        $prod = $produitRepo->findBy(['nom_produit' => $tabExel[$data][1]]);

        //récupération du devis par son id
        $devis = $devisRepo->find(['id' => $id]);

        //traitement de la partie spécification
        $specification = new Specification();
        $specification = $specification->setPrixUnitaire($tabExel[$data][4]);
        $specification = $specification->setProduit($prod[0]);

        //controle du type
        foreach ($prod[0]->getSpecifications() as $spec) {
            if ($spec === $tabExel[$data][6]) {
                $specification = $specification->setType($tabExel[$data][6]);
            }
        }

        //traitement de la partie lignedevis
        $lignedevi = new Lignedevis();
        $lignedevi = $lignedevi->setQuantite(intval($tabExel[$data][3]));


        //enregistrement en bdd
        $entityManager = $doctrine->getManager();
        $entityManager->persist($specification);
        $entityManager->flush();
        //récupération de l'id de la ligne insérée
        $lastId = $specification->getId();
        //récupération de l'élément lié aux informations saisies
        $produitId = $specification->getProduit();

        //traitement des informations nécessaires
        $lignedevi->setDevis($devis);
        $lignedevi->setSpecification($specificationRepo->findOneBy(['id' => $lastId]));
        $lignedevi->setProduit($produitId);
        //enregistrement en bdd
        $entityManager = $doctrine->getManager();
        $entityManager->persist($lignedevi);
        $entityManager->flush();
        
        //effacement de la ligne du tableau après traitement
        unset($tabExel[$data]);
        //enregistrement du tableau à jour en session
        $session->set("tabExel", $tabExel);

        //affichage du tableau 
        return $this->render('admin/doc_final/testLectureExel.html.twig', [
            'prod' => $tabExel,
            'devi' => $devis
        ]);
    }

    /**
     * @Route("/{id}/edit", name="lignedevis_edit", methods={"GET","POST"})
     * fonction de mise à jour d'une ligne devis
     */
    public function edit(Request $request, Lignedevis $lignedevi, SpecificationRepository $specificationRepo, ManagerRegistry $doctrine): Response
    {
        //récupération de la ligne concernée
        $specification = $specificationRepo->findOneBy(['id' => $lignedevi->getSpecification()]);
        //appel des formulaires de création
        $form = $this->createForm(LignedevisType::class, $lignedevi);
        $form1 = $this->createForm(SpecificationType::class, $specification);
        //traitement des données saisies
        $form->handleRequest($request);
        $form1->handleRequest($request);
        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrement des modifications en bdd
            $doctrine->getManager()->flush();
            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'ligne devis modifiée avec succès');
            //redirection vers la page qui liste les devis
            return $this->redirectToRoute('devis_index', [], Response::HTTP_SEE_OTHER);
        }
        //affichage du formulaire imbriqué
        return $this->renderForm('admin/lignedevis/edit.html.twig', [
            'lignedevi' => $lignedevi,
            'form' => $form,
            'form1' => $form1
        ]);
    }

    /**
     * @Route("/{id}", name="lignedevis_delete", methods={"POST"})
     * fonction de suppression d'une ligne devis par son id
     */
    public function delete(Request $request, Lignedevis $lignedevi, ManagerRegistry $doctrine): Response
    {
        $devis = $lignedevi->getDevis();
        if ($this->isCsrfTokenValid('delete' . $lignedevi->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($lignedevi);
            $entityManager->flush();
        }
        //affichage d'un message si l'opération s'est déroulée avec succès
        $this->addFlash('message', 'ligne devis supprimée avec succès');
        //redirection vers la page qui liste les devis
        return $this->redirectToRoute('devis_show', ['id' => $devis->getId()], Response::HTTP_SEE_OTHER);
    }
}
