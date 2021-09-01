<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart {

    private $session;
    public function __construct(SessionInterface $session) {
    $this->session = $session;
    }
    public function add($id){

    $cart = $this->session->get('cart', []);
    if(!empty($cart[$id])){
        $cart[$id]++ ;

    }else{
        $cart[$id] = 1 ;
    }

      $this->session->set('cart', $cart);
    }
    public function get(){

        return $this->session->get('cart');
    }
    public function remove(){
        return $this->session->remove('cart');
    }

    public function delete($id){
        $cart = $this->session->get('cart', []);

        unset($cart[$id]);

        return $this->session->set('cart' , $cart);
    }

    public function decrease($id){
        //verifier si la quantité du produit != 1 


        $cart = $this->session->get('cart', []);
        // retirer quantité

        if($cart[$id] > 1){
            $cart[$id]--;

        }else{
            //supprimer produit
            unset($cart[$id]);
        }
        return $this->session->set('cart' , $cart);
    }
}
