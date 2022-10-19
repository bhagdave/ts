<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActiveUsers extends Mailable
{
    use Queueable, SerializesModels;

    private $emailData;

    public function __construct($emailData)
    {
        $this->emailData = $emailData;
    }

    public function build()
    {
        return $this->from('james@tenancystream.com', 'Tenancy Stream')
            ->subject("Active users for yesterday!")
            ->markdown('emails.active')
            ->with([
                'emailData' => $this->emailData
            ]);
    }
}
