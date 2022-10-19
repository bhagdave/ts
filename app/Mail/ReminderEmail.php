<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $reminders;

    public function __construct($reminders)
    {
        $this->reminders = $reminders;
    }

    public function build()
    {
        return $this->from('dave@tenancystream.com', 'Tenancy Stream')
            ->subject("Reminder Email")
            ->markdown('emails.remindAgents')
            ->with(['reminders' => $this->reminders]);
    }
}
