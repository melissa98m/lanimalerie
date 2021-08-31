<?php

namespace App\Controller;

use App\Form\SavType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class SavController extends AbstractController
{
    /**
     * @Route("/sav", name="sav")
     */
    public function index(): Response
    {

        $formsav = $this->createForm(SavType::class);

        return $this->render('sav/index.html.twig', [

            'formsav' => $formsav->createView()
        ]);
        
    }
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        
        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Vous allez recevoir une confirmation de reception par email');

        return $this->redirectToRoute('app_home');
    }
}

