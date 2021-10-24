<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProviderConfirmationCar extends Mailable
{
    use Queueable, SerializesModels;
    private $provider_car;
    private $provider;
    private $car;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($provider_car, $car, $provider)
    {
        $this->provider_car = $provider_car;
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
        return $this->markdown('emails.ProviderConfirmationCar',['provider_car' => $this->provider_car, 'car' => $this->car, 'provider' => $this->provider]);
    }
}
