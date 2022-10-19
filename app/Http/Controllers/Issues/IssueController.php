<?php

namespace App\Http\Controllers\Issues;

use App\Issue;
use App\Stream;
use Illuminate\Http\Request;
use App\Http\Controllers\Issues\BaseIssueController;
use Illuminate\Support\Collection;
use App\User;
Use App\Category;
use App\Agent;
use App\Tenant;
use App\Contractor;
use App\Properties;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Events\StreamUpdated;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Str;
use App\Traits\InviteUser;

class IssueController extends BaseIssueController
{
    use InviteUser;

    public function getIssues($propertyId = null)
    {
        $user = Auth::user();
        $issuesQuery = Issue::getIssuesForUser($user, $propertyId);
        return $issuesQuery->get();
    }

    public function exportIssues($propertyId = null){
        $user = Auth::user();
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=issues.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $issues = Issue::getIssuesForUser($user, $propertyId)->get();
        $columns = array('IssueID', 'Property', 'Description', 'Status', 'Created');

        $callback = function() use ($issues, $columns)
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach($issues as $issue) {
                fputcsv($file, array($issue->id, $issue->property, $issue->description, $issue->attributes, $issue->created_at));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function index(Request $request)
    {
        $properties = Properties::orderBy('propertyName')->get();
        $filter = $request->query('filter');
        return view('issues.index',compact('properties', 'filter'));
    }

    public function indexByProperty($propertyId)
    {
        $properties = Properties::where('id',$propertyId)->first();
        if (isset($properties)){
            $issuesQuery = Issue::orderBy('created_at', 'desc')->get();

            $issuesQuery = $issuesQuery->where('property_id',$propertyId);

            $issues = (new Collection($issuesQuery))->paginate(10);

            return view('issues.indexByProperty',compact('issues','properties'));
        }
        return redirect()->back()->with('message',  'Unable to load issues for this property.');
    }

    public function create()
    {
        $user = Auth::user();
        $properties = Properties::get(); 
        $categories = Category::all();
        $contractors = null;
        if ($user->userType == 'Agent'){
            if (isset($user->agent)){
                $contractors = $user->agent->agency->contractors;
            }
        }

        return view('issues.create', compact('properties', 'categories', 'contractors'));
    }

    public function store(Request $request)
    {
        $validator = $this->validateIssueCreate($request);
        if ($validator->fails()) {
            return redirect()->back()->with('message',  'Issue could not be created, please complete all fields and retry.')->withInput()->withErrors($validator);
        }

        $property = $this->getFirstProperty();
        if(is_null($property)){
            return redirect()->back()->with('message',  'You do not have permission to add an issue for this property .')->withInput();
        }

        $user = $this->createUser();

        try{
            $newIssue = $this->createBaseIssue($user);
            if (request('invite')){
                $this->inviteContractor($newIssue);
            }
            $stream = $this->createStream();
            $this->addIssueCreatedLog($stream['stream'],$user,$newIssue);
            event(new StreamUpdated($user,$stream['stream_id'],request('description')));
            $this->sendIssueEmails($property, $newIssue->id);
       } catch (\Exception $e){
           return redirect()->back()->with('message',  'There was a problem creating the issue. Please contact Tenancy Stream with the issue no:IC-01')->withInput();
         }

        return redirect('/issue/'.$newIssue->id)->with('message',  'Issue reported successfully');
    }

    private function validateIssueCreate($request){
        $validator = Validator::make($request->all(), [
        'property_id' => 'required',
        'description' => 'required|max:500',
        ]);
        return $validator;
    }

    private function getFirstProperty(){
        $property = \App\Properties::where('id',request('property_id'))->first();
        return $property;
    }

    private function createBaseIssue($user){
        $newIssue = new Issue([
                'property_id' => request('property_id'),
                'description' => request('description'),
                'attributes' => "Open",
        ]);

        $newIssue->extra_attributes->mainDescription = request('description');
        $newIssue->extra_attributes->author = $user->firstName." ".$user->lastName;
        $this->addIssueAttribute($newIssue,'title');
        $this->addIssueAttribute($newIssue,'priority');
        $this->addIssueAttribute($newIssue,'duedate');

        if (request('private')){
            if (request('private') === 'true'){
                $newIssue->private = 1;
            }
        }
        if (request('creator_id')){
            $newIssue->creator_id = request('creator_id');
        }
        if(request('confidential')){
             $newIssue->extra_attributes->confidential = 'true';
        }
        if (request('categories_id')){
            $newIssue->categories_id = request('categories_id');
        }
        if (request('invite')){
            $newIssue->invite = request('invite');
        }
        if (request('assignee')){
            $newIssue->contractors_id = request('assignee');
        }
        if(request('status')){
             $newIssue->extra_attributes->status = request('status');
        } else{
            $newIssue->extra_attributes->status = 'Open';
        }

        $newIssue->save();
        return $newIssue;
   }

    private function addIssueAttribute($issue,$attribute){
        if (request($attribute)){
            $issue->extra_attributes->$attribute = request($attribute);
        }
    }

    private function addIssueCreatedLog($stream, $user, $issue){
       $link = '/issue/'.$issue->id;

       activity($stream)->withProperties([
        'propertyId' => request('property_id'),
        'messageType' => 'NewPropertyIssue' ,
        'issueDescription' => request('description'),
        'issueStatus' => 'Open',
        'issueLink' => $link,
        'issueID' => $issue->id,
        'firstName' => $user->firstName,
        'lastName' =>  $user->lastName,
        'profileImage' => $user->profileImage,
        'userType' => $user->userType
       ])->log(request('description'));
    }

    private function sendIssueEmails($property, $issueId){
        if (!is_null($property->agent)) {
            $this->sendAgentIssueEmail($property, $issueId);
        } else {
            if (!is_null($property->landlord)) {
                if ($property->landlord->userType == "Landlord") {
                    // Identical email sent for agent and  Landlord TODO - Rename the email class
                    $this->emailAgentOfIssue($property->landlord->user, $issueId);
                }
            }
        }
    }

    private function sendAgentIssueEmail($property,$issueId){
        if (!is_null($property->agents)) {
            foreach($property->agents as $agent){
                $this->emailAgentOfIssue($agent->user, $issueId);
            }
        }
    }


   public function show( $issueId )
    {
        $user = Auth::user();
        $history = Activity::where('properties->issueID', $issueId)->orderBy('id', 'DESC')->get();
        $issue = $this->getIssueRestrictedByUser($user, $issueId);

        if ($user->userType =='Tenant' || $user->userType =='Landlord'){
            if($issue->extra_attributes->confidential == "true"){
                 abort(403, 'This issue has been changed to confidential.');
            }
        }

        return view('issues.show',compact('issue','history'));
    }

    public function edit( $issueId )
    {
        $user = Auth::user();
        $history = Activity::where('properties->issueID', $issueId)->orderBy('id', 'DESC')->get();
        $issue = $this->getIssueRestrictedByUser($user, $issueId);
        $categories = Category::all();
        $contractors = null;
        if ($user->userType == 'Agent'){
            $contractors = $user->agent->agency->contractors;
        }

        return view('issues.edit',compact('issue','history', 'categories', 'contractors'));
    }


    public function destroy($issueId)
    {
        $issue = Issue::find($issueId);
        if (is_null($issue)) {
            abort(403);
        }

        $user = User::current();
        $stream_id = $issue->property->stream_id;
        $property_id = $issue->property_id;

        $message = "An issue has been deleted ";
        if($issue->extra_attributes->confidential==="true"){
            $message = "A confidential issue has been deleted ";
        }

        try{
            activity($stream_id)
                ->withProperties(['propertyId' => $property_id, 'messageType' => 'Event'])
                ->log($message);
            event(new StreamUpdated($user,$issueId,$property_id));
            Issue::where('id', $issueId)->delete();
        } catch (\Exception $e){
            return redirect()->back()->with('message',  'There was a problem deleting the Issue. Please contact Tenancy Stream with the issue no:IC-03')->withInput();
        }

        return redirect('/issues/')->with('message',  'Issue deleted successfully');
    }
}
