<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\User;
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
                return $this->redirectToRoute('accueil');
            }

            if(!$form->get('lieu')->isEmpty()){

                //je mets d'office la personne identifié comme organisatrice
                $organisateur= $repoUser->find($id);
                $sortie->setOrganisateur($organisateur);

                //je mets d'office le site de l'organisateur
                $site= $organisateur->getSite();
                $sortie->setSite($site);

                //je mets d'office le nbrePlacesRestante = nbrePLacesMax
                $sortie->setNbrePlacesRestantes($sortie->getNbrePlacesMax());

                dump($sortie->getDateHeureDebut());


                $em->persist($sortie);
                $em->flush();

                return $this->redirectToRoute('accueil');
            }





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
        $participants= $sortie ->getUsers();
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
            'participants'=>$participants
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
            if(!$form->get('lieu')->isEmpty()){

                if ($form->get('enregistrer')->isClicked()){

                    $this->getDoctrine()->getManager()->flush();
                }
                if ($form->get('publier')->isClicked()){



                    $etat = $repoEtat->findOneBy(["libelle" => 'Ouverte']);
                    $sortie->setEtat($etat);
                    $this->getDoctrine()->getManager()->flush();

                }
            return $this->redirectToRoute('accueil');
        }
        else if ($form->get('supprimer')->isClicked()){
            $em->remove($sortie);
            $em->flush();
                return $this->redirectToRoute('accueil');

            }



        }

        return $this->render('sortie/edit.html.twig', [
            'sortie' => $sortie,
            'form' => $form->createView(),
        ]);
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
     * @Route("/publier/{id}", name="sortie_publier", methods={"GET"})
     */
    public function publier(Sortie $sortie, EtatRepository $repoEtat): Response
    {
        // je mets d'office l'état de la sortie à Ouverte.
        $etat= $repoEtat->findOneBy(["libelle" =>'Ouverte']);
        $sortie->setEtat($etat);

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/inscrire/{idUser}/{idSortie}", name="sortie_inscrire")
     */
    public function inscrire ($idUser, $idSortie, SortieRepository $repoSortie, UserRepository $repoUser, SiteRepository $repoSite): Response
    {

        $user= $repoUser->find($idUser);
        $sortie= $repoSortie->find($idSortie);
        $today= new \Datetime();
        $inscrits=$sortie->getUsers();

        //verifier que la date limite d'inscription est ok
        if ($sortie->getDateLimiteInscription() > $today) {
            //verifier si pas dejà inscrit
//            foreach ($inscrits as $inscrit) {
//                if (! $inscrit->getId() === $user->getId()){
                       $sortie->addUser($user);
                    //enlever une place restante à chaque inscription

                    if ($sortie->getNbrePlacesRestantes() > 0) {
                        $sortie->setNbrePlacesRestantes($sortie->getNbrePlacesRestantes() - 1);
                    }
                    $this->getDoctrine()->getManager()->flush();
//                }
//
//            }

        }

        return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/desister/{idUser}/{idSortie}", name="sortie_desister")
     */
    public function desister ($idUser, $idSortie, SortieRepository $repoSortie, UserRepository $repoUser, SiteRepository $repoSite): Response
    {

        $user= $repoUser->find($idUser);
        $sortie= $repoSortie->find($idSortie);
        $inscrits=$sortie->getUsers();
        $today= new \Datetime();
        //si la sortie n'est pas commencée
        if ($sortie->getDateHeureDebut()> $today) {
            //si le user est bien inscrit
            foreach ($inscrits as $i) {
                if ($i->getId() == $user->getId()) {
                    $sortie->removeUser($user);

                    //ajouter une place restante à chaque desistement

                    if ($sortie->getNbrePlacesRestantes() < $sortie->getNbrePlacesMax()) {
                        $sortie->setNbrePlacesRestantes($sortie->getNbrePlacesRestantes() + 1);
                    }
                    $this->getDoctrine()->getManager()->flush();
                }
            }
        }



        return $this->redirectToRoute('accueil');
    }

}
