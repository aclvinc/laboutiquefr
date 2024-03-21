<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountOrderController extends AbstractController
{
    #[Route('/account/order', name: 'app_account_order')]
    public function index(OrderRepository $or): Response
    {

        $orders = $or->findBy([
            'user' => $this->getUser(),
            'state' => [1, 2],
        ]);

        //dd($orders);
        return $this->render('account/list.html.twig', [
            'orders' => $orders,
        ]);
    }
}
