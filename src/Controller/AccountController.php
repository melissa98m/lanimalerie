<?php

namespace App\Controller;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class AccountController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
     $this->entityManager = $entityManager;
 }
    /**
     * @Route("/moncompte/mes-commandes", name="account_orders")
     */
    public function index(): Response
    {
        $orders = $this->entityManager->getRepository(Order::class)->findAll();
        return $this->render('mon_compte/myorders.html.twig', [
            'orders' => $orders
            
        ]);
    }

    /**
     * @Route("/moncompte/mes-commandes/{id}", name="show_order_account")
     */
    public function show(Order $order)
    {
       
        return $this->render('mon_compte/showOrder.html.twig', [
            'order' => $order
            
        ]);
    }
}
