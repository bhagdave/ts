<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\URL;

class LandlordInvitation extends Mailable
{
    use Queueable, SerializesModels;

    protected $mailData;

    public function __construct($mailData)
    {
        $this->mailData = $mailData;
        $link = url('/invite/'.$this->mailData['refCode']);
        $this->mailData['link'] = $link;
    }

    public function build()
    {
        return $this->from('james@tenancystream.com', 'Tenancy Stream')
            ->subject($this->mailData['subject'])
            ->markdown('emails.landlordInvite')
            ->with($this->mailData);
    }
}
