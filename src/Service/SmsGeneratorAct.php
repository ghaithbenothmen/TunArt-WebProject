<?php
namespace App\Service;

use Twilio\Rest\Client;

class SmsGeneratorAct
{


          public function SendSms(string $number, string $name, string $text)
    {

        $accountSid ='AC193b508426a03f257f4fcb40dff97a95';  //Identifiant du compte twilio
        $authToken ='96d1e1088007780240afdd0f8c2e0413'; //Token d'authentification
        $fromNumber ='+13346974956'; // Numéro de test d'envoie sms offert par twilio

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