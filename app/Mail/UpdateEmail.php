<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class UpdateEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $mailData;

    public function __construct($mailData)
    {
        $this->mailData = $mailData;
        $link = url("/");
        $this->mailData['link'] = $link;
    }

    public function build()
    {
        return $this->from('dave@tenancystream.com', 'Tenancy Stream')
            ->subject($this->mailData['subject'])
            ->markdown('emails.updateAgents')
            ->with($this->mailData);
    }
}
