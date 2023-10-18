<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\VonageMessage;
class TacNotification extends Notification
{
    protected $tac;

    public function __construct($tac)
    {
        $this->tac = $tac;
    }

    public function via($notifiable)
    {
        return ['vonage'];
    }
    
    public function toVonage($notifiable)
    {
        return (new VonageMessage)
            ->content('Your Bet4d TAC is: ' . $this->tac);
    }
}
