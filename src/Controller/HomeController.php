<?php

namespace App\Controller;

use Mailjet\Client;
use Mailjet\Resources;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {

      

        $mj = new Client($_ENV['MJ_APIKEY_PUBLIC'], $_ENV['MJ_APIKEY_PRIVATE'], true, ['version' => 'v3.1']);


        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "aclvinc@yahoo.fr",
                        'Name' => "Me"
                    ],
                    'To' => [
                        [
                            'Email' => "aclvinc@tahitibestpromo.fr",
                            'Name' => "You"
                        ]
                    ],
                    'Subject' => "My first Mailjet Email!",
                    'TextPart' => "Greetings from Mailjet!",
                    'HTMLPart' => "<h3>Dear passenger 1, welcome to <a href=\"https://www.mailjet.com/\">Mailjet</a>!</h3>
                    <br />May the delivery force be with you!"
                ]
            ]
        ];

        $response = $mj->post(Resources::$Email, ['body' => $body]);

        // Read the response
        
        //$response->success() && var_dump($response->getData());        

        $url_encode = urlencode("#Gly@l@0720");
        return $this->render('home/index.html.twig', [
            'url_encode' => $url_encode,
        ]);
    }
}
