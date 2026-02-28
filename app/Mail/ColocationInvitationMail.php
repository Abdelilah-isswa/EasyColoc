<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ColocationInvitationMail extends Mailable
{
    use Queueable, SerializesModels;
    public $token;
    public $colocationName;



    /**
     * Create a new message instance.
     */
 public function __construct($invitation, $colocation)
    {
        $this->token = $invitation->token;
        $this->colocationName = $colocation->name;

        
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Colocation Invitation Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.colocation_invitation',
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
public function build()
{
    $url = route('invitations.accept', $this->token);
    
    return $this->subject('Colocation Invitation')
                ->view('emails.colocation_invitation')
                ->with([
                    'colocationName' => $this->colocationName,
                    'url' => $url,
                ]);
}
}


