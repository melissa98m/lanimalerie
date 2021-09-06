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
use Symfony\Component\Mime\Address;
use App\Repository\UserRepository; 
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
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
     * @Route("/success", name="order_success")
     */

    public function show(Order $order , User $user , MailerInterface $mailer , Cart $cart): Response
    {
       
       

    if(!$order->getState() == 0){
        $cart->remove();
        $order->setState(2);
        $this->entityManager->flush();


    }

        // ici le mail de validation
        $email = (new Email())
        ->from(new Address('melissa.mangione@gmail.com' , 'NoReply'))
        ->to($user->getEmail())
        ->subject('Validation de votre commande');
        

        

        $mailer->send($email);

        
        return $this->render('order/orderSuccess.html.twig', [
            'order' => $order,
        ]);
        
    

    
        
    

       
    }}



