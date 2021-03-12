<?php

namespace App\Controller;

use App\Repository\EtatRepository;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use Cassandra\Date;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/accueil", name="accueil")
     */
    public function index(EntityManagerInterface $em, EtatRepository $repoEtat, SiteRepository $repoSite, SortieRepository $repoSortie): Response
    {
        $sites= $repoSite->findAll();
        $sorties = $repoSortie->findAll();
        $today=new \DateTime("now");

        foreach ($sorties as $s){

//            $a= $s->getDateHeureDebut()+ $s->getDuree();
//            dump($a);
//            die();

            if ($today> $s->getDateLimiteInscription()){
                $etat= $repoEtat->findOneBy(["libelle" =>'Cloturée']);
                $s->setEtat($etat);
            }

            if ($today> $s->getDateHeureDebut()){
                $etat= $repoEtat->findOneBy(["libelle" =>'En cours']);
                $s->setEtat($etat);
            }
//
//            if ($today> ($s->getDateHeureDebut()+ $s->getDuree())){
//                $s->setEtat('Passée');
//            }
            $em->persist($etat);
            $em->flush();



        }




        return $this->render('accueil.html.twig', [
            'sites'=>$sites,
            'sorties'=>$sorties
        ]);
    }

//    /**
//     * @Route("/select/{id}", name="select")
//     */
//    public function select($id, SortieRepository $repo): Response
//    {
//        $sorties= $repo->findBy([],["site_id" => $id]);
//
//        return $this->render('accueil.html.twig', [
//            'sorties'=>$sorties,
//        ]);
//    }


}
