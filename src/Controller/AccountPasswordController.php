<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountPasswordController extends AbstractController
{

    private $em ;


    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/account/password', name: 'app_account_password')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $notification = null;

        $log_user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $log_user) ;         
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $id = $log_user->getId() ;  
            $user = $this->em->getRepository(User::class)->find($id); 

            $old_pw = $form->get('old_password')->getData(); 
            //dd($old_pw);
            if ($passwordHasher->isPasswordValid($user, $old_pw)) {
                $plain_pw = $form->get('new_password')->getData();
                //dd($plain_pw);
                $hashe_pw = $passwordHasher->hashPassword(
                    $user,
                    $plain_pw
                );
                $user->setPassword($hashe_pw);
                //$this->em->persist($user);
                $this->em->flush();      
                $notification = "Votre mot de passe a été mis à jour";           
            } else {
                $notification = "Votre ancien mot de passe ne correspond pas !";
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification,
        ]);
    }
}
