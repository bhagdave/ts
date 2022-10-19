<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Mail;
use App\Mail\DMNotificationEmail;

class StreamNotifications extends Command
{
    protected $signature = 'email:streamnotification';

    protected $description = 'Send email notifications for unread stream messages';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $users = $this->getUsersWithUnreadStreamMessages();
        foreach($users as $user){
            if ($user->email_notifications){
                $mailData = [
                    'name' => $user->firstName,
                    'email' => $user->email,
                    'subject' => 'Unread messages on Tenancy Stream'
                ];
                // Using the same email as DM Notification on purpose
                // It just says unread messages
                Mail::to($user->email)->queue(new DMNotificationEmail($mailData));
            }
        }
    }

    private function getUsersWithUnreadStreamMessages(){
        return DB::table('users')
            ->select('users.email', 'users.firstName', 'last_activity', 'email_notifications')
            ->distinct()
            ->join('stream_user', 'user_id', '=', 'users.id')
            ->join('stream', 'stream.id', '=', 'stream_user.stream_id')
            ->whereRaw('last_message > last_activity') 
            ->get();
    }
}
