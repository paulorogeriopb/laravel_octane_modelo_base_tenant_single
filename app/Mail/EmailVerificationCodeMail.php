<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $code;
    public $formattedDate;
    public $formattedTime;
    public $url;

    public function __construct($user, string $code, string $formattedDate, string $formattedTime, string $url)
    {
        $this->user = $user;
        $this->code = $code;
        $this->formattedDate = $formattedDate;
        $this->formattedTime = $formattedTime;
        $this->url = $url;
    }

    public function build()
    {
        return $this->subject('Código de Verificação de E-mail')
                    ->view('emails.email_verification_code');
    }
}