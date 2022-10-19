<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;
use App\Tenant;
use App\Mail\UpdateEmail;

class UpdateTenants extends Command
{
    protected $signature = 'email:updatetenants';

    protected $description = 'Send a platform update to all the tenants';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info("Starting the update");
        $update = \DB::table('update')->first();
        $tenants = Tenant::all();
        foreach($tenants as $tenant){
            if (isset($tenant->user)){
                if ($tenant->user->email_notifications){
                    $name = $tenant->user->firstName ?? $tenant->name;
                    $mailData = [
                        'subject' => 'Tenancy Stream Update',
                        'email' => $tenant->user->email,
                        'message' => $update->message,
                        'name' => $name
                    ];
                    Mail::to($mailData['email'])->queue(new UpdateEmail($mailData));
                }
            }
        }
    }
}
