<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrialEndingReportEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $agencies;

    public function __construct($agencies)
    {
        $this->agencies = $agencies;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('phil@tenancystream.com', 'Tenancy Stream')
            ->subject('Agencies trial ending')
            ->markdown('emails.trialEndingReport')
            ->with(["agencies" =>$this->agencies] );
    }
}
