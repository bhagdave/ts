<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class NotifyUserEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $mailData;
    
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
        $this->mailData['link'] =url("/") ;
    }
 
    public function build()
    {
        return $this->from('james@tenancystream.com', 'Tenancy Stream')
            ->subject($this->mailData['subject'])
            ->markdown('emails.notifyUser')
            ->with([
                'link' => $this->mailData['link'],
                'name' => $this->mailData['name']
            ]);
    }
}
