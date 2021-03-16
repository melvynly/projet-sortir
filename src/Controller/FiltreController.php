<?php

namespace App\Controller;

use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FiltreController extends AbstractController
{
    /**
     * @Route("/filtre/{id}", name="filtre")
     */
    public function filtre($id, Request $request, SiteRepository $repoSite, SortieRepository $repoSortie, UserRepository $repoUser): Response
    {
//        $filtres= $request->getContent();
        $sites= $repoSite->findAll();
        //$sorties = $repoSortie->findAll();
        $user= $repoUser->find($id);
        $sorties=[];

        if (!$request->request->get('site')
            && !$request->request->get('nom')
            && !$request->request->get('dateDebut')
            && !$request->request->get('dateFin')
            && !$request->request->get('inscrit')
            && !$request->request->get('pasInscrit')
            && !$request->request->get('passe')) {

            $sorties = $repoSortie->findAll();
        } else {


            if ($request->request->get('site')) {
                $sorties = $repoSortie->findBy(['site' => $request->request->get('site')]);
            }
            if ($request->request->get('nom')) {

            }
            if ($request->request->get('dateDebut')) {

            }
            if ($request->request->get('dateFin')) {

            }
            if ($request->request->get('orga')) {

            }
            if ($request->request->get('inscrit')) {

            }
            if ($request->request->get('pasInscrit')) {

            }
            if ($request->request->get('passe')) {

            }
        }


        return $this->render('accueil.html.twig', [
            //'resultats'=>$resultat,
            'sites'=>$sites,
            'sorties'=>$sorties,
        ]);
    }
}
