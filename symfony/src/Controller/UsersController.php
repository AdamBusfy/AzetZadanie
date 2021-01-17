<?php

namespace App\Controller;

use App\Entity\Gallery;
use App\Entity\User;
use App\Form\CreateGalleryForm;
use App\Form\DeleteForm;
use App\Form\UsersControllerForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function index(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $deleteUserForm = $this->createForm(DeleteForm::class);
        $deleteUserForm->handleRequest($request);

        if ($deleteUserForm->isSubmitted() && $deleteUserForm->isValid()) {
            $galleryRepository = $this->getDoctrine()
                ->getRepository(User::class);

            $user = $galleryRepository->find($deleteUserForm->get('id')->getData());

            if (!empty($user)) {
                $entityManager = $this->getDoctrine()->getManager();

                //$galleries = $user->getGalleries();

                foreach ($user->getGalleries() as $gallery) {
                    foreach ($gallery->getItems() as $item) {
                        $entityManager->remove($item);
                    }
                    $entityManager->remove($gallery);
                }

                $entityManager->remove($user);
                $entityManager->flush();
                $this->addFlash('success_delete_user', 'User deleted successfully!');
            }
        }

        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('page/users.html.twig', [
            'users' => $users,
            'deleteUserForm' => $deleteUserForm,

        ]);
    }
}
