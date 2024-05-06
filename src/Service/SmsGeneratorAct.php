<?php
namespace App\Service;

use Twilio\Rest\Client;

class SmsGeneratorAct
{


          public function SendSms(string $number, string $name, string $text)
    {

        $accountSid ='ACa2d791814aa055d3d9c876c2f36c64e1';  //Identifiant du compte twilio
        $authToken ='8dd3393f26f503316b8a5ccbed4154cd'; //Token d'authentification
        $fromNumber ='+13343393088'; // Numéro de test d'envoie sms offert par twilio

        $toNumber = $number; // Le numéro de la personne qui reçoit le message
        $message = ''.$name.' vous a envoyé le message suivant:'.' '.$text.''; //Contruction du sms

        //Client Twilio pour la création et l'envoie du sms
        $client = new Client($accountSid, $authToken);

        $client->messages->create(
            $toNumber,
            [
                'from' => $fromNumber,
                'body' => $message,
            ]
        );
    }
}