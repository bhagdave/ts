<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Message;
use Mail;
use App\User;
use App\Mail\DMNotificationEmail;

class DMNotification extends Command
{
    protected $signature = 'email:dmnotification';

    protected $description = 'Notify users of unread Direct Messages';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Running DM notifications');
        $users = Message::getUsersWithUnreadMessages();
        $this->info('Sending:' . count($users));
        foreach ($users as $user){
            $userData = User::where('sub', '=', $user->recipient_sub)->first();
            if ($userData && $userData->email_notifications){
                $mailData = [
                    'name' => $userData->firstName,
                    'email' => $userData->email,
                    'subject' => 'Unread messages on Tenancy Stream'
                ];
                Mail::to($userData->email)->queue(New DMNotificationEmail($mailData));
            }
        }
    }
}
