<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CongratulationsLoalty extends Mailable
{
    use Queueable, SerializesModels;

    protected $loyalty;
    protected $customer;
    /**
     * Create a new message instance.
     */
    public function __construct($loyalty, $customer)
    {
        $this->loyalty = $loyalty;
        $this->customer = $customer;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Chúc mừng lên cấp độ trung thành',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.mail_congratulations_lotalty',
            with: [
                'loyalty' => $this->loyalty,
                'customer' => $this->customer
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
