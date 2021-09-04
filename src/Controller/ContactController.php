<?php

namespace App\Controller;

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request , MailerInterface $mailer)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form-> isValid()) {
            $data= $form->getData();
            $afficher = "Votre demande de contact à bien été envoyé , vous recevrai une réponse dans les 48h";
            $message = (new Email())
            ->from($data['email'])
            ->to('melissa.mangione@gmail.com')
            ->subject('Demande en prevenance du site')
            ->text('From'.' '.$data['email'].' '.$data['message'].''.'text/plain');

            $mailer->send($message);

            return $this->redirectToRoute('contact_succes');
        }

        return $this->render('contact/index.html.twig', [

            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/contact/envoyer", name="contact_succes")
     */
    public function message()
    {
        return $this->render('contact/contactSuccess.html.twig', [
            'controller_name' => 'AquarioController',
        ]);
    }
}


