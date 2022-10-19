<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrialEnding extends Mailable
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
            ->subject('Core features on your account are set to be deactivated')
            ->markdown('emails.trialEnding')
            ->with($this->mailData );
    }
}
