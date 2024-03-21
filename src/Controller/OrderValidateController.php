<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderValidateController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
        
    }

    #[Route('/commande/merci/{checkoutSessionId}', name: 'app_order_validate')]
    public function index($checkoutSessionId): Response
    {
        $order = $this->em->getRepository(Order::class)->findOneBy([
            'stripeCheckoutSessionId' => $checkoutSessionId,
            'user' => $this->getUser(),
        ]);
        //dd($order);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if (!$order->isIsPaid()) { // protection contre les F5
            $order->setState(2);
            $order->setIsPaid(1);
            $this->em->flush();
        }


        return $this->render('order_validate/success.html.twig', [
            'order' => $order,
        ]);
    }
}
