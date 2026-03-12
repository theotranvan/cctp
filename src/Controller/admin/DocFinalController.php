<?php

namespace App\Controller\admin;

use App\Entity\Devis;
use App\Entity\DocFinal;
use App\Entity\Lignedevis;
use App\Entity\Lot;
use App\Entity\Produit;
use App\Entity\Specification;
use App\Entity\Systeme;
use App\Form\DocFinalType;
use App\Form\DocFiType;
use App\Form\DpgfType;
use App\Form\FileNameType;
use App\Repository\CctpRepository;
use App\Repository\DevisRepository;
use App\Repository\DocFinalRepository;
use App\Repository\LignedevisRepository;
use App\Repository\ProduitRepository;
use App\Repository\SpecificationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;

/**
 * @Route("admin/doc/final")
 * @package App\Controller\admin
 */
class DocFinalController extends AbstractController
{
    /**
     * @Route("/new/{idCctp}/{id}", name="doc_final_new", methods={"GET","POST"})
     * methode qui enregistre dans la session, la partie modifiable de l'élément
     */
    public function new(SessionInterface $session, Request $request, ProduitRepository $produitRepo, CctpRepository $cctpRepo, $id, $idCctp): Response
    {
        $cctp = $cctpRepo->findOneBy(['id' => $idCctp]);
        //instanciation d'un nouvel objet docfinal
        $docFinal = new DocFinal();
        //récupération de l'élément par son id
        $prodAModif = $produitRepo->findOneBy(['id' => $id]);
        //appel du form d'enregistrement partie modifiable
        $form = $this->createForm(DocFinalType::class, $prodAModif);
        //traitement des données saisies
        $form->handleRequest($request);

        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //récupération du contenu saisi
            $contenu = $form->getData();
            //appel du panier en session
            $panier = $session->get("panier", []);
            
            $id = $contenu->getId();
            //si le panier ne contient pas l'id de cet élément 
            if (empty($panier[$id])) {
                //ajout du contenu au panier
                $panier[$id] = $contenu->getModifiable();
            }
            //enregistrement du panier en session
            $session->set("panier", $panier);

            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'contenu enregistré avec succès');
            //redirection vers la page qui liste les éléments du cctp
            return $this->redirectToRoute('cctp_show', ['id' => $idCctp], Response::HTTP_SEE_OTHER);
        }
        //affichage du formulaire
        return $this->renderForm('admin/doc_final/new.html.twig', [
            'doc_final' => $docFinal,
            'form' => $form,
            'cctp' => $cctp
        ]);
    }

    /**
     * @Route("/dpgf/{idCctp}/{id}", name="doc_final_dpgf", methods={"GET","POST"})
     * methode d'enregistrement des données du dpgf
     */
    public function dpgf(Produit $prod, $idCctp, $id, CctpRepository $cctpRepo, ProduitRepository $produitRepo, LignedevisRepository $lignedevisRepo, Request $request, ManagerRegistry $doctrine, SessionInterface $session): Response
    {
        
        //récupération du cctp par son id
        $cctp = $cctpRepo->findBy(['id' => $idCctp]);
        //récupération de l'élément par son id
        $produit = $produitRepo->findBy(['id' => $id]);
        //récupération de l'année n -1
        $year = date('Y') - 1;

        //instanciation d'un nouvel objet docfinal
        $dpgf = new DocFinal();
        
        //création du formulaire
        $form = $this->createFormBuilder($dpgf)
            ->add('type', EntityType::class, [
                'class' => Specification::class,
                'choices' => array_unique($produit[0]->getSpecifications()->toArray()),
                'choice_label' => 'type',
                'required' => false,
                'empty_data' => null,
                'by_reference' => false
            ])
            ->add('localisation', TextType::class, [
                'required' => false,
                'empty_data' => null
            ])
            ->add('quantite', TextType::class)
            ->add('Valider', SubmitType::class)
            ->getForm();

        //traitement des données saisies
        $form->handleRequest($request);
        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //ajout des informations nécessaires
            $dpgf->setProduit($prod);
            $dpgf->setCctpId($idCctp);
            $prix = $lignedevisRepo->prixMoyen($id, $dpgf->getType(), $year);
            $dpgf->setMoyenne($prix[0][1]);
            //enregistrement en bdd
            $entityManager = $doctrine->getManager();
            $entityManager->persist($dpgf);
            $entityManager->flush();

            //initialisation d'un tableau en session
            $compteur = $session->get("compteur ajout", []);
            if(!empty($compteur[strval($id)])){
                $compteur[strval($id)]++;
            }else{
                $compteur[strval($id)] = 1;
            }
            
            //enregistrement en session
            $session->set("compteur ajout", $compteur);

            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'contenu enregistré avec succès');
            //redirection vers la page qui liste les éléments du cctp
            return $this->redirectToRoute('cctp_show', ['id' => $idCctp], Response::HTTP_SEE_OTHER);
        }

        //affichage du formulaire
        return $this->renderForm('admin/doc_final/dpgf.html.twig', [
            'cctp' => $cctp,
            'produit' => $produit,
            'form' => $form
        ]);
    }

    /**
     * @Route("/dpgfView/{idCctp}", name="dpgf_view", methods={"GET","POST"})
     * methode qui affiche le dpgf
     */
    public function afficheDpgf(SessionInterface $session, $idCctp, CctpRepository $cctpRepo, DocFinalRepository $docfinalRepo)
    {
        //récupération du cctp par son id
        $cctp = $cctpRepo->findBy(['id' => $idCctp]);
        //récupération des lignes du pgf par l'id du cctp
        $dpgf = $docfinalRepo->findBy(['cctp_id' => $idCctp]);

        $panier = $session->get("lignes_Dpgf", []);

        return $this->render('admin/doc_final/view.html.twig', [
            'dpgf' => $dpgf,
            'cctp_id' => $idCctp,
            'panier' => $panier
        ]);
    }

    /**
     * @Route("/dpgfSupprime/{id}/{idCctp}", name="dpgf_supprime", methods={"GET","POST"})
     * methode qui supprime une ligne de la session sur le dpgf
     */
    public function supprimeLigneDpgf(SessionInterface $session, Request $request, $id, $idCctp, DocFinalRepository $docfinalRepo)
    {   
        $panier = $session->get("lignes_Dpgf", []);
        
        
            unset($panier[$id]);
        
        $session->set("lignes_Dpgf", $panier);

        //récupération du cctp par son id
        //$cctp = $cctpRepo->findBy(['id' => $idCctp]);
        //récupération des lignes du pgf par l'id du cctp
        $dpgf = $docfinalRepo->findBy(['cctp_id' => $idCctp]);

        $panier = $session->get("lignes_Dpgf", []);

        return $this->render('admin/doc_final/view.html.twig', [
            'dpgf' => $dpgf,
            'cctp_id' => $idCctp,
            'panier' => $panier
        ]);

        
    }

    /**
     * @Route("/dpgfNewLigne/{idCctp}", name="dpgf_newLigne", methods={"GET","POST"})
     * methode qui ajoute une ligne sur le dpgf
     */
    public function ajoutLigneDpgf(SessionInterface $session, Request $request, $idCctp, CctpRepository $cctpRepo, DocFinalRepository $docfinalRepo)
    {   
        $id = null;
        //$docFinal = new DocFinal();
        //$form = $this->createForm(DpgfType::class, null);
        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('Lot', EntityType::class, [
                'class' => Lot::class
            ])
            ->add('Systeme', EntityType::class, [
                'class' => Systeme::class
            ])
            ->add('Description')
            ->add('Unite')
            ->add('Quantite')
            ->add('Valider', SubmitType::class)
            ->getForm();
        
            //si le formulaire est soumis
            if ($request->isMethod('POST')) {
                //traitement des données saisies
                $form->handleRequest($request);
                
                //récupération des éléments en tableau

                $contenu = $form->getData();
            //appel du panier en session
            $panier = $session->get("lignes_Dpgf", []);
            
            if (array_key_last($panier) == null){
                $id = 0;
            }else{
                $id = array_key_last($panier);
            }
            
            //si le panier ne contient pas l'id de cet élément 
            if (empty($panier[$id])) {
                
                //ajout du contenu au panier
                $panier[$id]['id'] = $id;
                $panier[$id]['lot'] = $contenu['Lot']->getNomLot();
                $panier[$id]['systeme'] = $contenu['Systeme']->getNomSysteme();
                $panier[$id]['descritpion'] = $contenu['Description'];
                $panier[$id]['unite'] = $contenu['Unite'];
                $panier[$id]['quantite'] = $contenu['Quantite'];
                
                //enregistrement du panier en session
            $session->set("lignes_Dpgf", $panier);
            
            return $this->redirectToRoute('dpgf_view', ['idCctp' => $idCctp], Response::HTTP_SEE_OTHER);
            }
            else{
                $id += 1;
                //ajout du contenu au panier
                $panier[$id]['id'] = $id;
                $panier[$id]['lot'] = $contenu['Lot']->getNomLot();
                $panier[$id]['systeme'] = $contenu['Systeme']->getNomSysteme();
                $panier[$id]['descritpion'] = $contenu['Description'];
                $panier[$id]['unite'] = $contenu['Unite'];
                $panier[$id]['quantite'] = $contenu['Quantite'];
                //enregistrement du panier en session
            $session->set("lignes_Dpgf", $panier);

                return $this->redirectToRoute('dpgf_view', ['idCctp' => $idCctp], Response::HTTP_SEE_OTHER);
            }
            
            
            
            }
        //récupération du cctp par son id
        $cctp = $cctpRepo->findBy(['id' => $idCctp]);
        //récupération des lignes du pgf par l'id du cctp
        $dpgf = $docfinalRepo->findBy(['cctp_id' => $idCctp]);

        return $this->renderForm('admin/doc_final/newLigneDpgf.html.twig', [
            'dpgf' => $dpgf,
            'cctp_id' => $idCctp,
            'form' => $form
        ]);
    }

    /**
     * @Route("/{id}/edit", name="dpgf_edit", methods={"GET","POST"})
     * fonction de mise à jour d'un dpgf
     */
    public function edit(Request $request, DocFinal $docFinal, ManagerRegistry $doctrine): Response
    {
        //appel du form de création d'un chantier
        $form = $this->createForm(DpgfType::class, $docFinal);
        //traitement des données saisies
        $form->handleRequest($request);
        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrement des modifications en bdd
            $doctrine->getManager()->flush();
            //affichage d'un message si l'opération s'est déroulée avec succès
            $this->addFlash('message', 'Mise à jour effectuée avec succès');
            //redirection vers la page qui liste les chantiers
            return $this->redirectToRoute('chantier_index', [], Response::HTTP_SEE_OTHER);
        }
        //affichage du form
        return $this->renderForm('admin/doc_final/dpgfEdit.html.twig', [
            'docFinal' => $docFinal,
            'form' => $form,
            
        ]);
    }


    /**
     * @Route("/dpgfpdf/{idCctp}", name="dpgf_pdf", methods={"GET","POST"})
     * methode qui génère le dpgf
     */
    public function generePdf(SessionInterface $session, $idCctp, CctpRepository $cctpRepo, DocFinalRepository $docfinalRepo)
    {
        //récupération du cctp par son id
        $cctp = $cctpRepo->findBy(['id' => $idCctp]);
        //récupération des lignes du pgf par l'id du cctp
        $dpgf = $docfinalRepo->findBy(['cctp_id' => $idCctp]);

        $panier = $session->get("lignes_Dpgf", []);

        //définition des options du pdf
        $pdfOptions = new Options();
        $pdfOptions->setIsRemoteEnabled(true);
        $dompdf = new Dompdf($pdfOptions);

        //enregistrement de la vue dans la variable html
        $html = $this->renderView('admin/doc_final/generePdf1.html.twig', [
            'cctp' => $cctp[0],
            'dpgf' => $dpgf,
            'panier' =>$panier
        ]);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream($cctp[0]->getTitre().'_DPGF.pdf', [
            'Attachment' => true
        ]);

        return new Response();
    }

    /**
     * @Route("/dpgfpdf2/{idCctp}", name="dpgf_pdf2", methods={"GET","POST"})
     * methode qui génère le dpgf sans quantité
     */
    public function generePdfSansQuantite(SessionInterface $session, $idCctp, CctpRepository $cctpRepo, DocFinalRepository $docfinalRepo)
    {
        //récupération du cctp par son id
        $cctp = $cctpRepo->findBy(['id' => $idCctp]);
        //récupération des lignes du pgf par l'id du cctp
        $dpgf = $docfinalRepo->findBy(['cctp_id' => $idCctp]);

        $panier = $session->get("lignes_Dpgf", []);

        //définition des options du pdf
        $pdfOptions = new Options();
        $pdfOptions->setIsRemoteEnabled(true);
        $dompdf = new Dompdf($pdfOptions);

        //enregistrement de la vue dans la variable html
        $html = $this->renderView('admin/doc_final/generePdf2.html.twig', [
            'cctp' => $cctp[0],
            'dpgf' => $dpgf,
            'panier' =>$panier
        ]);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream($cctp[0]->getTitre().'_DPGFsansQte.pdf', [
            'Attachment' => true
        ]);

        return new Response();
    }

    /**
     * @Route("/dpgfpdf3/{idCctp}", name="dpgf_pdf3", methods={"GET","POST"})
     * methode qui génère le dpgf avec tarifs
     */
    public function estimTravaux(SessionInterface $session, $idCctp, CctpRepository $cctpRepo, DocFinalRepository $docfinalRepo)
    {
        //récupération du cctp par son id
        $cctp = $cctpRepo->findBy(['id' => $idCctp]);
        //récupération des lignes du pgf par l'id du cctp
        $dpgf = $docfinalRepo->findBy(['cctp_id' => $idCctp]);

        $panier = $session->get("lignes_Dpgf", []);

        //définition des options du pdf
        $pdfOptions = new Options();
        $pdfOptions->setIsRemoteEnabled(true);
        $dompdf = new Dompdf($pdfOptions);

        //enregistrement de la vue dans la variable html
        $html = $this->renderView('admin/doc_final/generePdf3.html.twig', [
            'cctp' => $cctp[0],
            'dpgf' => $dpgf,
            'panier' =>$panier
                ]);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream($cctp[0]->getTitre().'_EstimDPGF.pdf', [
            'Attachment' => true
        ]);

        return new Response();
    }

    /**
     * @Route("/supprime/{id}/{idCctp}", name="doc_final_supprime", methods={"GET"})
     * methode qui supprime les infos du dpgf liées à l'élément séléctionné
     */
    public function supprime(DocFinal $docFinal, $idCctp, ManagerRegistry $doctrine): Response
    {
        //suppression des infos de l'élément en bdd
        $entityManager = $doctrine->getManager();
        $entityManager->remove($docFinal);
        $entityManager->flush();

        //affichage d'un message si l'opération s'est déroulée avec succès
        $this->addFlash('message', 'contenu supprimé avec succès');
        //redirection vers la page qui liste les éléments du cctp
        return $this->redirectToRoute('cctp_show', ['id' => $idCctp], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/modif/{id}/{idCctp}", name="doc_final_modif", methods={"GET", "POST"})
     * methode qui modifife les infos du dpgf liées à l'élément séléctionné
     */
    public function modif(DocFinal $docFinal, DevisRepository $devisRepo, Request $request, $idCctp, SpecificationRepository $specificationRepo, ManagerRegistry $doctrine): Response
    {
        //récupération du formulaire d'ajout
        $form = $this->createForm(DocFiType::class, $docFinal);
        //traitement des données saisies
        $form->handleRequest($request);

        $specification = new Specification();
        $lignedevis = new Lignedevis();

        if ($form->isSubmitted() && $form->isValid()) {
            
            $specification->setProduit($docFinal->getProduit());
            $specification->setType($docFinal->getType());
            $specification->setPrixUnitaire($docFinal->getMoyenne());

            $entityManager = $doctrine->getManager();
            $entityManager->persist($docFinal);
            $entityManager->persist($specification);
            $entityManager->flush();

            $lastId = $specification->getId();
            $lignedevis->setSpecification($specificationRepo->findOneBy(['id' => $lastId]));
            $lignedevis->setProduit($docFinal->getProduit());
            $lignedevis->setQuantite(1);
            $lignedevis->setDevis($devisRepo->findOneBy(['id'=> 51]));

            $em = $doctrine->getManager();
            $em->persist($lignedevis);
            $em->flush();
            
        //affichage d'un message si l'opération s'est déroulée avec succès
        $this->addFlash('message', 'contenu modifié avec succès');
        //redirection vers la page qui liste les éléments du cctp
        return $this->redirectToRoute('cctp_show', ['id' => $idCctp], Response::HTTP_SEE_OTHER);
        }
        //affichage du form 
        return $this->renderForm('admin/doc_final/edit.html.twig', [
            //'cctp' => $cctp,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="doc_final_delete", methods={"POST"})
     */
    public function delete(Request $request, DocFinal $docFinal, ManagerRegistry $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete' . $docFinal->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($docFinal);
            $entityManager->flush();
        }

        return $this->redirectToRoute('doc_final_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/lireExel/{id}", name="lire_exel", methods={"GET","POST"})
     * methode qui lit le document exel et enregistre le contenu dans un tableau
     */
    public function excelReader(Request $request, DevisRepository $deviRepo, $id, SessionInterface $session)
    {
        //si la requette est bien passée en POST
        if ($request->isMethod('POST')) {
            //récupération du nom de fichier à lire
            $nomDoc = $request->request->get('fichier');       
        }
        //récupération du devis par son id
        $devi = $deviRepo->findBy(['id' => $id]);
        //instanciation d'un nouvel objet reader
        $reader = new ReaderXlsx();
        //lire simplement les données des cellules
        $reader->setReadDataOnly(TRUE);

        //indication du nom de fichier à lire
        $spreadsheet = $reader->load("C:\docs\\".$nomDoc); 

        //création d'un tableau vide
        $data = [];
        //tri des données 
        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
            //création d'un tableau pour récéptionnerr les données
            $data = [
                'columnNames' => [],
                'columnValues' => [],
            ];
            foreach ($worksheet->getRowIterator() as $row) {
                //récupération de l'index de chaque ligne
                $rowIndex = $row->getRowIndex();

                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(true);
                //lecture de chaque cellule par ligne
                foreach ($cellIterator as $cell) {
                    //si c'est ligne 4 
                    if ($rowIndex === 3) {
                        if ($cell->getCalculatedValue() != null) {
                            //recupération des noms de colonnes
                            $data[$rowIndex]['columnNames'][] = $cell->getCalculatedValue();
                        }
                    }
                    //pour toutes les lignes a partir de la ligne 7
                    if ($rowIndex > 6) {
                        //tri des données de chaque cellule par ligne
                        $data['columnValues'][$rowIndex][] = $cell->getCalculatedValue();
                    }
                }
            }
        }
        //réorganisation du tableau
        $newTab = [];
        foreach ($data['columnValues'] as $data['columnValues'][0]) {
            if ($data['columnValues'][0] != null) {
                array_push($newTab, $data['columnValues'][0]);
            }
        }
        //tri des données lues en fonction de leur position
        $listeProd = [];

        foreach ($newTab as $key => $rowTab) {
            if ($rowTab[0] != null && $rowTab[1] != null && $rowTab[3] != null && $rowTab[4] != null) {
                $listeProd[$key] = $rowTab;
            } else if ($rowTab[0] != null && $rowTab[1] != null && $rowTab[3] == null) {
                $listeProd[$key] = $rowTab;
                $i = 1;
                if ($newTab[$key + $i][0] == null && $newTab[$key + $i][3] != null) {
                    while ($newTab[$key + $i][0] == null && $newTab[$key + $i][3] != null) {
                        $listeProd[$key]['quantite'][] = $newTab[$key + $i];
                        $i += 1;
                    }
                } else if ($newTab[$key + $i][0] == null && $newTab[$key + $i][1] != null && $newTab[$key + $i][3] == null) {

                    $i += 1;
                    while ($newTab[$key + $i][0] == null && $newTab[$key + $i][3] != null) {
                        $listeProd[$key]['type'][] = $newTab[$key + $i];
                        $i += 2;
                    }
                }
            }
        }
        //prépartion des données avant enregistrement en session
        $liste = [];

        foreach ($listeProd as $prod) {
            if (count($prod) == 7 && $prod[3] != null) {
                array_push($liste, $prod);
            } else if (isset($prod['quantite']) && count($prod['quantite']) == 1) {
                $prod[3] = $prod['quantite'][0][3];
                $prod[4] = $prod['quantite'][0][4];
                $prod[6] = $prod['quantite'][0][1];
                array_push($liste, $prod);
            } else if (isset($prod['quantite']) && count($prod['quantite']) > 1) {
                $prod[3] = $prod['quantite'][0][3];
                $prod[4] = $prod['quantite'][0][4];
                $prod[6] = $prod['quantite'][0][1];
                array_push($liste, $prod);
                $nb = 1;
                while ($nb < count($prod['quantite'])) {
                    $prod[3] = $prod['quantite'][$nb][3];
                    $prod[4] = $prod['quantite'][$nb][4];
                    $prod[6] = $prod['quantite'][$nb][1];
                    array_push($liste, $prod);
                    $nb += 1;
                }
            } else if (isset($prod['type']) && count($prod['type']) == 1) {
                $prod[3] = $prod['type'][0][3];
                $prod[4] = $prod['type'][0][4];
                $prod[6] = $prod['type'][0][1];
                array_push($liste, $prod);
            } else if (isset($prod['type']) && count($prod['type']) > 1) {
                $prod[3] = $prod['type'][0][3];
                $prod[4] = $prod['type'][0][4];
                $prod[6] = $prod['type'][0][1];
                array_push($liste, $prod);
                $nb = 1;
                while ($nb < count($prod['type'])) {
                    $prod[3] = $prod['type'][$nb][3];
                    $prod[4] = $prod['type'][$nb][4];
                    $prod[6] = $prod['type'][$nb][1];
                    array_push($liste, $prod);
                    $nb += 1;
                }
            }
        }
        //initialisation d'un tableau en session
        $tabExel = $session->get("tabExel", []);
        $tabExel = $liste;
        //enregistrement en session
        $session->set("tabExel", $tabExel);

        //affichage de la liste des éléments du doc exel
        return $this->render('admin/doc_final/testLectureExel.html.twig', [
            'prod' => $tabExel,
            'devi' => $devi[0]

        ]);
          
    }
}
