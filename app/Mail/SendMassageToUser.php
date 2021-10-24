<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMassageToUser extends Mailable
{
    use Queueable, SerializesModels;
    private $user;
    private $sePrice;
    private $date;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $sePrice, $user ,$date)
    {
       
        $this->user = $user;
        $this->sePrice = $sePrice;
        $this->date = $date;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.sendMassageToUser',['price' => $this->sePrice,'date' => $this->date,  'user' => $this->user]);
    }
}
