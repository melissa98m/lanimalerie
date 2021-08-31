<?php
// src/Controller/ContentController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
class ContentController extends AbstractController
{
    public function cgv(string $title): Response
    {
        return $this->render('content/cgv.html.twig', [
            'title' => $title,
            'description' => 'Contenu de la page',
        ]);
    }
    public function mention(string $title): Response
    {
        return $this->render('content/MentionLegale.html.twig', [
            'title' => $title,
            'description' => 'Contenu de la page',
        ]);
    }
    public function politique(string $title): Response
    {
        return $this->render('content/PolitiqueConfi.html.twig', [
            'title' => $title,
            'description' => 'Contenu de la page',
        ]);
    }
}