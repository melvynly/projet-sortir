<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/ville")
 */
class VilleController extends AbstractController
{
    /**
     * @Route("/", name="ville", methods={"GET"})
     */
    public function home(VilleRepository $repo, Request $request, EntityManagerInterface $em): Response
    {

        if($this->denyAccessUnlessGranted('ROLE_ADMIN')){
            return $this->render('site/index.html.twig');
        }
        else{
            $villes = $repo->findAll();

            $ville = new Ville();
            $form = $this->createForm(VilleType::class, $ville);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($ville);
                $em->flush();

                return $this->redirectToRoute('ville');
            }

            return $this->render('ville/ville.html.twig', [
                'villes'=>$villes,
                'formNew' => $form->createView(),
            ]);
        }



    }

    // methode DELETE not allowed à l'ENI
    /**
     * @Route("/delete/{id}", name="ville_delete", methods={"GET"})
     */
    public function delete(EntityManagerInterface $em, Ville $ville): Response
    {
            $em->remove($ville);
            $em->flush();

        return $this->redirectToRoute('ville');
    }

    /**
     * @Route("/edit/{id}", name="ville_edit", methods={"GET","POST"})
     */
    public function edit(EntityManagerInterface $em, Request $request, Ville $ville): Response
    {
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('ville');
        }

        return $this->render('ville/edit.html.twig', [
            'formEdit' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("/new", name="ville_new", methods={"GET","POST"})
//     */
//    public function new(Request $request, EntityManagerInterface $em): Response
//    {
//        $ville = new Ville();
//        $form = $this->createForm(VilleType::class, $ville);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $em->persist($ville);
//            $em->flush();
//
//            return $this->redirectToRoute('home');
//        }
//
//        return $this->render('ville/ville.html.twig', [
//            'formNew' => $form->createView(),
//        ]);
//    }


//    /**
//     * @Route("/", name="ville_index", methods={"GET"})
//     */
//    public function index(VilleRepository $villeRepository): Response
//    {
//        return $this->render('ville/index.html.twig', [
//            'villes' => $villeRepository->findAll(),
//        ]);
//    }
//
//    /**
//     * @Route("/new", name="ville_new", methods={"GET","POST"})
//     */
//    public function new(Request $request): Response
//    {
//        $ville = new Ville();
//        $form = $this->createForm(VilleType::class, $ville);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($ville);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('ville_index');
//        }
//
//        return $this->render('ville/new.html.twig', [
//            'ville' => $ville,
//            'form' => $form->createView(),
//        ]);
//    }
//
//    /**
//     * @Route("/{id}", name="ville_show", methods={"GET"})
//     */
//    public function show(Ville $ville): Response
//    {
//        return $this->render('ville/show.html.twig', [
//            'ville' => $ville,
//        ]);
//    }
//
//    /**
//     * @Route("/{id}/edit", name="ville_edit", methods={"GET","POST"})
//     */
//    public function edit(Request $request, Ville $ville): Response
//    {
//        $form = $this->createForm(VilleType::class, $ville);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->getDoctrine()->getManager()->flush();
//
//            return $this->redirectToRoute('ville_index');
//        }
//
//        return $this->render('ville/edit.html.twig', [
//            'ville' => $ville,
//            'form' => $form->createView(),
//        ]);
//    }
//
//    /**
//     * @Route("/{id}", name="ville_delete", methods={"DELETE"})
//     */
//    public function delete(Request $request, Ville $ville): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$ville->getId(), $request->request->get('_token'))) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->remove($ville);
//            $entityManager->flush();
//        }
//
//        return $this->redirectToRoute('ville_index');
//    }
}
