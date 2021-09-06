<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Form\BrandType;
use App\Repository\BrandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/brand")
 */
class BrandController extends AbstractController
{
    /**
     * @Route("/", name="brand_index", methods={"GET"})
     */
    public function index(BrandRepository $brandRepository): Response
    {
        return $this->render('brand/index.html.twig', [
            'brands' => $brandRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="brand_new", methods={"GET","POST"})
     */
    public function new(Request $request , SluggerInterface $slugger): Response
    {
        $brand = new Brand();
        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $logoFile = $form->get('logo')->getData();
            if($logoFile) {// condition si il y a un logo , traitement du fichier
                $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);// recupere le nom de ficher original uploader (juste le nom)
                $safeFilename = $slugger->slug($originalFilename);// conversion le nom du fichier en un mon slug(nom de fichier sans escapace accent utilisable en url)
                $newFilename = $safeFilename.'-'.uniqid().'.'.$logoFile->guessExtension();// crer un nouveau nom de fichier a partir du slug + id unique(pas de probleme upload fichier nom identique + extension (jpeg ...))
                try{ 
                    //copie le fichier upload sur le serveur avec le nouveau nom
                    $logoFile->move($this->getParameter('upload_directory'), $newFilename);
                }catch(FileException $e){
                    var_dump($e);
                    die('Erreur');
                }
                $brand->setLogo($newFilename);
            }
            




            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($brand);
            $entityManager->flush();

            return $this->redirectToRoute('brand_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('brand/new.html.twig', [
            'brand' => $brand,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="brand_show", methods={"GET"})
     */
    public function show(Brand $brand): Response
    {
        return $this->render('brand/show.html.twig', [
            'brand' => $brand,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="brand_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Brand $brand , SluggerInterface $slugger): Response
    {
        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $logoFile = $form->get('logo')->getData();
            if($logoFile) {// condition si il y a un logo , traitement du fichier
                $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);// recupere le nom de ficher original uploader (juste le nom)
                $safeFilename = $slugger->slug($originalFilename);// conversion le nom du fichier en un mon slug(nom de fichier sans escapace accent utilisable en url)
                $newFilename = $safeFilename.'-'.uniqid().'.'.$logoFile->guessExtension();// crer un nouveau nom de fichier a partir du slug + id unique(pas de probleme upload fichier nom identique + extension (jpeg ...))
                try{ 
                    //copie le fichier upload sur le serveur avec le nouveau nom
                    $logoFile->move($this->getParameter('upload_directory'), $newFilename);
                }catch(FileException $e){
                    var_dump($e);
                    die('Erreur');
                }
                $brand->setLogo($newFilename);
            $this->getDoctrine()->getManager()->flush();
            }
            return $this->redirectToRoute('brand_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('brand/edit.html.twig', [
            'brand' => $brand,
            'form' => $form,
        ]);
    
}

    /**
     * @Route("/{id}", name="brand_delete", methods={"POST"})
     */
    public function delete(Request $request, Brand $brand): Response
    {
        if ($this->isCsrfTokenValid('delete'.$brand->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($brand);
            $entityManager->flush();
        }

        return $this->redirectToRoute('brand_index', [], Response::HTTP_SEE_OTHER);
    }
}
