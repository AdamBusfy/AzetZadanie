<?php

namespace App\Controller;

use App\Entity\Gallery;
use App\Entity\Item;
use App\Entity\User;
use App\Form\CreateGalleryForm;
use App\Form\DeleteForm;
use App\Form\EditForm;
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
        $this->denyAccessUnlessGranted('ROLE_USER');

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



        $deleteGalleryForm = $this->createForm(DeleteForm::class);
        $deleteGalleryForm->handleRequest($request);

        if ($deleteGalleryForm->isSubmitted() && $deleteGalleryForm->isValid()) {
            $galleryRepository = $this->getDoctrine()
                ->getRepository(Gallery::class);

            $gallery = $galleryRepository->find($deleteGalleryForm->get('id')->getData());

            if (!empty($gallery)) {
                $entityManager = $this->getDoctrine()->getManager();

                $galleryItems = $gallery->getItems();

                foreach ($galleryItems as $galleryItem) {
                    $entityManager->remove($galleryItem);
                }

                $entityManager->remove($gallery);
                $entityManager->flush();

            }

            if ($request->isXmlHttpRequest()) {
                return $this->json(['success' => true]);
            }
        }

        ///////

        $editGalleryForm = $this->createForm(EditForm::class);
        $editGalleryForm->handleRequest($request);

        if ($editGalleryForm->isSubmitted() && $editGalleryForm->isValid()) {
            $galleryRepository = $this->getDoctrine()
                ->getRepository(Gallery::class);

            /** @var Item $gallery */
            $gallery = $galleryRepository->find($editGalleryForm->get('id')->getData());

            if (!empty($gallery)) {
                $gallery->setName($editGalleryForm->get('name')->getData());
                $gallery->setDescription($editGalleryForm->get('description')->getData());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();
            }
        }
//////

        $galleries = $this->getDoctrine()->getRepository(User::class)->find($user->getId())->getGalleries();

        return $this->render('page/homePage.html.twig', [
            'createGalleryForm' => $form->createView(),
            'deleteGalleryForm' => $deleteGalleryForm,
            'editGalleryForm' => $editGalleryForm,
            'galleries' => $galleries
        ]);
    }
}
