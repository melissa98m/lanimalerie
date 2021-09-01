<?php

namespace App\Controller;
use App\Classe\Cart;
use App\Entity\Adress;
use App\Form\AdressType;
use App\Repository\AdressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/moncompte/adresses")
 */
class AdressController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
     $this->entityManager = $entityManager;
 }
    /**
     * @Route("/", name="account_adresses", methods={"GET"})
     */
    public function index(AdressRepository $adressRepository): Response
    {

        
        return $this->render('adress/index.html.twig', [
            'adresses' => $adressRepository->findAll(),
        ]);
    }

    /**
     * @Route("/ajouter-adresse", name="adress_new", methods={"GET","POST"})
     */
    public function new(Cart $cart , Request $request): Response
    {
        $adress = new Adress();
        $form = $this->createForm(AdressType::class, $adress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adress->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($adress);
            $entityManager->flush();
            if($cart->get()){
                return $this->redirectToRoute('order');

            }else{
            return $this->redirectToRoute('account_adresses', [], Response::HTTP_SEE_OTHER);
        }
    }
        return $this->renderForm('adress/new.html.twig', [
            'adress' => $adress,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="adress_show", methods={"GET"})
     */
    public function show(Adress $adress): Response
    {
        return $this->render('adress/show.html.twig', [
            'adress' => $adress,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="adress_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Adress $adress): Response
    {


         if (!$adress || $adress->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_adresses');
         }
        $form = $this->createForm(AdressType::class, $adress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('account_adresses', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adress/edit.html.twig', [
            'adress' => $adress,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="adress_delete")
     */
    public function delete($id): Response
    {
        $adress = $this->entityManager->getRepository(Adress::class )->findOneById($id);

        if (!$adress && $adress->getUser() != $this->getUser()) {

           $this->entityManager->remove($adress);
            $this->entityManager->flush();
        }
    
        return $this->redirectToRoute('account_adresses');
    }
}
