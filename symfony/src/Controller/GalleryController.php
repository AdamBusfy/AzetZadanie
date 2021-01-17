<?php

namespace App\Controller;

use App\Entity\Gallery;
use App\Entity\Item;
use App\Form\CreateGalleryForm;
use App\Form\DeleteForm;
use App\Form\EditForm;
use App\Form\UploadPhotoForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends AbstractController
{
    /**
     * @Route("/gallery/{id}", name="gallery")
     */
    public function index(Request $request, int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $item = new Item();
        $uploadPhotoForm = $this->createForm(UploadPhotoForm::class, $item);
        $uploadPhotoForm->handleRequest($request);

        if ($uploadPhotoForm->isSubmitted() && $uploadPhotoForm->isValid()) {
            // encode the plain password
            $item->setName($uploadPhotoForm->get('name')->getData());
            $item->setDescription($uploadPhotoForm->get('description')->getData());

            $tmpName = $uploadPhotoForm->get('photo')->getData();
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

        $deletePhotoForm = $this->createForm(DeleteForm::class);
        $deletePhotoForm->handleRequest($request);

        if ($deletePhotoForm->isSubmitted() && $deletePhotoForm->isValid()) {
            $galleryRepository = $this->getDoctrine()
                ->getRepository(Item::class);

            $item = $galleryRepository->find($deletePhotoForm->get('id')->getData());

            if (!empty($item)) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($item);
                $entityManager->flush();
            }
        }

        $editPhotoForm = $this->createForm(EditForm::class);
        $editPhotoForm->handleRequest($request);

        if ($editPhotoForm->isSubmitted() && $editPhotoForm->isValid()) {
            $galleryRepository = $this->getDoctrine()
                ->getRepository(Item::class);

            /** @var Item $item */
            $item = $galleryRepository->find($editPhotoForm->get('id')->getData());

            if (!empty($item)) {
                $item->setName($editPhotoForm->get('name')->getData());
                $item->setDescription($editPhotoForm->get('description')->getData());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();
            }

        }

        $items = $this->getDoctrine()->getRepository(Gallery::class)->find($id)->getItems();

        return $this->render('page/gallery.html.twig', [
            'uploadPhotoForm' => $uploadPhotoForm->createView(),
            'deletePhotoForm' => $deletePhotoForm,
            'editPhotoForm' => $editPhotoForm,
            'items' => $items
        ]);
    }
}
