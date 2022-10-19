<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TenantRemovedFromProperty extends Mailable
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
            ->subject('Removed from property.')
            ->markdown('emails.tenantRemovedFromProperty')
            ->with([
                'name' => $this->mailData['name'],
                'address' => $this->mailData['address']
            ]);
    }
}
