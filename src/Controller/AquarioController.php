<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AquarioController extends AbstractController
{
    /**
     * @Route("/aquario", name="aquario")
     */
    public function index(): Response
    {
        return $this->render('aquario/index.html.twig', [
            'controller_name' => 'AquarioController',
        ]);
    }
}
