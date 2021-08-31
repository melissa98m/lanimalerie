<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NacController extends AbstractController
{
    /**
     * @Route("/nac", name="nac")
     */
    public function index(): Response
    {
        return $this->render('nac/index.html.twig', [
            'controller_name' => 'NacController',
        ]);
    }
}
