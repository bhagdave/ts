<?php
namespace App\Http\Controllers\Issues;

use App\Http\Controllers\Controller as BaseController;
use App\Issue;
use App\Stream;
use App\Contractor;
use App\Traits\IssueUpdateEmails;
use App\User;
use Illuminate\Http\Request;
use Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Notifications\ContractorAssignedNotification;

class BaseIssueController extends BaseController
{
    use IssueUpdateEmails;

    protected function getIssueRestrictedByUser($user, $id){
        if ($user->userType =='Agent'){
            $issuesQuery = Issue::whereHas('property', function($q) use($user){
                $q->where('created_by_user_id', $user->sub)->orWhere('agent_id', $user->agent->agency_id);
            });
        }


        if ($user->userType =='Landlord'){
            $issuesQuery = Issue::whereHas('property', function($q) use ($user){
                $q->where('created_by_user_id', $user->sub);
            });
        }


        if ($user->userType =='Tenant'){
            $issuesQuery = Issue::where('property_id',  $user->tenant->property_id)
                ->where( function($query) use ($user){
                    $query->where('issues.private','=', false)
                        ->orWhere('creator_id', $user->sub);
                });
        }


        if ($user->userType =='Admin'){
            $issuesQuery = Issue::whereHas('property', function($q) {

            });
         }

        if ($user->userType == 'Contractor'){
            $issuesQuery = Issue::join('contractors', 'contractors_id', '=', 'contractors.id')
                ->join('users', 'users.sub', '=', 'contractors.sub')
                ->select('issues.*')
                ->where('users.sub', '=', $user->sub);
        }

        $issue = $issuesQuery->where('issues.id', $id)->first();
        if (is_null($issue)) {
            abort(403);
        }

        return $issue;
    }

    protected function getIssueStatusForUpdate($user, $id, $request){
        $status =  Issue::where('id', $id)->first()->attributes;

        if($user->isAn('landlord') || $user->isAn('agent')){
            if($request->has('status')){
                $status = request('status');
            }
        }
        return $status;
    }

    protected function doUpdateOfIssue($issue, $status, Request $request){
        $issue->attributes = $status;
        if($request->input('categories_id')){
            $issue->categories_id = $request->input('categories_id');
        }
        if($request->input('description', false)){
            $issue->description = $request->input('description');
        }
        if ($request->input('invite')){
            $issue->invite = $request->input('invite');
        }
        if($request->input('assignee', false)){
            $issue->contractors_id = $request->input('assignee');
        }

        //set the extra_attribute values on the issue
        $issue->extraAttributes->status = $request->input('status');
        if($request->input('title', false)){
            $issue->extraAttributes->title = $request->input('title');
        }
        if($request->input('mainDescription', false)){
            $issue->extraAttributes->mainDescription = $request->input('mainDescription');
        }
        if($request->input('confidential', false)){
            $issue->extraAttributes->confidential = $request->input('confidential');
        }
        if($request->input('priority', false)){
            $issue->extraAttributes->priority = $request->input('priority');
        }
        if($request->input('duedate', false)){
            $issue->extraAttributes->duedate = $request->input('duedate');
        }

        $issue->save();
        return $issue;
    }

    protected function createUser(){
        $user= new \stdClass();

        $user->firstName = User::current()->firstName;
        $user->lastName = User::current()->lastName;
        $user->profileImage = User::current()->profileImage;
        $user->userType = User::current()->userType;

        return $user;

    }

    protected function createStream(){
        $streamModel =  new Stream();
        $property_id = request('property_id');
        $stream = $streamModel->withExtraAttributes('property_id',$property_id)->first();
        if($stream){
            $stream_id = $stream->id;
        } else {
            $stream_id = null;
        }

        if(request('confidential') == 'true'){
            $stream="ConfidentialIssueReport";
        }
        else{
            $stream=$stream_id;
        }
        if (request('private') == 'true'){
            $stream="PrivateIssueReport";
        }
        return ['stream' => $stream, 'stream_id' => $stream_id ];
    }

    protected function addIssueUpdatedLog($stream, $user, $issue){
        $link = "/issue/".$issue->id;
        activity($stream)->withProperties([
            'propertyId' => $issue->property_id,
            'messageType' => 'PropertyIssue' ,
            'issueDescription' => request('description'),
            'issueStatus' => $issue->status,
            'issueLink' => $link,
            'issueID' => $issue->id,
            'firstName' => $user->firstName,
            'lastName' =>  $user->lastName,
            'profileImage' => $user->profileImage,
            'userType' => $user->userType
        ])->log(request('description'));
    }

    protected function updateTenantEmail($issue){
        $property = $issue->property;
        $tenants = \App\Tenant::with('user')->where('property_id', $property->id)->get();
        if ($tenants){
            foreach($tenants as $tenant){
                $this->emailTenantOfUpdate($tenant, $issue->id);
            }
        }
    }
    
    protected function sendAssignedEmailToContractor($issue, $user, $contractorId){
        $contractor = Contractor::find($contractorId);
        if (isset($contractor)){
            $agent = $user->agent;
            $agency = $agent->agency;
            $emailData = [
                'agentName' => $agent->name,
                'agencyName' => $agency->name,
                'link' => URL::to('/issue/' . $issue->id),
                'propertyAddress' => $issue->property->inputAddress . ' ' . $issue->property->inputPostcode,
                'description' => $issue->description
            ];
            $contractor->user->notify(new ContractorAssignedNotification($emailData));
        }
    }
}
