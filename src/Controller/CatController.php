<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CatController extends AbstractController
{
    /**
     * @Route("/cat", name="cat")
     */
    public function index(ProductRepository $productRepository): Response
    {
        
        return $this->render('cat/index.html.twig', [
                'products' => $productRepository->findAll(),
        ]);
    }
}
