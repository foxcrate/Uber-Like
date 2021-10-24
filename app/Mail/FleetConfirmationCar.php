<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FleetConfirmationCar extends Mailable
{
    use Queueable, SerializesModels;
    private $fleet;
    private $provider;
    private $car;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fleet, $car, $provider)
    {
        $this->fleet = $fleet;
        $this->provider = $provider;
        $this->car = $car;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.FleetConfirmationCar',['fleet' => $this->fleet, 'car' => $this->car, 'provider' => $this->provider]);
    }
}
