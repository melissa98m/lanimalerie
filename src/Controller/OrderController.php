<?php

namespace App\Controller;
use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Carrier;
use App\Entity\OrderDetails;
use App\Entity\Product;
use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends AbstractController
{

        private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
           
    }

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

/**
 * @Route("/commande/recapitulatif", name="order_recap" , method={"POST"} )
 */
    public function add(Cart $cart , Request $request)
    {

        
        $form = $this->createForm(OrderType::class , null , [
            'user' => $this->getUser()

        ]);

        $form->handleRequest($request);
        if($form->isSubmitted()  && $form->isValid()){

            $date = new \DateTime();
            $carriers = $form->get('carriers')->getData();
            $delivery = $form->get('adresses')->getData(); //recuperer le contenue champ upload envoyé
            $delivrey_content  = $delivery->getFirstname().' '. $delivery->getLastname();
             
              if($delivery->getCompany()){
                $delivrey_content.='<br>'.$delivery->getCompany();
            }
            $delivrey_content.='<br>'.$delivery->getNumero().''.$delivery->getName();
            $delivrey_content.='<br>'.$delivery->getCodepostal().''.$delivery->getVille();
            $delivrey_content.='<br>'.$delivery->getPays();

            //enregistrer object une commande 
                $order = new Order();
                $order->setUser($this->getUser());
                $order->setDateCreate($date);
                $order->setCarrierName( $carriers->getName());
                $order->setCarrierPrice( $carriers->getPrice());
                $order->setDelivery($delivrey_content);
                $order->setIsPaid(0);



                $this->entityManager->persist($order);
                //enregistrer produit

                
                foreach($cart->getFull() as $product){

                    $orderDetails = new OrderDetails();

                    $orderDetails->setMyOrder($order);
                    $orderDetails->setProduct($product['product']->getName());
                    $orderDetails->setQuantity($product['quantity']);
                    $orderDetails->setPrice($product['product']->getPrice());
                    $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);

                    $this->entityManager->persist($orderDetails);
                }
             
                $this->entityManager->flush();
            
        
        return $this->render('order/add.html.twig', [
            'cart' => $cart->getFull(),
            'carriers' => $carriers,
            'delivery' => $delivery
        ]);
    }
        return $this->redirectToRoute('cart');
    }
}
