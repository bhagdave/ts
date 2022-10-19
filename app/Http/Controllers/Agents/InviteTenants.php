<?php

namespace App\Http\Controllers\Agents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Properties;
use App\Tenant;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Traits\inviteUser;

class InviteTenants extends Controller
{
    use inviteUser;

    public function index(){
        $tenantProperties = DB::table('tenants')
            ->select('property_id')->distinct()
            ->whereNotNull('property_id')
            ->pluck('property_id')
            ->all();
        $properties = Properties::whereNotIn('id', $tenantProperties)
            ->distinct('id')
            ->get();
        return view('agents/tenantinvite', compact('properties'));
    }

    public function invite(Request $request){
        $emails = request('email');
        $names = request('name');
        $message = 'Invites sent for ';
        $array = [$emails, $names];
        $combinedData = array_merge_recursive(...$array);
        $canProcess = $this->createArrayToProcess($combinedData);
        foreach($canProcess['process'] as $invite){
            $tenant = New Tenant([
                'name' => $invite['name'],
                'email' => $invite['email'],
                'property_id' => $invite['property_id'],
                'sub' => null,
            ]);
            $message = $message . ' ' . $invite['property_name'];
            $tenant->save();
            $emailData = $this->buildDataForInviteEmail($invite);
            $this->inviteNewTenant($emailData);
        }
        return redirect()->back()->with('message', $message );
    }

    private function createArrayToProcess($combinedData){
        $processArray = [];
        $removed = [];
        foreach($combinedData as $key => $value){
            if (($value[0] == '') || ($value[1] == '')){
                $removed[] = $key;
                continue;
            }
            $property = Properties::find($key);
            $processArray[$key] = [
                'property_name' => $property->propertyName, 
                'property_id' => $key, 
                'name' => $value[1], 
                'email' => $value[0]];
        }
        return ['process' => $processArray, 'removed' => $removed];
        return false;
    }

    private function buildDataForInviteEmail($invite){
        $user = User::current();
        $agency = $user->agent->agency;
        $userName = $user->firstName;
        if (isset($agency)){
            $subject = $userName . " from " . $agency->company_name . " has invited you to join them in TenancyStream.com";
            $agencyName = $agency->company_name;
        } else {
            $subject = $userName . " has invited you to join them in TenancyStream.com";
            $agencyName = '';
        }
        $emailData = array(
            'name' => $invite['name'],
            'email' => $invite['email'],
            'property_address' => $invite['property_name'],
            'agencyName' => $agencyName,
            'agentName' => $userName,
            'subject' => $subject,
        );
        return $emailData;
   }
}
