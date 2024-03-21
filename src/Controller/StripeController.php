<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Tools\Cart;
use App\Entity\Order;
use App\Entity\Product;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    #[Route('/command/checkout/{reference}', name: 'app_stripe_checkout')]
    public function index(EntityManagerInterface $em, $reference): Response
    {

        $order = $em->getRepository(Order::class)->findOneByReference($reference);
        //dd($order->getOrderDetails()->getValues());

        if (!$order) {
            return $this->redirectToRoute('app_order');
        }

        $line_items = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        foreach ($order->getOrderDetails()->getValues() as $od) {
            //dd($p);
            $product = $em->getRepository(Product::class)->findOneByName($od->getProduct());
            //dump($od);
            //dd($product);
            $qty = $od->getQuantity();
            //$total = $p['total_item'];                
            $line_item = [
                'quantity' => $qty,
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $od->getPrice(),
                    'product_data' => [
                        'name' => $product->getName(),
                        'description' => $product->getSubtitle(),
                        'images' => [
                            $YOUR_DOMAIN.'/public'.$product->getIllustrationPath()
                        ],
                    ]
                ]
            ] ; 
            $line_items[] = $line_item;
        }

        $line_item = [
            'quantity' => 1,
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $order->getCarrierPrice(),
                'product_data' => [
                    'name' => $order->getCarrierName(),
                    'description' => "Transporteur",
                    'images' => [
                        "https://media.istockphoto.com/vectors/transport-icon-vector-id864451142?k=6&m=864451142&s=170667a&w=0&h=20cFGgH2q-S9WWsne2ZUZaGpMqhi51_53SI4hBLOErw=",
                    ],
                ]
            ]
        ] ;     
        
        $line_items[] = $line_item;
        //dd($line_items);

        Stripe::setApiKey("sk_test_51IBPjTB3QhPTJ04neF5rTyxtNnEIrkRfvXwSQw3rhFZoOYArhsCBp2CKtU31V9gDjRqlqbCkZs7eloyGPnSTJQhH007088fdxR");

        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);    

        $order->setStripeCheckoutSessionId($checkout_session->id);
        $em->flush();
        
        $response = new Response(
            'Content',
            Response::HTTP_SEE_OTHER,
            [
                'content-type' => 'application/json',
                'location' => $checkout_session->url,
            ]
        );

        //dd($response);

        return $response;

    }
}
