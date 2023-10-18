<?php

namespace App\Traits;
use Vonage\Client;
use Vonage\SMS\Message;
use Vonage\Client\Credentials\Basic;
trait SMS
{
    public function sendTAC($toPhoneNo)
    {
        // $status = Password::sendResetLink('anc@gmail.com');
        $client = new Client(new Basic(env('NEXMO_API_KEY'), env('NEXMO_API_SECRET')));

        $tac = mt_rand(100000, 999999);

        $message = $client->message()->send([
            'to'   => $toPhoneNo,
            'from' => env('NEXMO_PHONE_NUMBER'),
            'text' => 'Your TAC is: ' . $tac,
        ]);

        return $tac;

    }

    
}