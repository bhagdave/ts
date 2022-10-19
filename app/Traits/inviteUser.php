<?php

namespace App\Traits;
use App\Agent;
use App\User;
use App\Properties;
use App\Stream;
use App\Contractor;
use App\Invitation;
use GuzzleHttp\Exception\ClientException;
use App\Events\StreamUpdated;
use Bouncer;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Invite;
use Mail;
use App\Mail\EmailInvitation;
use App\Mail\LandlordInvitation;
use App\Mail\ContractorInvite;
use App\Mail\AgentInvitation;

trait inviteUser {

	protected static function inviteNewTenant($mailData, $actingUser = null) {
        if (isset($actingUser)){
            $user = $actingUser;
        } else {
            $user = Auth::user();
        }
        $refCode = Invite::invite($mailData['email'], $user->id);

        $mailData['refCode'] = $refCode;
        $emailToSend = new EmailInvitation($mailData);
        Mail::to($mailData['email'])->queue($emailToSend);
   }

    protected static function inviteNewLandlord($mailData) {
        $user = Auth::user();
        $refCode = Invite::invite($mailData['email'], $user->id);
        $mailData['refCode'] = $refCode;
        if (isset($mailData['property_id'])){
            self::updateInvitationWithPropertyId($refCode, $mailData['property_id']);
        }
        
        Mail::to($mailData['email'])->queue(new LandlordInvitation($mailData));
    }

    private static function updateInvitationWithPropertyId($code, $propertyId){
        if (isset($propertyId)){
            $invitation = Invitation::where('code', $code)->first();
            $invitation->property_id = $propertyId;
            $invitation->save();
       }
    }

   
    protected static function inviteNewAgent($mailData){
        $user = Auth::user();
        $refCode = Invite::invite($mailData['email'], $user->id);
        $mailData['refCode'] = $refCode;
        if (isset($mailData['property_id'])){
            $property = Properties::where('id', $mailData['property_id'])->first();
            if (!is_null($property)){
                $mailData['address'] = $property->inputAddress;
            }
            self::updateInvitationWithPropertyId($refCode, $mailData['property_id']);
        }
        
        Mail::to($mailData['email'])->queue(new AgentInvitation($mailData));
    }


    protected static function inviteContractor($issue){
        $user = Auth::user();
        $mailData = Self::createMailDataForContractorInvite($issue, $user);
        $refCode = Self::createInviteIfNoContractor($issue, $user);
        $mailData['refCode'] = $refCode;
        self::updateInvitationWithIssueDetails($refCode, $mailData, $user);
        
        $mailData['subject'] = 'Invite to work on an Issue on TenancyStream';
        Mail::to($mailData['email'])->queue(new ContractorInvite($mailData));
    }

    private static function createMailDataForContractorInvite($issue, $user){
        $mailData['id'] = $issue->id;
        $mailData['email'] = $issue->invite;
        $mailData['description'] = $issue->description;
        $mailData['agentName'] = $user->firstName;
        if ($user->userType == 'Agent'){
            if (isset($user->agent->agency)){
                $mailData['agencyName'] = $user->agent->agency->company_name;
            }
        }
        if (isset($issue->property)){
            $mailData['propertyAddress'] = $issue->property->inputAddress;
        }
        return $mailData;
    }

    private static function createInviteIfNoContractor($issue, $user){
        $contractor = Contractor::where('email','=',$issue->invite)->first();
        if (isset($contractor)){
            Self::updateIssueWithContractor($issue,$contractor->id);
            return null;
        }
        $refCode = Invite::invite($issue->invite, $user->id);
        return $refCode;
    }

    private static function updateIssueWithContractor($issue, $contractorId){
        $issue->contractors_id = $contractorId;
        $issue->save();
    }

    private static function updateInvitationWithIssueDetails($code, $mailData, $user){
        $invitation = Invitation::where('code', $code)->first();
        $invitation->issue_id = $mailData['id'];
        if ($user->userType == 'Agent'){
            if (isset($user->agent->agency_id)){
                $invitation->agency_id = $user->agent->agency_id;
            }
        }
        $invitation->save();
    }
}
