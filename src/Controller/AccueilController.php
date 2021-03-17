<?php

namespace App\Controller;

use App\Repository\EtatRepository;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Cassandra\Date;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(EntityManagerInterface $em, EtatRepository $repoEtat, SiteRepository $repoSite, SortieRepository $repoSortie, UserRepository $repoUser): Response
    {
        $sites= $repoSite->findAll();
        $sorties = $repoSortie->findBy([],['dateHeureDebut' => 'desc']);
        $today=new \DateTime("now");

        foreach ($sorties as $s){
            $dateDebut= $s->getDateHeureDebut();
            $duree= $s->getDuree();
            $dateFin=date_add($dateDebut, date_interval_create_from_date_string("$duree day"));
            $dateArchive=date_add($dateFin, date_interval_create_from_date_string("1 month"));

            // si elle est annulée, créée ou publiée, pas de changement d'état
            if (!$s->getEtat()->getLibelle()=='Annulée'){}
            else {

                // si la date d'inscription est depassée alors elle passe en cloturee
                if ($today> $s->getDateLimiteInscription()){
                    $etat= $repoEtat->findOneBy(["libelle" =>'Cloturée']);
                    $s->setEtat($etat);
                    $em->persist($etat);
                    $em->flush();
                }
                // si la date de début est arrivée alors elle passe en cours
                if ($today>= $s->getDateHeureDebut()){
                    $etat= $repoEtat->findOneBy(["libelle" =>'En cours']);
                    $s->setEtat($etat);
                    $em->persist($etat);
                    $em->flush();
                }
                // si la date de debut + duree est dépaséee alors elle passe en passée
                if ($today> $dateFin){
                     $etat= $repoEtat->findOneBy(["libelle" =>'Passée']);
                     $s->setEtat($etat);
                    $em->persist($etat);
                    $em->flush();
                }
                // si la date de debut + duree + 30 jours est dépaséee alors elle passe en archivée
                if ($today>= $dateArchive){
                    $etat= $repoEtat->findOneBy(["libelle" =>'Archivée']);
                    $s->setEtat($etat);
                    $em->persist($etat);
                    $em->flush();
                }

            }

        }

        return $this->render('accueil.html.twig', [
            'sites'=>$sites,
            'sorties'=>$sorties,

        ]);
    }


}
