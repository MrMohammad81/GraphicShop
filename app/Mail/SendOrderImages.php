<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendOrderImages extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(private array $images, private User $user)
    {
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Send Order Images',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('mail.sendOrderImages')->with(['user' => $this->user]);

        foreach ($this->images as $filePatch)
        {
            $email->attach(storage_path('app/local_storage/products'.$filePatch));
        }
        return $email;
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
