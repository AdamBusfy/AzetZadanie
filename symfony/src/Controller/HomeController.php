<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home_show")
     */
    public function index(): Response
    {
        return $this->render('page/home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
