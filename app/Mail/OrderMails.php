<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderMails extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData; // Use this to pass all email data

    /**
     * Create a new message instance.
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData; // Assign all mail data to a public property
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->mailData['subject'], // Correctly extract subject from the passed data
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $viewData = [
            'order' => $this->mailData['order'], // Always pass the order
        ];

        if (isset($this->mailData['user'])) {
            $viewData['user'] = $this->mailData['user']; // Pass the user if it exists
        }
        return new Content(
            view: 'emails.order', 
            with: $viewData, 
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