<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;

class TwiliosmsController extends AbstractController
{
    #[Route('/twiliosms', name: 'app_twiliosms')]
    public function index(): Response
    {
        return $this->render('twiliosms/index.html.twig', [
            'controller_name' => 'TwiliosmsController',
        ]);
    }
    #[Route('/twiliosmsEn', name: 'app_twiliosms_send')]

    public function sendSmsAction(Client $twilioClient)
{
    $toNumber = '+21656765293'; // Replace with the phone number you want to send the SMS to
    $fromNumber = '+18588793064';

    $message = $twilioClient->messages->create(
        $toNumber,
        [
            'from' => $fromNumber,
            'body' => 'Hello from Twilio and Symfony!',
        ]
    );

    return new Response('SMS sent with ID: ' . $message->sid);
}
}
