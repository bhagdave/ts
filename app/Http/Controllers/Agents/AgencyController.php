<?php

namespace App\Http\Controllers\Agents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Properties;
use App\Agent;
use App\Contractor;

class AgencyController extends Controller
{
    public function admin(){
        $user = Auth::user();
        if ($user->userType == 'Agent'){
            if ($user->agent->main){
                $counts = $this->getCounts($user);
                $billing = $this->getBillingDetails($user);
                return view('agents.admin', compact('billing', 'counts'));
            }
        }
        return redirect()->back()->with('error', 'You do not have permission to access that area!');
    }

    private function getCounts($user){
        $counts = [];
        $counts['agents'] = $user->agent->agency->agents->count();
        $counts['contractors'] = $user->agent->agency->contractors->count();
        $counts['properties'] = $user->agent->agency->properties->count();
        $counts['tenants'] = $user->agent->agency->tenants->count();
        $counts['landlords'] = $user->agent->agency->landlords->count();
        return $counts;
    }

    private function getBillingDetails($user){
        $agency = $user->agent->agency;
        $stripeCustomer = $agency->createOrGetStripeCustomer();
        $billingUrl = $agency->billingPortalUrl(route('profile'));
        $subscribed = $agency->subscribed('standard');
        return [
            'url' => $billingUrl,
            'subscribed' => $subscribed,
            'trialEnds' => $agency->trial_ends_at,
            'founder' => $agency->founder,
            'stripeCustomer' => $stripeCustomer
        ];
    }

    public function landlords(){
        return view('soon');
    }

    public function properties(){
        return view('soon');
    }

    public function tenants(){
        return view('soon');
    }

    public function agents(){
        $user = Auth::user();
        if ($user->userType == 'Agent'){
            if (isset($user->agent)){
                if ($user->agent->main){
                    $agents = $user->agent->agency->agents->sortBy('name')->paginate(20);
                    $userType = 'Agents';
                    return view('agents.agents', compact('agents', 'userType'));
                }
            }
            return redirect()->back()->with('error', 'There seems to be a problem with your account please contact us!');
        }
        return redirect()->back()->with('error', 'You do not have permission to access that area!');
    }

    public function contractors(){
        $user = Auth::user();
        if ($user->userType == 'Agent'){
            if ($user->agent->main){
                $contractors = $user->agent->agency->contractors->sortBy('name')->paginate(20);
                $userType = 'Contractors';
                return view('agents.contractors', compact('contractors', 'userType'));
            }
        }
        return redirect()->back()->with('error', 'You do not have permission to access that area!');
    }

    public function searchAgent(Request $request){
        $user = Auth::user();
        if ($user->userType == 'Agent'){
            if ($request->input('search')){
                $search = $request->input('search');
                $userType = $request->input('usertype');
                $agents = Agent::where('id', '!=', $user->agent->id)
                    ->where('name', 'like', '%' . $search . '%')
                    ->get();
                return view('agents.agents', compact('agents', 'search', 'userType'));
            }
        }
        return redirect()->back()->with('error', 'Unable to perform search!');
    }

    public function deleteAgent(Request $request, $agentId){
        $user = Auth::user();
        if ($user->userType == 'Agent'){
            $agent = Agent::find($agentId);
            if (isset($agent)){
                try {
                    $this->reassignProperties($user, $agent);
                    $agentUser = $agent->user;
                    $agent->delete();
                    if (!isset($agentUser->property)){
                        $agentUser->delete();
                    }
                } catch (Exception $e){
                    return redirect('agency/admin/agents')->with('error', 'Unable to delete agent please contact the Stream Team');
                }
                return redirect('agency/admin/agents')->with('message', 'Agent deleted!');
            }
            return redirect('agency/admin/agents')->with('error', 'Unable to find agent!');
        }
        return redirect()->back()->with('error', 'You do not have permission to access that area!');
    }
    private function reassignProperties($user, $agent){
        Properties::where('created_by_user_id', $agent->user_id)->update([
            'created_by_user_id' => $user->sub
        ]);

    }

    public function deleteContractor( $contractorId){
        $user = Auth::user();
        if ($user->userType == 'Agent'){
            $contractor = Contractor::find($contractorId);
            if (isset($contractor)){
                try {
                    $contractorUser = $contractor->user;
                    $assignedIssues = $this->getContractorIssues($contractor);
                    if ($assignedIssues > 0){
                        return redirect('agency/contractors')->with('error', 'Contractor is assigned to open issues');
                    }
                    $contractor->delete();
                    if (!isset($contractorUser->property)){
                        $contractorUser->delete();
                    }
                } catch (Exception $e){
                    return redirect('agency/contractors')->with('error', 'Unable to delete contractor please contact the Stream Team');
                }
                return redirect('agency/contractors')->with('message', 'Contractor deleted!');
            }
            return redirect('agency/contractors')->with('error', 'Unable to find contractor!');
        }
        return redirect()->back()->with('error', 'You do not have permission to access that area!');
    }
    private function getContractorIssues($contractor){
        return $contractor->issues()->open()->count();
    }

    public function searchContractor(Request $request){
        $user = Auth::user();
        if ($user->userType == 'Agent'){
            if ($request->input('search')){
                $search = $request->input('search');
                $userType = $request->input('usertype');
                $contractors = Contractor::where('name', 'like', '%' . $search . '%')
                    ->orWhere('company', 'like', '%' . $search . '%')
                    ->get();
                return view('agents.contractors', compact('contractors', 'search', 'userType'));
            }
        }
        return redirect()->back()->with('error', 'Unable to perform search!');
    }
}
