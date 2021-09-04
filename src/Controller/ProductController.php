<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Product;
use App\Form\ProductType;
use App\Form\SearchType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
  private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/admin", name="product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository , Request $request): Response
    {
        
        $search = new Search() ;
        $form = $this->createForm(SearchType::class , $search);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            $products = $this->entityManager->getRepository(Product::class)->findWithSearch($search);
            
        }else{
            $products = $this->entityManager->getRepository(Product::class)->findAll();
        }
        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
            
        ]);
    }

    /**
     * @Route("/admin/new", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $product = new Product(); // crrer un nouveau product
        $form = $this->createForm(ProductType::class , $product); // creer le formulaire
        $form->handleRequest($request);
        //$entityManager->persist($formadd);
        if ($form->isSubmitted() && $form->isValid()) {// condition si le formulaire est valide
            $imageFile = $form->get('image')->getData(); //recuperer le contenue champ upload envoyé

            if($imageFile) {// condition si il y a un logo , traitement du fichier
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);// recupere le nom de ficher original uploader (juste le nom)
                $safeFilename = $slugger->slug($originalFilename);// conversion le nom du fichier en un mon slug(nom de fichier sans escapace accent utilisable en url)
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();// crer un nouveau nom de fichier a partir du slug + id unique(pas de probleme upload fichier nom identique + extension (jpeg ...))
                try{ 
                    //copie le fichier upload sur le serveur avec le nouveau nom
                    $imageFile->move($this->getParameter('upload_directory'), $newFilename);
                }catch(FileException $e){
                    var_dump($e);
                    die('Erreur');
                }
                $product->setImage($newFilename);
            }
            
            
            
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
            //si tout est bon retourne sur la page listing produit
            return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
        }
         //Page ajout produits avec formulaire
        return $this->renderForm('product/new.html.twig', [
            'products' => $product,
            'form' => $form,
        ]);
      
    }

    /**
     * @Route("/{id}/back", name="product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/back/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product , SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageProduct')->getData(); //recuperer le contenue champ upload envoyé
    
            if($imageFile) {// condition si il y a un logo , traitement du fichier
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);// recupere le nom de ficher original uploader (juste le nom)
                $safeFilename = $slugger->slug($originalFilename);// conversion le nom du fichier en un mon slug(nom de fichier sans escapace accent utilisable en url)
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();// crer un nouveau nom de fichier a partir du slug + id unique(pas de probleme upload fichier nom identique + extension (jpeg ...))
                try{ 
                    //copie le fichier upload sur le serveur avec le nouveau nom
                    $imageFile->move($this->getParameter('upload_directory'), $newFilename);
                }catch(FileException $e){
                    var_dump($e);
                    die('Erreur');
                }
                $product->setImage($newFilename);
            }
    
            $this->getDoctrine()->getManager()->flush();
    
            return $this->redirectToRoute('list_Product', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        
        ]);
    
    }    
    /**
     * @Route("/admin/{id}", name="product_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/", name="liste_product")
     */
    public function liste_product(ProductRepository $productRepository , Request $request)
    {
        $search = new Search() ;
        $form = $this->createForm(SearchType::class , $search);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            $products = $this->entityManager->getRepository(Product::class)->findWithSearch($search);
            
        }else{
            $products = $this->entityManager->getRepository(Product::class)->findAll();
        }
        return $this->render('product/liste_product.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
        ]

        );
    }
    /**
     * @Route("/{id}", name="product_detail", methods={"GET"})
     */
    public function showDetail(Product $product): Response
    {
        return $this->render('product/detailProduct.html.twig', [
            'product' => $product,
        ]);
    }
}
