<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\ResetPassword;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;

class ResetPasswordController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/motdepasseoublier", name="reset_password")
     */
    public function index(Request $request , MailerInterface $mailer)
    {
        
        if($request->get('email')){

            $user = $this->entityManager->getRepository( User::class )->findOneByEmail($request->get('email'));
        
            if(!$user){

                //enregistrer en bases la demande de reset passaword 
                $reset_password = new ResetPassword();
                $reset_password->getUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new \DateTime);
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();

                // envoyer email a l'utilisateur pour mettre a jour le mdp
               
            $url = $this->generateUrl('update_password' ,[
                'token' => $reset_password->getToken()
            ]
            );
               $content = "Bonjour".$user->getFirstname() ."vous avez demander a reinitialiser votre mot de passe sur notre site<br>";
               $content .="Merci de cliquez sur <a href='$url' >le lien suivant pour le changer</a>";
            $email = (new Email())
            ->from('melissa.mangione@gmail.com')
            ->to($user->getEmail())
            ->subject('Reinitialiser votre mot de passe sur La Nimealierie')
            ->text($content);

            $mailer->send($email);
            }

    }
        return $this->render('reset_password/index.html.twig', [
           
        ]);
    }

    /**
     * @Route("/modifier-motdepasse/{token}", name="update_password")
     */
    public function Update(Request $request)
    {

    }
}
