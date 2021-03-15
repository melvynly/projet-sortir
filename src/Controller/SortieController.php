<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sortie")
 *
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/", name="sortie_index", methods={"GET"})
     */
    public function index(SortieRepository $sortieRepository): Response
    {
        return $this->render('sortie/index.html.twig', [
            'sorties' => $sortieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="sortie_new", methods={"GET","POST"})
     */
    public function new($id,Request $request, EntityManagerInterface $em, UserRepository $repoUser, EtatRepository $repoEtat, VilleRepository $repoVille): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        $villes = $repoVille->findAll();

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('enregistrer')->isClicked()){

                // je mets d'office l'état de la sortie à Créée.
                $etat= $repoEtat->findOneBy(["libelle" =>'Créée']);
                $sortie->setEtat($etat);
            }
            if ($form->get('publier')->isClicked()){

                // je mets d'office l'état de la sortie à Ouverte.
                $etat= $repoEtat->findOneBy(["libelle" =>'Ouverte']);
                $sortie->setEtat($etat);
            }
            if ($form->get('annuler')->isClicked()){
                //TODO rediriger vers la page d'annulation
//                return $this->redirectToRoute('');
            }


            //je mets d'office la personne identifié comme organisatrice
            $organisateur= $repoUser->find($id);
            $sortie->setOrganisateur($organisateur);

            //je mets d'office le site de l'organisateur
            $site= $organisateur->getSite();
            $sortie->setSite($site);


            $em->persist($sortie);
            $em->flush();

            return $this->redirectToRoute('accueil');
        }

        return $this->render('sortie/new.html.twig', [
            'sortie' => $sortie,
            'form' => $form->createView(),
            'villes' => $villes,
        ]);
    }

    /**
     * @Route("/{id}", name="sortie_show", methods={"GET"})
     */
    public function show(Sortie $sortie): Response
    {
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sortie_edit")
     */
    public function edit(EntityManagerInterface $em, Request $request, EtatRepository $repoEtat, Sortie $sortie): Response
    {
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('enregistrer')->isClicked()){

                $this->getDoctrine()->getManager()->flush();
            }
            if ($form->get('publier')->isClicked()){

                // je mets d'office l'état de la sortie à Ouverte.
                $etat= $repoEtat->findOneBy(["libelle" =>'Ouverte']);
                $sortie->setEtat($etat);

                $this->getDoctrine()->getManager()->flush();

            }
            if ($form->get('supprimer')->isClicked()){
            $em->remove($sortie);
            $em->flush();

            }


            return $this->redirectToRoute('accueil');
        }

        return $this->render('sortie/edit.html.twig', [
            'sortie' => $sortie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sortie_delete")
     */
    public function delete(Request $request, Sortie $sortie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sortie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sortie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sortie_index');
    }


    /**
     * @Route("/annuler/{id}", name="sortie_annuler")
     */
    public function annuler(Sortie $sortie, Request $request, EntityManagerInterface $em, UserRepository $repoUser, EtatRepository $repoEtat): Response
    {
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('enregistrer')->isClicked()){

                $etat= $repoEtat->findOneBy(["libelle" =>'Annulée']);
                $sortie->setEtat($etat);

                $this->getDoctrine()->getManager()->flush();
            }

            if ($form->get('annuler')->isClicked()){
                return $this->redirectToRoute('accueil');
            }


            return $this->redirectToRoute('accueil');
        }


        return $this->render('sortie/annuler.html.twig', [
            'sortie' => $sortie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sortie_publier", methods={"GET"})
     */
    public function publier(Sortie $sortie): Response
    {
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }

}
