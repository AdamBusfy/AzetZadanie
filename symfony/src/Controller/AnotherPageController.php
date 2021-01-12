<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class AnotherPageController extends AbstractController
{
    /**
     * @Route("/api/{id}", methods={"GET","HEAD"}, requirements={"page"="\d+"})
     *
     */
    public function show(int $id): Response
    {
        return $this->render(
            'page/homepage/index.html.twig',
            [
                'test' => $id
            ]
        );
    }

    /**
     * @Route("/number/{max}")
     */
    public function number(int $max, LoggerInterface $logger): Response
    {
        $logger->info('We are logging!');

        return $this->render(
            'page/homepage/index.html.twig',
            [
                'test' => $max
            ]
        );
    }


    /**
     * @Route("/api/posts/{id}", methods={"PUT"})
     */
    public function edit(int $id): Response
    {
        // ... edit a post
    }
}