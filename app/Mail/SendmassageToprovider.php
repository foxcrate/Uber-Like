<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendmassageToprovider extends Mailable
{
    use Queueable, SerializesModels;
    private $provider;
    private $price;
    private $date;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $price, $provider,$date)
    {
       
        $this->provider = $provider;
        $this->price = $price;
        $this->date = $date;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.sendmassageToprovider',['price' => $this->price,'date' => $this->date,  'provider' => $this->provider]);
    }
}
