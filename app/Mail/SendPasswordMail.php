<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    private $details;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;


    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        info(json_encode($this->details));
        return $this->subject('Mot de passe de votre compte')
        ->markdown('emails.mot_de_pass', ['details' => $this->details]);


    }



}
