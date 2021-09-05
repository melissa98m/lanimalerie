<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\ResetPassword;
use App\Repository\UserRepository; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\UpdatePasswordType;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class ResetPasswordController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    
    /**
     * @Route("/motdepasseoublier", name="reset_password")
     */
    
    public function forgetPassword(Request $request , MailerInterface $mailer , UserRepository $userRepository)
    {
        
        if($request->get('email')){

            $user = $this->entityManager->getRepository( User::class )->findOneByEmail($request->get('email'));
        
            

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
               $content = "Bonjour".$user->getFirstname() ." vous avez demander a reinitialiser votre mot de passe sur notre site<br>";
               $content .="Merci de cliquez sur <a href='$url'>le lien suivant pour le changer</a>";
            
               $email = (new Email())
               ->from(new Address('melissa.mangione@gmail.com'))
            ->to($user->getEmail())
            ->subject('Reinitialiser votre mot de passe sur La Nimealierie')
            ->text($content);

            $mailer->send($email);
           
   
};
        return $this->render('reset_password/index.html.twig');
    }


    /**
     * @Route("/modifier-motdepasse/{token}", name="update_password")
     */
    
public function update(Request $request, UserPasswordEncoderInterface $passwordEncoder,$token , User $user){
    $reset_password = $this->entityManager->getRepository( ResetPassword::class)->findOneByToken($token);

    //verifier le create date est -3h
    $now = new \DateTime();
    if( $now > $reset_password->getCreatedAt()->modify('+ 3 hours')){
        //token expirer

        $this->addFlash('motice' , 'Votre demande de mot de passe a expirer , Merci de la renouveller');
        return $this->redirectToRoute('reset_password');
    }
    if( $now < $reset_password->getCreatedAt()->modify('+ 3 hours')){
        //afficher vue changer mot de passe 
        return $this->render('update_password');
    }
       $form = $this->createForm(UpdatePasswordType::class);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
        $new_pwd = $form->get('new_password')->getData();
       
        //encoder le nouveau mot de passe 
        $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                $form->get('newpassword')->getData(),
                
            ));
        //flush en base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        //ajouter un message 
        $this->addFlash('notice' , 'Votre mot de passe à bientété modifier');

        // redirection vers page de connexion
        return $this->redirectToRoute('app_login');
       }
        return $this->render('update_password/index.html.twig' , [
            'form' => $form->createView()
        ]);

    
}

}


  

