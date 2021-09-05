<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;

class MonCompteController extends AbstractController
{


    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/moncompte", name="mon_compte")
     */
    public function index(): Response
    {

        return $this->render('mon_compte/index.html.twig');
    }

    /**
     * @Route("/moncompte/new_password", name="new_password")
     */

     public function newPassword(Request $request , UserPasswordEncoderInterface $passwordEncoder ){


        $notification = null;// faire une motification pour que l'utilisateur voit quand le mot de passe est changer les


        $user =$this->getUser();// recupere l'utilisateur et l'injecter dans le form
        $form = $this->createForm(ChangePasswordType::class , $user);

        //traiter le form 
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //recuperer l'ancien mdp
            $password = $form->get('password')->getData();

            //modifier le mot de passe
            if($passwordEncoder->isPasswordValid($user,$password)){
                //nouveau motdepasse
                $new_pwd = $form->get('new_password')->getData();
                //encoder le nouveau motdepasse
                $password = $passwordEncoder->encodePassword($user , $new_pwd);
                // seter le nouveau password 
                $user->setPassword($password);

                //le mettre en bdd
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($password);
                $entityManager->flush();
                $notification = "votre mot de passe à bien été modifier";
             } else{
                    $notification = "Votre mot de passe actuel nest pas le bon" ;
                }
            }

        


        return $this->render('mon_compte/new_pass.html.twig', [
            'form' => $form->createView() , 
            'notification'=> $notification
        ]);

     }
}
