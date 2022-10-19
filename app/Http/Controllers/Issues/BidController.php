<?php

namespace App\Http\Controllers\Issues;

use App\Http\Controllers\Controller;
use Auth;
use Mail;
use App\Issue;
use App\Http\Controllers\Issues\BaseIssueController;
use App\Contractor;
use App\Jobs\NotifyAgentsAboutBid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BidController extends BaseIssueController{
    public function index(Request $request, $issueId){
        $user = Auth::user();
        if ($user->userType == 'Contractor'){
            $user->contractor->bids()->attach($issueId);
            $this->sendEmailToAgents($issueId, $user->contractor);
            return redirect()->back()->with('message',  'Job bid for!');
        }
        return redirect()->back()->with('message',  'Only contractors can bid for jobs');
    }

    private function sendEmailToAgents($issueId, $contractor){
        $issue = Issue::find($issueId);
        $property = $this->getPropertyFromIssue($issue);
        if (isset($property)){
            NotifyAgentsAboutBid::dispatch($issue,$property, $contractor );
        }
    }

    private function getPropertyFromIssue($issue){
        return DB::table('properties')
            ->where('id', '=', $issue->property_id)
            ->first();
    }

    public function accept(Request $request, $issueId, $contractorId){
        $user = Auth::user();
        $issue = Issue::find($issueId);
        $issue->contractors_id = $contractorId;
        $issue->save();
        $this->sendAssignedEmailToContractor($issue, $user, $contractorId);
        return redirect()->back()->with('message',  'Contractor assigned to issue.');
    }

}
