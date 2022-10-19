<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    private $user;
    private $type;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $type = 'Unknown')
    {
        $this->user = $user;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mailArray = [
            'companyName' => $this->user['companyName'],
            'email' => $this->user['email'],
            'type' => $this->type,
            'phone' => $this->user['telephone'],
        ];
        return $this->from('james@tenancystream.com', 'Tenancy Stream')
            ->subject('New User Alert')
            ->markdown('emails.agentWelcome')
            ->with($mailArray);
    }
}
