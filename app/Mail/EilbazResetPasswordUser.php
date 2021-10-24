<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EilbazResetPasswordUser extends Mailable
{
    use Queueable, SerializesModels;
    private $code;
    private $id;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($code,$id)
    {
        $this->code = $code;
        $this->id = $id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.auth.resetPasswordUser',['code' => $this->code,'id' => $this->id]);
    }
}
