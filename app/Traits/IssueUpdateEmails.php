<?php

namespace App\Traits;

use Mail;
use Auth;
use App\Notifications\NotifyAgentOfIssue;
use App\Notifications\NotifyTenantOfIssueUpdate;
use Illuminate\Support\Facades\URL;

trait IssueUpdateEmails{
    protected static function emailTenantOfUpdate($tenant, $issueId){
        if (isset($tenant->user)){
            $mailData = array(
                'name' => $tenant->name,
                'subject' => "Update on property issue ",
                'link' => url("/issue/" . $issueId)
            );
            $tenant->user->notify( new NotifyTenantOfIssueUpdate($mailData));
        }
    }

    protected static function emailAgentOfIssue($user, $issueId){
        $mailData = array(
            'subject' => "New issue on property",
            'link' => url("/issue/" . $issueId)
        );

        $user->notify(new NotifyAgentOfIssue($mailData));
    }
}
