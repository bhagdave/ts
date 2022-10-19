<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Properties;
use App\Issue;
use App\Hint;
use App\Invitation;
use App\Advert;
use App\Contractor;
use SimpleXMLElement;

class Dashboard extends Controller
{

    private $user;

    public function index(Request $request)
    {
        $this->user = Auth::user();
        session(['wizard' => $request->input('wizard')]);
        // Please note that the properties returned are restricted by a global scope define in the trait - MultiTenatedProperty
        $propertiesQuery = Properties::all();
        $redirectTo = $this->getRedirectIfNoProperties($propertiesQuery);
        if (!is_null($redirectTo)){
            return $redirectTo;
        }
        $redirectTo = $this->getDefaultTenantView();
        if (!is_null($redirectTo)){
            return $redirectTo;
        }
        $issuesQuery = $this->generateIssuesQuery();
        $openIssues = null;
        if ($this->user->userType == 'Contractor'){
            $openIssues = Issue::getOpenIssuesForContractor($this->user->contractor);
        }
        return $this->getViewWithData( $issuesQuery, $openIssues, $propertiesQuery);
    }
    private function getRedirectIfNoProperties($propertiesQuery){
        if (count($propertiesQuery) == 0) {
            return redirect('/welcome');
        }
        return null;
    }
    /*
     * If a tenant has a property attached then get data and display a dashboard
     * for them.  If they do not then it will go through the normal display route
     * REMEMBER - Tenants can register independent of agents which is why this
     * code is required
     */
    private function getDefaultTenantView(){
        if ($this->user->userType == 'Tenant'){
            $tenant = $this->user->tenant;
            if (isset($tenant)){
                if (isset($tenant->property)){
                    $channel = $this->getNewsFeed(10);
                    $advert = Advert::getAdvertForType($this->user->userType);
                    // This is set if the tenants property has no agency attached or if they have no landlord attached to it
                    $tenantNeedsToInvite = ($tenant->property->agent_id == 0) && ($tenant->property->created_by_user_id == $this->user->sub);
                    $tenantWaiting = $this->tenantSentInvite() && ($tenant->property->created_by_user_id == $this->user->sub);
                    return view('dashboard',compact('tenantWaiting', 'tenantNeedsToInvite', 'channel', 'advert'));
                }
            }
        }
    }

    private function tenantSentInvite(){
        return Invitation::where('user_id',$this->user->id)->exists();
    }
    private function getNewsFeed($numberOfItems){
        $xml = new SimpleXMLElement("http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/front_page/rss.xml",null,true);
        // Limit the feed to first x items
        $items = $xml->xpath(sprintf('/rss/channel/item[position() <= %d]', $numberOfItems));
        return $items;
    }
    private function generateIssuesQuery(){
        $issuesQuery = null;
        $user = $this->user;
        if ($user->userType =='Agent') {
            if (isset($user->agent)){
                $issuesQuery = Issue::whereHas('property', function ($q) use ( $user) {
                    $q->where('created_by_user_id', $user->sub)->orWhere('agent_id', $user->agent->agency_id);
                })->orderBy('updated_at', 'desc')->get();
            }
        }

        if($user->userType == 'Contractor'){
            $contractor = Contractor::where('sub', '=', $user->sub)->first();
            if ($contractor){
                $issuesQuery = Issue::where('contractors_id', '=', $contractor->id)
                   ->orderBy('updated_at', 'desc')->get(); 
            }
        }

        if ($user->userType =='Landlord') {
            $issuesQuery = Issue::whereHas('property', function ($q) use ($user) {
                $q->where('created_by_user_id', $user->sub);
            })->orderBy('updated_at', 'desc')->get();

            foreach ($issuesQuery as $issueFix => &$item) {
                if ($item->extra_attributes->confidential==="true") {
                    unset($issuesQuery[$issueFix]);
                }
            }
        }
        if ($user->userType =='Admin') {
            $issuesQuery = Issue::orderBy('updated_at', 'desc')->get();
        }
       return $issuesQuery; 
    }
    private function getViewWithData($issuesQuery, $openIssues, $propertiesQuery){
        $numberOfItems = 5;
        if ($this->user->userType == 'Tenant'){
            $numberOfItems = 10;
        }
        $channel = $this->getNewsFeed($numberOfItems);
        $advert = Advert::getAdvertForType($this->user->userType);
        $reminders = null;
        $hint = null;
        if ($this->user->userType == 'Agent'){
            if (isset($this->user->agent)){
                if (isset($this->user->agent->agency)){
                    $reminders = $this->user->agent->agency->reminders()
                        ->OrderBy('start_date')
                        ->where('start_date', '>', now())
                        ->limit(5)
                        ->get();
                    if ($this->user->agent->agency->onGenericTrial()){
                        $hint = $this->getHints();
                    }
                }
            }
        }
        if ($this->user->userType !='Tenant' ) {
            if (isset($issuesQuery)){
                $countOpen = $issuesQuery->where("attributes", 'Open')->count();
                $countPendingLandlord = $issuesQuery->where("attributes", 'Pending Landlord')->count();
                $countPendingContractor = $issuesQuery->where("attributes", 'Pending Contractor')->count();
                $countOngoing = $issuesQuery->where("attributes", 'On-Going')->count();

                return view('dashboard', compact(
                    'countOpen', 'countPendingLandlord', 
                    'countPendingContractor', 'countOngoing',
                    'openIssues', 'reminders', 'channel',
                    'hint', 'advert', 'propertiesQuery'
                ));
            }
         } 
        return view('dashboard', compact('channel','reminders', 'advert', 'hint'));
    }
    private function getHints(){
        $day = $this->user->created_at->diffInDays(now());
        $hint = Hint::getHintForDay($day);
        return $hint;
    }

}
