<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentFailure extends Mailable
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
            ->subject('Tenancy Stream - Subscription Cancelled')
            ->markdown('emails.paymentFailure')
            ->with($this->mailData);
    }
}
