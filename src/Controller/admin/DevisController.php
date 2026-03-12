<?php

namespace App\Controller\admin;

use App\Entity\Catusage;
use App\Entity\Devis;
use App\Entity\Typeusage;
use App\Form\CatusageType;
use App\Form\DevisType;
use App\Form\TypeusageType;
use App\Repository\DevisRepository;
use App\Repository\LignedevisRepository;
use App\Repository\TypeusageRepository;
use Doctrine\Persistence\ManagerRegistry;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("admin/devis")
 * @package App\Controller\admin
 */
class DevisController extends AbstractController
{
    /**
     * @Route("/", name="devis_index", methods={"GET", "POST"})
     * fonction qui liste tous les devis
     */
    public function index(DevisRepository $devisRepository, Request $request, TypeusageRepository $typeusageRepo): Response
    {
        $typeusage = $typeusageRepo->findAll();
        $usage = null;

        $form = $this->createForm(TypeusageType::class, $typeusage);

        //traitement des données saisies
        $form->handleRequest($request);
        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            
            $idUsage = $request->request->get('typeusage')['nom_usage'];
            
            $usage = $typeusageRepo->findOneBy(['id' => $idUsage]);
            //dd($usage->getNomUsage());
            

            
        }

        return $this->renderForm('admin/devis/index.html.twig', [
            'devis' => $devisRepository->findBy([], ['created_At' => 'ASC']),
            'form' => $form,
            'usage' => $usage
        ]);
    }

    /**
     * @Route("/new", name="devis_new", methods={"GET","POST"})
     * fonction de création d'un devis
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        //instanciation d'un nouvel objet devis
        $devi = new Devis();
        //appel du form de création d'un nouveau devis
        $form = $this->createForm(DevisType::class, $devi);
        //traitement des données saisies
        $form->handleRequest($request);
        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //récupération de l'utilisateur
            $devi->setUtilisateur($this->getUser());
            //enregistrement en bdd
            $entityManager = $doctrine->getManager();
            $entityManager->persist($devi);
            $entityManager->flush();
            //affichage d'un message si opération réalisée avec succès
            $this->addFlash('message', 'Devis crée avec succès');
            //redirection vers la page qui liste les devis
            return $this->redirectToRoute('devis_index', [], Response::HTTP_SEE_OTHER);
        }
        //affichage du form
        return $this->renderForm('admin/devis/new.html.twig', [
            'devi' => $devi,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/show/{id}", name="devis_show", methods={"GET"})
     * fonction qui affiche les informations d'un devis
     */
    public function show(Devis $devi, LignedevisRepository $lignedevisRepo, $id): Response
    {
        //récupération de toutes les lignes du devis par son id
        $lignedevis = $lignedevisRepo->findBy(['devis' => $id]);

        return $this->render('admin/devis/show.html.twig', [
            'devi' => $devi,
            'lignedevis' => $lignedevis
        ]);
    }

    /**
     * @Route("/{id}/edit", name="devis_edit", methods={"GET","POST"})
     * fonction de mise à jour d'un devis
     */
    public function edit(Request $request, Devis $devi, ManagerRegistry $doctrine, SessionInterface $session): Response
    {
        //appel du formulaire de création d'un devis
        $form = $this->createForm(DevisType::class, $devi);
        //traitement des données saisies
        $form->handleRequest($request);
        //si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            if(isset($form['fichier'])){
                $file = $form['fichier']->getData();
                $destination = $this->getParameter('devis_directory');

                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$file->guessExtension();
                $file->move(
                    $destination,
                    $newFilename
                );
                //instanciation d'un nouvel objet reader
                $reader = new ReaderXlsx();
                //lire simplement les données des cellules
                $reader->setReadDataOnly(TRUE);

                //indication du nom de fichier à lire
                $spreadsheet = $reader->load($destination.'/'.$newFilename); 

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
            'devi' => $devi

        ]);
            }
            //enregistrement des modifications en bdd
            $doctrine->getManager()->flush();
            //affichage d'un message si opération réalisée avec succès
            $this->addFlash('message', 'Mise à jour effectuée avec succès');
            //redirection vers la page qui liste les devis
            return $this->redirectToRoute('devis_index', [], Response::HTTP_SEE_OTHER);
        }
        //afichage du form 
        return $this->renderForm('admin/devis/edit.html.twig', [
            'devi' => $devi,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="devis_delete", methods={"POST"})
     * fonction de suppression d'un devis par son id
     */
    public function delete(Request $request, Devis $devi, ManagerRegistry $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$devi->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($devi);
            $entityManager->flush();
        }
        //affichage d'un message si opération réalisée avec succès
        $this->addFlash('message', 'Devis supprimé avec succès');
        //redirection vers la page qui liste les devis
        return $this->redirectToRoute('devis_index', [], Response::HTTP_SEE_OTHER);
    }
    
}
