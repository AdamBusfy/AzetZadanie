<?php

namespace App\Controller;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class ItemController extends AbstractController
{
    /**
     * @Route("/item", name="create_item")
     */
    public function createItem(): Response
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $item = new Item();
        $item->setName("Mountains");
        $item->setDescription('Nice view!');
        $item->setPhoto('photooo');

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($item);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new item with id '.$item->getId());
    }

    /**
     * @Route("/item/{id}", name="item_show")
     */
    public function show(Item $item): Response
    {
        return new Response('Check out this great item: ' . 'ID:   ' . $item->getId() . '  ' .  $item->getName());
    }

    /**
     * @Route("/item/edit/{id}")
     * @param int $id
     * @return Response
     */
    public function update(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $item = $entityManager->getRepository(Item::class)->find($id);

        if (!$item) {
            throw $this->createNotFoundException(
                'No item found for id '.$id
            );
        }

        $item->setName('Novy nazov.');
        $entityManager->flush();

        return $this->redirectToRoute('item_show', [
            'id' => $item->getId()
        ]);
    }

    /**
     * @Route("/item/delete/{id}")
     * @param Item $item
     * @return Response
     */
    public function delete(Item $item): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $id = $item->getId();
        $entityManager->remove($item);
        $entityManager->flush();
        return new Response('Item ' . $id . ' deleted successfully');
    }
}
