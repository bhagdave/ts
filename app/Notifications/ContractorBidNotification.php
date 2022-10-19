<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class ContractorBidNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $emailData;

    public function __construct($mailData)
    {
        $this->emailData = $mailData;
    }

    public function via($notifiable)
    {
        if ($notifiable->email_notifications){
            return ['mail'];
        }
        return ['database'];
    }

    public function toMail($notifiable)
    {
        Log::info("Sending bid email to " . $notifiable->email);
        return (new MailMessage)
            ->from('james@tenancystream.com', 'Tenancy Stream')
            ->subject("Contractor bid on open issue")
            ->markdown('emails.contractorBid', $this->emailData);
    }

    public function toArray($notifiable)
    {
        return $this->emailData;
    }
}
