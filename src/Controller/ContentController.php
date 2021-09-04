<?php
// src/Controller/ContentController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
class ContentController extends AbstractController
{
    public function cgv(): Response
    {
        return $this->render('content/cgv.html.twig', [
            
            'description' => 'Contenu de la page',
        ]);
    }
    public function mention(): Response
    {
        return $this->render('content/MentionLegale.html.twig', [
            
            'description' => 'Contenu de la page',
        ]);
    }
    
    public function politique(): Response
    {
        return $this->render('content/PolitiqueConfi.html.twig', [
            
            'description' => 'Contenu de la page',
        ]);
    }
}