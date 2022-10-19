<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\DirectMessage;
use stdClass;
use Auth;
use App\User;
use App\Agent;
use App\Landlord;
use App\Tenant;
use App\Message;

class DirectMessageController extends Controller
{
    public function showMessages($userType,$typeId){
        $user = Auth::user();
        $userType = strtolower($userType);
        $userToChatTo = $this->getUserSub($userType, $typeId);
        if (!$userToChatTo){
            return redirect()->back()->with(['message' => 'No user with that id']);
        }
        $otherUser = User::getUserBySub($userToChatTo);
        $property = $this->getPropertyForUser($otherUser);
        $messages = $this->getMessages($user->sub, $userToChatTo);
        Message::markMessagesRead($user->sub, $userToChatTo);
        return view('messages.show', compact('user','otherUser', 'messages', 'property'));
    }

    private function getUserSub($type, $typeId){
        $type = strtolower($type);
        switch($type) {
        case 'agent':
            return $this->getAgentSub($typeId);    
            break;
        case 'landlord':
            return $this->getLandlordSub($typeId);
            break;
        case 'tenant':
            return $this->getTenantSub($typeId);
            break;
        case 'contractor':
            return null;
            break;
        }
    }

    private function getAgentSub($agentId){
        $agent = Agent::find($agentId);
        if ($agent){
            return $agent->user_id;
        }
        return null;
    }
    private function getLandlordSub($landlordId){
        $landlord = Landlord::find($landlordId);
        if ($landlord){
            return $landlord->user_id;
        }
        return null;
    }
    private function getTenantSub($tenantId){
        $tenant =  Tenant::find($tenantId);
        if ($tenant){
            return $tenant->sub;
        }
        return null;
    }

    private function getMessages($userSub, $recipientSub)
    {
        $messages = Message::getMessagesForChat($userSub, $recipientSub);
        return $messages;
    }

    public function sendMessage(Request $request){
        $user = Auth::user();
        
        $recipient = User::getUserBySub(request('recipient'));

        if (null != request('message')){
            $message = $this->createMessageRecord($user);
            $recipient->notify(new DirectMessage($user, $message));
            return $message->toJson();
        }
        return response()->json(['error' => 'No message recieved']);
    }

    private function createMessageRecord($user){
        $message = new Message([
            'creator_sub' => $user->sub,
            'creator_type' => $user->userType,
            'creator_type_id' => $user->getTypeId(),
            'message' => request('message'),
            'recipient_sub' => request('recipient')
        ]);
        $message->save();
        return $message;
    }

    private function getPropertyForUser($user){
        if ($user->userType == 'Tenant'){
            if (isset($user->tenant)){
                if (isset($user->tenant->property)){
                    return $user->tenant->property;
                }
            }
        }
        return null;
    }
    public function markMessageAsRead($messageId){
        $user = Auth::user();
        $message = Message::find($messageId);
        if ($user->sub == $message->recipient_sub) {
            $message->read = 1;
            $message->save();
        }
        return 'Ok';
    }
    public function getUnreadMessages(){
        $user = Auth::user();
        return Message::getUnreadMessages($user->sub)->toJson();
    }
    public function dmPage(){
        $user = Auth::user();
        // Please note that the tenants/landlords returned from all are resticted by a trait found in MultiTenant<Tenant/Landlord> 
        $tenants = Tenant::whereNotNull('sub')->get();
        $landlords = Landlord::whereNotNull('user_id')->get();
        $agents = Agent::getAgentsOnAgency($user->agent->agency_id, $user->sub);
        $all = $tenants->merge($landlords);
        $all = $all->merge($agents);
        return view('messages.dm-all', compact('all'));
    }
}
