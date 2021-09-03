<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\User;
use App\Entity\Carrier;
use App\Entity\OrderDetails;
use App\Entity\Product;
use App\Form\OrderType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class OrderSuccessController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
           
    }
    /**
     * @Route("/success/{id}", name="order_success")
     */

    public function show(Order $order , OrderRepository $orderRepository): Response
    {
       
    

    /*if(!$order->getState() == 0){
        $cart->remove();
        $order->setState(2);
        $this->entityManager->flush();


    }*/

        // ici le mail de validation
        $message = \Swift_Message::newInstance()
            ->setSubject('Validation de votre commande')
            ->setFrom(array('melissa.mangione@gmail.com' => "NoReply"))
            ->setTo($order->getUser()->getEmail())
            ->setCharset('utf-8')
            ->setContentType('text/html')
            ->setBody($this->renderView('order/orderSuccess.html.twig',array('user' => $order->getUser())));

        $this->get('mailer')->send($message);

        
        return $this->render('order/orderSuccess.html.twig', [
            'order' => $order,
        ]);
        
    

    
        
    

       
    }}



