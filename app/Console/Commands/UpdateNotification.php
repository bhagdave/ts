<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Mail\NotifyUserEmail;
use Mail;

class UpdateNotification extends Command
{
    protected $signature = 'email:updateusers';

    protected $description = 'Notify users of updates';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Running notification update');
        $userModel = new User();
        $users = $userModel->getUsersToNotify();
        foreach($users as $user) {
            if ($user->email_notifications){
                $mailData = [
                    'name' => $user->firstName,
                    'email' => $user->email,
                    'subject' => 'Tenancy Stream activity'
                ];
                Mail::to($user->email)->queue(New NotifyUserEmail($mailData));
                $user->nologin_email = true;
                $user->save();
            }
        }
    }
}
