<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $recipient; // Người nhận (Admin hoặc Customer)
    public $resetLink; // Link đặt lại mật khẩu

    public function __construct($recipient, $resetLink)
    {
        $this->recipient = $recipient;
        $this->resetLink = $resetLink;
    }

    public function build()
    {
        return $this->subject('Forgot Password')
                    ->view('mail.forgot_password')
                    ->with([
                        'recipient' => $this->recipient,
                        'resetLink' => $this->resetLink,
                    ]);
    }
}
