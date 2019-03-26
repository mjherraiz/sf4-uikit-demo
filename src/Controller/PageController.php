<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Advertising;
use App\Entity\Tag;

class PageController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Advertising::class);
        $ads = $repository->findBy([]);
        $repository = $this->getDoctrine()->getRepository(Tag::class);
        $tags = $repository->findAll();
        return $this->render('home/index.html.twig', [
            'ads' => $ads,
            'tags' => $tags,
        ]);
    }
}
