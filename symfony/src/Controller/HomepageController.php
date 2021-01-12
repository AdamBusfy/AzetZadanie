<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomepageController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(): Response
    {
        //return $this->redirectToRoute('homepage');


        return $this->render(
            'page/homepage/index.html.twig',
            [
                'test' => 'qwert'
            ]
        );
    }
}