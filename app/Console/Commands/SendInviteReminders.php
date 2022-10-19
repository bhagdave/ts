<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Invitation;
use App\Mail\EmailReminder;
Use Carbon\Carbon;
use Mail;

class SendInviteReminders extends Command
{
    protected $signature = 'email:inviteReminders';

    protected $description = 'Send email reminders on invites';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $today = new Carbon();
        if ($today->dayOfWeek == Carbon::MONDAY){ // Heroku scheduler does not allow a weekly job. Hence this
            $this->info("Sending invitation reminders!");
            $invitationModel = new Invitation();
            $pendingInvitations = $invitationModel->getPendingInvites();
            $this->info("Number of reminders to send:" . count($pendingInvitations));
            foreach($pendingInvitations as $invitation){
                if ($invitation->email) {
                    $mailData = [
                        'subject' => 'Tenancy Stream Invite',
                        'email' => $invitation->email,
                        'refCode' => $invitation->code,
                    ];
                    Mail::to($mailData['email'])->queue(new EmailReminder($mailData));
                }
            }
        }
    }
}
