<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class ContractorInvite extends Mailable
{
    use Queueable, SerializesModels;

    private $mailData;

    public function __construct($mailData)
    {
        $this->mailData = $mailData;
        $link = url("/");
        if (isset($mailData['refCode'])){
            $link = $link . '/invite/'.$this->mailData['refCode'];
        }
        $this->mailData['link'] = $link;
    }

    public function build()
    {
        return $this->from('james@tenancystream.com', 'Tenancy Stream')
            ->subject($this->mailData['subject'])
            ->markdown('emails.contractorInvite')
            ->with($this->mailData);
    }
}
