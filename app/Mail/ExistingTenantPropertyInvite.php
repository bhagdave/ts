<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExistingTenantPropertyInvite extends Mailable
{
    use Queueable, SerializesModels;
    
    private $mailData;

    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    public function build()
    {
        return $this->from('james@tenancystream.com', 'Tenancy Stream')
            ->subject($this->mailData['subject'])
            ->markdown('emails.existingTenantPropertyInvite')
            ->with($this->mailData);
    }
}
