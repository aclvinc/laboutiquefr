<?php

namespace App\Controller;

use App\Tools\Cart;
use App\Entity\Order;
use DateTimeImmutable;
use App\Form\OrderType;
use App\Entity\OrderDetail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{

    public function __construct(private EntityManagerInterface $em)
    {
        
    }

    #[Route('/commande', name: 'app_order')]
    public function index(Cart $cart): Response
    {

        if (!$this->getUser()->getAdresses()->getValues()) {
            return $this->redirectToRoute('app_account_adress_add');
        }

        $products = $cart->getProducts();

        //$order = new Order();

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser(),
        ]);

        return $this->render('order/index.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/commande/recap', name: 'app_order_recap', methods:["POST"])]
    public function add(Cart $cart, Request $request): Response
    {


        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser(),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $carriers = $form->get('carriers')->getData();
            $adresses = $form->get('adresses')->getData();
            //dd($adresses->getAdressDelivery());
            //dd($form->getData());
            //$data = $form->getData() ;
            // enregistrement de la commande -> order
            $date = new DateTimeImmutable();
            $order = new Order();
            $reference = $date->format('Ymd')."-".uniqid();
            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setAdressDelivery($adresses->getAdressDelivery());
            $order->setIsPaid(false);
            $order->setState(0); // en cours de paiement

            $this->em->persist($order);            


            foreach ($cart->getProducts() as $p) {
                $product = $p['p'];
                $qty = $p['qty'];
                $total = $p['total_item'];                

                $detail = new OrderDetail();
                $detail->setLinkedOrder($order);
                $detail->setProduct($product->getName());
                $detail->setQuantity($qty);
                $detail->setPrice($product->getPrice());
                $detail->setTotal($total);

                $this->em->persist($detail);

                //$order->addOrderDetail($detail);
            }

            $this->em->flush();

            //dd($order);

            return $this->render('order/add.html.twig', [
                'products' => $cart->getProducts(),
                'order' => $order,
                'carrier' => $carriers,
                'reference' => $order->getReference(),
            ]);       
        }

        return $this->redirectToRoute('app_cart');
    }
}


