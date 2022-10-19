<?php


namespace App\Http\Controllers\Issues;

use App\Events\StreamUpdated;
use App\Http\Controllers\Issues\BaseIssueController;
use App\User;
use App\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\InviteUser;

class UpdateIssueController extends BaseIssueController
{
    use InviteUser;

    public function addLogEntry(Request $request, $id)
    {
        $user = User::current();
        $issue = Issue::find($id);
        if($request->has('status')){
            $status = request('status');
        } else {
            $status = $issue->attributes;
        }
        $validator = $this->createValidateAddLogEntry($request);

        if ($validator->fails()) {
            return redirect()->back()->with('message',  'Issue could not be updated, please update the description and retry.')->withInput()->withErrors($validator);
        }


        try{
            $issue = $this->doUpdateOfIssue($issue,$status, $request);

            $stream = $this->createStream();
            $this->addIssueUpdatedLog($stream['stream'], $user, $issue);
            event(new StreamUpdated($user,$stream['stream_id'],request('description')));
            $this->updateTenantEmail($issue);
        } catch (\Exception $e){
            return redirect()->back()->with('message',  'There was a problem updating the issue. Please contact Tenancy Stream with the issue no:UIC-02')->withInput();
        }

        return redirect("/issue/".$id)->with('message',  'Issue updated successfully');
    }

    private function createValidateAddLogEntry($request){
        $validator = Validator::make($request->all(), [
            'description' => 'required|max:500',
        ]);
        return $validator;
    }

    public function updateIssue(Request $request, $id)
    {
        $user = User::current();
        $issue = $this->getIssueRestrictedByUser($user, $id);
        $status = $this->getIssueStatusForUpdate($user, $id, $request);

        $validator = $this->createValidatorUpdateIssue($request);

        if ($validator->fails()) {
            return redirect()->back()->with('message',  'Issue could not be updated, please update highlighted and retry.')->withInput()->withErrors($validator);
        }

        try{
            $issue = $this->doUpdateOfIssue($issue, $status, $request);
            if (request('invite')){
                $this->inviteContractor($issue);
            }
            if (request('assignee')){
                $this->sendAssignedEmailToContractor($issue, $user, request('assignee'));
            }

            $link = "/issue/".$id;
            $user = $this->createUser();
            $stream = $this->createStream();

            activity($stream['stream'])
                ->withProperties(['propertyId' => $issue->property_id, 'messageType' => 'PropertyIssue' , 'issueDescription' => request('description'), 'issueStatus' => $status,'issueLink' => $link, 'issueID' => $id, 'firstName' => $user->firstName, 'lastName' =>  $user->lastName, 'profileImage' => $user->profileImage, 'userType' => $user->userType])
                ->log('A issue was updated');
            event(new StreamUpdated($user, $stream['stream_id'], request('description')));
            $this->updateTenantEmail($issue);
        } catch (\Exception $e){
            return redirect()->back()->with('message',  'There was a problem updating the issue. Please contact Tenancy Stream with the issue no:UIC-01')->withInput();
        }

        return redirect("/issue/".$id)->with('message',  'Issue updated successfully');
    }

    private function createValidatorUpdateIssue($request){
        $rules = [
            'mainDescription'   => 'required|max:500'
        ];
        if(User::current()->isAn('agent')){
            $rules = array_merge($rules, [
                'title'             => 'required|max:150',
                'confidential'      => 'required|max:5',
                'status'            => 'required',
                'priority'          => 'required|integer|min:1|max:5',]
            );
        }
        return Validator::make($request->all(), $rules);
    }
}
