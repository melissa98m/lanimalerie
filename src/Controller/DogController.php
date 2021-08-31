<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DogController extends AbstractController
{
    /**
     * @Route("/dog", name="dog")
     */
    public function index(): Response
    {
        return $this->render('dog/index.html.twig', [
            'controller_name' => 'DogController',
        ]);
    }
}
