<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NewAdminAddedEmail extends Mailable  implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $sender;
    public $receiver;
    public $info;
    public $title;



    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sender, $receiver, $info, $title)
    {

        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->info = $info;
        $this->subject = $title;
        $this->build(); 

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
            ->to($this->receiver)
            ->from($this->sender)
            ->view('email-template.login_credential')
            ->with([
                'info' => $this->info,
            ]);

    }
}
