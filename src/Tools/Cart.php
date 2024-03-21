<?php


namespace App\Tools;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    private $session;
    private $rep;

    public function __construct(private RequestStack $requestStack, private EntityManagerInterface $em)
    {
        $this->session = $requestStack->getSession();
        $this->rep = $this->em->getRepository(Product::class);
    }


    public function add($id, $qty = 1)
    {
        $cart = $this->session->get("cart");

        if ($cart == null) {
            $cart = [$id => $qty];
        } else {
           if (array_key_exists($id, $cart)) {
                $cart[$id] += $qty;
           } else {
                $cart[$id] = $qty;
           }
            //dd($cart);        
        }

        $this->session->set('cart', $cart);

    }

    public function remove()
    {
        $this->session->remove('cart');
    }

    public function get()
    {
        return $this->session->get("cart");
    }

    public function getProducts() {
        $cart = $this->session->get("cart");
        //dd($cart);
        $items = [] ; 
        if ($cart) {
            foreach ($cart as $key=>$value) {
                //$keys[] = $key;
                $p = $this->rep->find($key);
                if ($p) {
                    $total_item = round($p->getPrice() * $cart[$key], 0);
                    $items[] = [
                        'p' => $p,
                        'qty' => $cart[$key],
                        'total_item' => $total_item,
                    ];                    
                } else {
                    unset($cart[$key]);
                }
            }             
        }

        //dd($products);
        return $items;
    }

    public function deleteItem($id)
    {
        $cart = $this->session->get('cart');
        unset($cart[$id]);
        $this->session->set('cart', $cart);
    }

    public function changeItemQuantity($id, $qty=1)
    {
        $cart = $this->session->get('cart');
        $cart[$id] += $qty;
        if ($cart[$id] == 0) {
            unset($cart[$id]);
        }
        $this->session->set('cart', $cart);

    }

}