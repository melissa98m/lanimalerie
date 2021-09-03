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

            $message = (new Email())
            ->from($data['email'])
            ->to('melissa.mangione@gmail.com')
            ->subject('Demande en prevenance du site')
            ->text('From'.' '.$data['email'].' '.$data['message'].''.'text/plain');

            $mailer->send($message);

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [

            'form' => $form->createView()
        ]);
    }
}

