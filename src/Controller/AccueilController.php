<?php

namespace App\Controller;

use App\Repository\EtatRepository;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\SearchIndexType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Data\RechercheDonnees;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     *
     */
    public function index(Request $request, UserInterface $user=null, EntityManagerInterface $em, EtatRepository $repoEtat, SiteRepository $repoSite, SortieRepository $repoSortie, UserRepository $repoUser): Response
    {

        if($this->denyAccessUnlessGranted('ROLE_USER')){
            return $this->redirectToRoute("app_login");
        }
        else{
            $sites= $repoSite->findAll();
            // $sorties = $repoSortie->findBy([],['dateHeureDebut' => 'desc']);
            // init data
            $data = new RechercheDonnees();
            //$data->page = $request->get('page', 1);
            // je cree un formulaire qui utilise la classe searchType que j'ai crée, et en 2eme parametre les données
            //$data, objet qui sera modifié quand je ferai un handlerequest ou autre
            $form1 = $this->createForm(SearchIndexType::class, $data);
            $form1->handleRequest($request);

            $sorties = $repoSortie->findSearch($data, $user);

            $today=new \DateTime("now");


            foreach ($sorties as $s){
                // Création du dateTime de la nouvelle méthode pour le pb d'affichage du tableau
                $d1 = new \DateTime($s->getDateHeureDebutFormat());


                $dateDebut = $s->getDateHeureDebut();


                //$duree= $s->getDuree();
                //$dateFin = date_add($d1, date_interval_create_from_date_string("$duree day"));;

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
                    if ($today> $s->getDateHeureDebut()){
                        $etat= $repoEtat->findOneBy(["libelle" =>'Passée']);
                        $s->setEtat($etat);
                        $em->persist($etat);
                        $em->flush();
                    }
                    // si la date de debut + duree + 30 jours est dépaséee alors elle passe en archivée
                    if ($today>= date_add($d1, date_interval_create_from_date_string("1 month"))){
                        $etat= $repoEtat->findOneBy(["libelle" =>'Archivée']);
                        $s->setEtat($etat);
                        $em->persist($etat);
                        $em->flush();
                    }

                }

            }



            return $this->render('accueil.html.twig', [
                'sorties'=>$sorties,
                'form1'=> $form1->createView(),
                'sites'=>$sites,
                //'sorties'=>$sorties,

            ]);
        }



    }
//
//    public function filtre (Request $request)
//    {
//        $defaultData = ['message' => 'Type your message here'];
//        $form = $this->createFormBuilder($defaultData)
//            ->add('date_debut', TextType::class)
//            ->add('date_fin', EmailType::class)
//            ->add('send', SubmitType::class)
//            ->getForm();
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            // data is an array with "date_debut", "date_fin" keys
//            $data = $form->getData();
//        }
//        // ... render the form
//    }

}
