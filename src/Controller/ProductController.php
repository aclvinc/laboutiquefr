<?php

namespace App\Controller;

use App\Tools\Cart;
use App\Tools\Search;
use App\Entity\Product;
use App\Tools\Quantity;
use App\Form\SearchType;
use App\Form\QuantityType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    #[Route('/nos-produits', name: 'app_products')]
    public function index(Request $request): Response
    {

        $rep = $this->em->getRepository(Product::class);

        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        //dd($form);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //dd($search);            
            $products = $rep->findWidthSearch($search);
        } else {
            $products = $rep->findAll();
        }

        return $this->render('product/index.html.twig', [
            "products" => $products,
            "form" => $form->createView(),
        ]);
    }

    #[Route('/produit/{slug}', name: 'app_product')]
    public function show(Cart $cart, Request $request,$slug): Response
    {
        $product = $this->em->getRepository(Product::class)->findOneBySlug($slug);
        //dd($product);

       

        if (!$product) {
            return $this->redirectToRoute('app_products');
        }

        $qty = new Quantity();

        $form = $this->createForm(QuantityType::class, $qty);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cart->add($product->getId(), $qty->quantity);
            //dd($cart);
            return $this->redirectToRoute("app_cart");
        } 

        return $this->render('product/show.html.twig', [
            "product" => $product,
            "form" => $form->createView(),
        ]);
    }

}
