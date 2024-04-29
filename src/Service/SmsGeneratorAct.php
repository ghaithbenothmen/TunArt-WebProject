<?php
namespace App\Service;

use Twilio\Rest\Client;

class SmsGeneratorAct
{


          public function SendSms(string $number, string $name, string $text)
    {

        $accountSid ='ACb352eac61b7881accd8ac01f20cbcd1d';  //Identifiant du compte twilio
        $authToken ='c75ba7b6c6b9a293f2733a2d4345c53f'; //Token d'authentification
        $fromNumber ='+16812026241'; // Numéro de test d'envoie sms offert par twilio

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