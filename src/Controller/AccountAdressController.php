<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Form\AdressType;
use App\Tools\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountAdressController extends AbstractController
{

    public function __construct(private EntityManagerInterface $em)
    { 
    }

    #[Route('/compte/adresses', name: 'app_account_adress')]
    public function index(): Response
    {

        return $this->render('account/adress.html.twig', []);
    }

    #[Route('/compte/ajouter-une-adresse', name: 'app_account_adress_add')]
    public function add(Request $request, Cart $cart): Response
    {
        
        $adress = new Adress();
        $form = $this->createForm(AdressType::class, $adress);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //dd($request->get('adress')['indic']);
            $adress->setUser($this->getUser());
            //$indic = $request->get('adress')['indic'];
            //$adress->setPhone("+".$indic." ".$adress->getPhone());
            $this->em->persist($adress);
            $this->em->flush();

            if ($cart->get()) { // si le panier n'est pas vide alors direction le tunnel d'achat
                return $this->redirectToRoute('app_order');    
            }

            return $this->redirectToRoute('app_account_adress');
        }

        return $this->render('account/adress_add.html.twig', [
            'form' => $form->createView(),
            'label' => "Ajouter"
        ]);
    }   

    #[Route('/compte/modifier-une-adresse/{id}', name: 'app_account_adress_modify')]
    public function modify(Request $request, $id): Response
    {
        $rep = $this->em->getRepository(Adress::class);
        $adress = $rep->find($id);

        if (!$adress || $adress->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_account_adress_add');
        }

        $form = $this->createForm(AdressType::class, $adress);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
 
            $this->em->flush();
            return $this->redirectToRoute('app_account_adress');
        }

        return $this->render('account/adress_add.html.twig', [
            'form' => $form->createView(),
            'label' => 'Modifier',
        ]);
    }  
    #[Route('/compte/supprimer-une-adresse/{id}', name: 'app_account_adress_delete')]
    public function delete($id): Response
    {

        $rep = $this->em->getRepository(Adress::class);
        $adress = $rep->find($id) ; 

        if (!$adress || $adress->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_account_adress');
        }

        if ($adress) {
            $rep->delete($id);
            $this->em->flush();
        }
        return $this->redirectToRoute('app_account_adress');

    }    
    
}
