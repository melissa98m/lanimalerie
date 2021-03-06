<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\User;
use App\Entity\Carrier;
use App\Entity\OrderDetails;
use App\Entity\Product;
use App\Form\OrderType;
use App\Form\OrdersType;
use Knp\Snappy\Pdf;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;



       

class OrderController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager , Pdf $pdf){
        $this->entityManager = $entityManager;
        $this->pdf = $pdf;

           
    }


    /**
     * @Route("backoffice/orders", name="order_index", methods={"GET"})
     */
    public function showAll(OrderRepository $orderRepository): Response
    {
        return $this->render('order/showAll.html.twig', [
            'orders' => $orderRepository->findAll(),
        ]);
    }

    /**
     * @Route("/commande", name="order_new", methods={"GET","POST"})
     */
    public function new(Request $request , Cart $cart ): Response
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
     * @Route("/order/{id}", name="order_show", methods={"GET"})
     */
    public function show(Order $order): Response
    {
        return $this->render('order/show.html.twig', [
            'order' => $order,
        ]);
    }

    /**
     * @Route("/order/{id}/edit", name="order_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Order $order): Response
    {
        $form = $this->createForm(OrdersType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('order/edit.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/order/{id}", name="order_delete", methods={"POST"})
     */
    public function delete(Request $request, Order $order): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('order_index', [], Response::HTTP_SEE_OTHER);
    }
   

       
    

    

    /**
     * @Route("/commande", name="order")
     */

    public function index(Cart $cart , Request $request)
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
     * @Route("/commande/recapitulatif", name="order_recap" )
     */

    public function add(Cart $cart , Request $request)
    {
        

        $form = $this->createForm(OrderType::class , null , [
            'user' => $this->getUser(),
           

        ]);

        $form->handleRequest($request);
        if($form->isSubmitted()  && $form->isValid()){
            

            $paiement = $form['paiement']->getData(); 

            $date = new \DateTime();
            $carriers = $form->get('carriers')->getData();
            $delivery = $form->get('adresses')->getData(); //recuperer le contenue champ upload envoy??
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
                $order->setState(0);


                



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
            'delivery' => $delivery, 
            'order'  => $orderDetails,
            'paiement' => $paiement
        ]);
    }
        return $this->redirectToRoute('cart');
    }


    /**
     * M??thode rempla??ant l'API banque.
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function validationCommandeAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository(Order::class)->findOneById($id);

        if (!$order or $order->getPayer() == true) {
            throw $this->createNotFoundException('La commande n\'existe pas.');
        }

        $order->setValider(true);
        $order->setReference($this->container->get('setNewReference')->reference()); // service
        $em->flush();

        $session = $request->getSession();
     
        $session->remove('cart');
        

        $this->get('session')->getFlashBag()->add('success', 'Votre commande est valid??e avec succ??s.');
        return $this->redirect($this->generateUrl('order_success'));
    }



   
    

}



