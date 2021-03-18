<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use App\Repository\UserRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_index")
     *
     */
    public function index(SortieRepository $sortieRepository, UserRepository $userRepository): Response
    {

//        if($this->denyAccessUnlessGranted('ROLE_ADMIN')){
//            return $this->render('admin/index.html.twig');
//        }

        return $this->render('admin/index.html.twig',[
                'sorties' => $sortieRepository->findAll(),
                'users' =>$userRepository->findAll()
            ]
        );
    }

    /**
     * @Route("/admin/deleteUsers", name="deleteUsers", methods={"POST"})
     *
     */

    public function deleteUsers(Request $request): Response
    {

        dd($request);

//        if($this->denyAccessUnlessGranted('ROLE_ADMIN')){
//            return $this->render('admin/index.html.twig');
//        }

        //return $this->redirectToRoute('admin_index');
    }
}
