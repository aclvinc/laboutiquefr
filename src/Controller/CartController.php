<?php

namespace App\Controller;

use App\Tools\Cart;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{

    #[Route('/mon-panier', name: 'app_cart')]
    public function index(Cart $cart): Response
    {
        //dd($cart->get());

        $products = $cart->getProducts() ; 
        //dd($products);
        if (empty($products)) {
            return $this->redirectToRoute('app_products');
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cart->get(),
            'products' => $products,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_add_to_cart')]
    public function addToCart(Cart $cart, $id): Response
    {

        $cart->add($id);

        return $this->redirectToRoute('app_cart');
    }    
    #[Route('/cart/remove/', name: 'app_remove_cart')]
    public function removeCart(Cart $cart): Response
    {

        $cart->remove();

        return $this->redirectToRoute('home');
    }    
    #[Route('/cart/delete/{id}', name: 'app_delete_cart_item')]
    public function deleteCartItem(Cart $cart, $id): Response
    {

        $cart->deleteItem($id);

        return $this->redirectToRoute('app_cart');
    }
    #[Route('/cart/change/{id}/{qty}', name: 'app_change_cart_item')]
    public function changeCartItem(Cart $cart, $id, $qty): Response
    {

        $cart->changeItemQuantity($id, $qty);

        return $this->redirectToRoute('app_cart');
    }           

}
