<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/compte', name: 'app_account')]
    public function index(): Response
    {

        $user = $this->getUser();
        //dd($user);

        return $this->render('account/index.html.twig', [
            'user' => $user,
        ]);
    }
}
