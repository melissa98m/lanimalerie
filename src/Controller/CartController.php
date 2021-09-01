<?php

namespace App\Controller;
use App\Classe\Cart;
use App\Entity\Product;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
private $entityManager;
 public function __construct(EntityManagerInterface $entityManager){
     $this->entityManager = $entityManager;
 }
    
 
 
 
 /**
     * @Route("/mon-panier", name="cart")
     */
    public function index(Cart $cart )
    {
       

        $cartComplete = [];
        
        
        foreach( $cart->get() as $id => $quantity){
            $product = $this->entityManager->getRepository(Product::class )->findOneById($id);
            
            
            
            $cartComplete[]= ['product' => $product ,
                            'quantity' => $quantity ];
        }


    
        return $this->render('cart/index.html.twig' , [
            "cart"=>$cartComplete
        ]);
    
    }

    /**
     * @Route("/cart/add/{id}", name="add_to_cart")
     */
    public function add($id, Cart $cart)
    {
        $cart->add($id);
        return $this->redirectToRoute('cart');
    }
    /**
     * @Route("/cart/remove", name="remove_my_cart")
     */
    public function remove(Cart $cart)
    {
        $cart->remove();
        return $this->redirectToRoute('product_index');
    }


    /**
     * @Route("/cart/delte/{id}" , name="delete_to_cart")
     */
    public function delete(Cart $cart , $id)
    {
        $cart->delete($id);

        return $this->redirectToRoute('cart');
    }

     /**
     * @Route("/cart/decrease/{id}" , name="decrease_to_cart")
     */
    public function decrease(Cart $cart , $id)
    {
        $cart->decrease($id);

        return $this->redirectToRoute('cart');
    }
}
