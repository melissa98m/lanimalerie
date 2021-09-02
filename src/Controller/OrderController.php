<?php

namespace App\Controller;
use App\Classe\Cart;
use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends AbstractController
{
    /**
     * @Route("/commande", name="order")
     */
    public function index(Cart $cart , Request $request): Response
    {

        
        $form = $this->createForm(OrderType::class , null , [
            'user' => $this->getUser()

        ]);


        if($form->isSubmitted()  && $form->isValid()){
            $form->handleRequest($request);
        }
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    
}
}
