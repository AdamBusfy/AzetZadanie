<?php

namespace App\Controller;

use App\Entity\Gallery;
use App\Entity\Item;
use App\Form\CreateGalleryForm;
use App\Form\UploadPhotoForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends AbstractController
{
    /**
     * @Route("/gallery/{id}", name="gallery")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(Request $request, int $id): Response
    {

        $item = new Item();
        $form = $this->createForm(UploadPhotoForm::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $item->setName($form->get('name')->getData());
            $item->setDescription($form->get('description')->getData());

            $tmpName = $form->get('photo')->getData();
            $relativePath = 'data/gallery/' . $id . '-' . basename($tmpName) . '-' . time();
            $photoName = $this->getParameter('kernel.project_dir') . '/public/' . $relativePath;
            move_uploaded_file($tmpName, $photoName);

            $item->setPhoto($relativePath);

            $gallery = $this->getDoctrine()
                ->getRepository(Gallery::class)
                ->find($id);

            $item->setGallery($gallery);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);
            $entityManager->flush();

            //return $this->redirectToRoute('app_login');
        }

        $items = $this->getDoctrine()->getRepository(Gallery::class)->find($id)->getItems();

        return $this->render('page/gallery.html.twig', [
            'uploadPhotoForm' => $form->createView(),
            'items' => $items
        ]);
    }
}
