<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct($username,$password,$email)
    {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $message= $this->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'))->view('emails.contact_us')
        ->with([
            'username' => $this->username,
            'password' => $this->password,
            'email' =>$this->email,
        ]);
        return $message;
    }
}
