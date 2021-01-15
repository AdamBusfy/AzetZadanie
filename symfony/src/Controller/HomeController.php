<?php

namespace App\Controller;

use App\Entity\Gallery;
use App\Entity\User;
use App\Form\CreateGalleryForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home_show")
     */
    public function index(Request $request): Response
    {
        $gallery = new Gallery();
        $form = $this->createForm(CreateGalleryForm::class, $gallery);
        $form->handleRequest($request);
        $user = $this->get('security.token_storage')->getToken()->getUser();

        //$user = null;
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $gallery->setName($form->get('name')->getData());
            $gallery->setDescription($form->get('description')->getData());

            $gallery->setUser($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($gallery);
            $entityManager->flush();
            //return $this->redirectToRoute('app_login');
        }

        $galleries = $this->getDoctrine()->getRepository(User::class)->find($user->getId())->getGalleries();

        return $this->render('page/homePage.html.twig', [
            'createGalleryForm' => $form->createView(),
            'galleries' => $galleries
        ]);
    }
}
