<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Mail;
use App\Agency;
use Illuminate\Support\Facades\DB;
use App\Mail\ActiveUsers;

class DailyUser extends Command
{
    protected $signature = 'email:activeusers';

    protected $description = 'Caluclate number of active users for yesterday';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info("Getting active users for yesterday.");

        $active = $this->getActiveUsers();
        $invitations = $this->getInvitations();
        $streams = $this->getStreams();
        $usersCreated = $this->getUsersCreated();
        $usersRegistered = $this->getUsersRegistered();
        $messages = $this->getMessages();
        $tenants = $this->getTenants();
        $agents = $this->getAgents();
        $landlords = $this->getLandlords();
        $properties = $this->getProperties();
        $issues = $this->getIssues();
        $documents = $this->getDocuments();
        $retentionRates = $this->getRetentionRates();
        $userSplit = $this->getUserSplit();
        $trialEnding = $this->getTrialEnding();

        $emailData = [
            'active' => $active,
            'invitations' => $invitations,
            'streams' => $streams,
            'messages' => $messages,
            'tenants' => $tenants[0]->number,
            'agents' => $agents[0]->agents_created,
            'usersCreated' => $usersCreated,
            'landlords' => $landlords[0]->landlords,
            'properties' => $properties[0]->properties,
            'documents' => $documents[0]->documents,
            'issues' => $issues[0]->issues,
            'usersRegistered' => $usersRegistered,
            'retentionRates' => $retentionRates,
            'userSplit' => $userSplit,
            'agencies' => $trialEnding,
        ];

        Mail::to(['dave@tenancystream.com', 'phil@tenancystream.com', 'natalie@tenancystream.com', 'jenny@tenancystream.com'])->queue(New ActiveUsers($emailData));
        $this->info("Active Users" . $active);
    }

    private function getActiveUsers(){
        return DB::table('users')
            ->select(DB::raw('count(*) as active'))
            ->whereBetween('last_activity', [Carbon::yesterday()->startOfDay(), Carbon::yesterday()->endOfDay()])
            ->whereNotIn('id', [1,3,4,39, 41, 49,51, 68, 83, 87, 88, 118, 120, 138, 159, 161, 163, 173, 174, 183, 
                                184, 185,186,187,196, 198, 199, 200,201, 207, 212, 215, 217, 218, 220, 228, 240, 
                                241, 242, 243, 245, 246, 248, 249, 250, 251, 257 ,258, 311, 316, 317, 322, 355,
                                356, 367, 372, 379, 396, 403, 404, 406, 407, 438, 508, 509, 768, 802, 1007, 1008, 1009])
            ->value('active');
    }

    private function getInvitations(){
        return  DB::table('user_invitations')
            ->select(DB::raw("count(*) as invitations"))
            ->whereBetween('created_at', [Carbon::yesterday()->startOfDay(), Carbon::yesterday()->endOfDay()])
            ->whereNotIn('user_id', [1,3,4,39, 41, 49,51, 68, 83, 87, 88, 118, 120, 138, 159, 161, 163, 173, 174, 183, 
                                    184, 185,186,187,196, 198, 199, 200,201, 207, 212, 215, 217, 218, 220, 228, 240, 
                                    241, 242, 243, 245, 246, 248, 249, 250, 251, 257 ,258, 311, 316, 317, 322, 355,
                                    356, 367, 372, 379, 396, 403, 404, 406, 407, 438, 508, 509, 768, 802, 1007, 1008, 1009])
            ->value('invitations');
    }
    private function getStreams(){
        return  DB::table('activity_log')
            ->select(DB::raw('count(*) as streams'))
            ->whereBetween('created_at', [Carbon::yesterday()->startOfDay(), Carbon::yesterday()->endOfDay()])
            ->whereNotIn('causer_id', [1,3,4,39, 41, 49,51, 68, 83, 87, 88, 118, 120, 138, 159, 161, 163, 173, 174, 183, 
                                        184, 185,186,187,196, 198, 199, 200,201, 207, 212, 215, 217, 218, 220, 228, 240, 
                                        241, 242, 243, 245, 246, 248, 249, 250, 251, 257 ,258, 311, 316, 317, 322, 355,
                                        356, 367, 372, 379, 396, 403, 404, 406, 407, 438, 508, 509, 768, 802, 1007, 1008, 1009])
            ->value('streams');
    }
    private function getUsersCreated(){
        return  DB::table('users')
            ->select(DB::raw("count(*) as usersCreated"))
            ->whereBetween('created_at', [Carbon::yesterday()->startOfDay(), Carbon::yesterday()->endOfDay()])
            ->whereNotIn('id', [1,3,4,39, 41, 49,51, 68, 83, 87, 88, 118, 120, 138, 159, 161, 163, 173, 174, 183, 
                                184, 185,186,187,196, 198, 199, 200,201, 207, 212, 215, 217, 218, 220, 228, 240, 
                                241, 242, 243, 245, 246, 248, 249, 250, 251, 257 ,258, 311, 316, 317, 322, 355,
                                356, 367, 372, 379, 396, 403, 404, 406, 407, 438, 508, 509, 768, 802, 1007, 1008, 1009])
            ->value('usersCreated');
    }
    private function getUsersRegistered(){
        return  DB::table('users')
            ->select(DB::raw("count(*) as usersCreated, userType as type"))
            ->whereBetween('created_at', [Carbon::yesterday()->startOfDay(), Carbon::yesterday()->endOfDay()])
            ->where('registered', '=', 1)
            ->whereNotIn('id', [1,3,4,39, 41, 49,51, 68, 83, 87, 88, 118, 120, 138, 159, 161, 163, 173, 174, 183, 
                                184, 185,186,187,196, 198, 199, 200,201, 207, 212, 215, 217, 218, 220, 228, 240, 
                                241, 242, 243, 245, 246, 248, 249, 250, 251, 257 ,258, 311, 316, 317, 322, 355,
                                356, 367, 372, 379, 396, 403, 404, 406, 407, 438, 508, 509, 768, 802, 1007, 1008, 1009])
            ->groupBy('userType')
            ->get();
    }
    private function getMessages(){
        return  DB::table('message')
            ->select(DB::raw("count(*) as messages"))
            ->join('users', 'creator_sub', '=', 'users.sub')
            ->whereBetween('message.created_at', [Carbon::yesterday()->startOfDay(), Carbon::yesterday()->endOfDay()])
            ->whereNotIn('users.id', [1,3,4,39, 41, 49,51, 68, 83, 87, 88, 118, 120, 138, 159, 161, 163, 173, 174, 183, 
                                184, 185,186,187,196, 198, 199, 200,201, 207, 212, 215, 217, 218, 220, 228, 240, 
                                241, 242, 243, 245, 246, 248, 249, 250, 251, 257 ,258, 311, 316, 317, 322, 355,
                                356, 367, 372, 379, 396, 403, 404, 406, 407, 438, 508, 509, 768, 802, 1007, 1008, 1009])
            ->value('messages');
    }
    private function getTenants(){
        return  DB::select( DB::raw("
            select count(*) as number from 
            (select distinct tenants.id,date(tenants.created_at) as created from tenants 
            join properties on tenants.property_id = properties.id
            join agency on properties.agent_id = agency.id
            join agents on agents.agency_id = agency.id
            join users on agents.user_id = users.sub 
            where users.id not in (1,3,4,39, 41, 51, 68, 83, 87, 88, 118, 120, 138, 159, 161, 163, 173, 174, 183, 184, 185,186,187,196, 198, 199, 200,201, 207, 212, 215, 217, 218, 220, 228, 240, 241, 242, 243, 245, 246, 248, 249, 250, 251, 257 ,258, 311, 316, 317, 322, 355, 356, 367,372, 379, 396, 403, 404, 406, 407, 438, 508, 509, 768, 802, 1007, 1008, 1009)
             and tenants.created_at between '" . Carbon::yesterday()->startOfDay()  . "' and '" .  Carbon::yesterday()->endOfDay() ."') x "
        ));
    }
    private function getAgents(){
        return  DB::select(DB::raw("
            select count(*) as agents_created from
            (select distinct agents.id,date(agents.created_at) as created from agents
            join users on agents.user_id = users.sub
            where agents.created_at between '" . Carbon::yesterday()->startOfDay()  . "' and '" .  Carbon::yesterday()->endOfDay() ."'
            and users.id not in (1,3,4,39, 41, 51, 68, 83, 87, 88, 118, 120, 138, 159, 161, 163, 173, 174, 183, 184, 185,186,187,196, 198, 199, 200,201, 207, 212, 215, 217, 218, 220, 228, 240, 241, 242, 243, 245, 246, 248, 249, 250, 251, 257 ,258, 311, 316, 317, 322, 355, 356, 367,372, 379, 396, 403, 404, 406, 407, 438, 508, 509, 768, 802, 1007, 1008, 1009)
            ) x
        "));
    }
    private function getLandlords(){
        return  DB::select(DB::raw("
            select count(*) as landlords from
            ( select distinct landlords.id,date(landlords.created_at) as created from landlords 
            join agency on agency.id = agent_id
            join agents on agents.agency_id = agent_id
            join users on agents.user_id = users.sub 
            where landlords.created_at between '" . Carbon::yesterday()->startOfDay()  . "' and '" .  Carbon::yesterday()->endOfDay() ."' 
            and users.id not in (1,3,4,39, 41, 51, 68, 83, 87, 88, 118, 120, 138, 159, 161, 163, 173, 174, 183, 184, 185,186,187,196, 198, 199, 200,201, 207, 212, 215, 217, 218, 220, 228, 240, 241, 242, 243, 245, 246, 248, 249, 250, 251, 257 ,258, 311, 316, 317, 322, 355, 356, 367,372, 379, 396, 403, 404, 406, 407, 438, 508, 509, 768, 802, 1007, 1008, 1009)
            ) x
        "));
    }
    private function getProperties (){
        return  DB::select(DB::raw("
            select count(*) as properties from
            (select distinct properties.id,date(properties.created_at) as created from properties
            join agency on properties.agent_id = agency.id
            join agents on agents.agency_id = agency.id
            join users on agents.user_id = users.sub
            where properties.created_at between '" . Carbon::yesterday()->startOfDay()  . "' and '" .  Carbon::yesterday()->endOfDay() ."' 
            and users.id not in (1,3,4,39, 41, 51, 68, 83, 87, 88, 118, 120, 138, 159, 161, 163, 173, 174, 183, 184, 185,186,187,196, 198, 199, 200,201, 207, 212, 215, 217, 218, 220, 228, 240, 241, 242, 243, 245, 246, 248, 249, 250, 251, 257 ,258, 311, 316, 317, 322, 355, 356, 367,372, 379, 396, 403, 404, 406, 407, 438, 508, 509, 768, 802, 1007, 1008, 1009)
            ) x
        "));
    }
    private function getIssues(){
        return  DB::select(DB::raw("
            select count(*) as issues from
            (select distinct issues.id,date(issues.created_at) as created from issues
            join properties on issues.property_id = properties.id
            join agency on properties.agent_id = agency.id
            join agents on agents.agency_id = agency.id
            join users on agents.user_id = users.sub
            where issues.created_at between '" . Carbon::yesterday()->startOfDay()  . "' and '" .  Carbon::yesterday()->endOfDay() ."' 
            and users.id not in (1,3,4,39, 41, 51, 68, 83, 87, 88, 118, 120, 138, 159, 161, 163, 173, 174, 183, 184, 185,186,187,196, 198, 199, 200,201, 207, 212, 215, 217, 218, 220, 228, 240, 241, 242, 243, 245, 246, 248, 249, 250, 251, 257 ,258, 311, 316, 317, 322, 355, 356, 367,372, 379, 396, 403, 404, 406, 407, 438, 508, 509, 768, 802, 1007, 1008, 1009)
            ) x
        "));
    }
    private function getDocuments(){
        return  DB::select(DB::raw("
            select count(*) as documents from 
            (select document_storage.id, date(document_storage.created_at) as created from document_storage 
            join properties on properties.id = linked_to and type = 'property'
            join agency on properties.agent_id = agency.id
            join agents on agents.agency_id = agency.id
            join users on agents.user_id = users.sub 
            where document_storage.created_at between '" . Carbon::yesterday()->startOfDay()  . "' and '" .  Carbon::yesterday()->endOfDay() ."' 
            and users.id not in (1,3,4,39, 41, 51, 68, 83, 87, 88, 118, 120, 138, 159, 161, 163, 173, 174, 183, 184, 185,186,187,196, 198, 199, 200,201, 207, 212, 215, 217, 218, 220, 228, 240, 241, 242, 243, 245, 246, 248, 249, 250, 251, 257 ,258, 311, 316, 317, 322, 355, 356, 367,372, 379, 396, 403, 404, 406, 407, 438, 508, 509, 768, 802, 1007, 1008, 1009)
            union 
            select document_storage.id, date(document_storage.created_at) as created from document_storage 
            join tenants on tenants.id = linked_to and type = 'tenant'
            join properties on properties.id = tenants.property_id
            join agency on properties.agent_id = agency.id
            join agents on agents.agency_id = agency.id
            join users on agents.user_id = users.sub 
            where document_storage.created_at between '" . Carbon::yesterday()->startOfDay()  . "' and '" .  Carbon::yesterday()->endOfDay() ."' 
            and users.id not in (1,3,4,39, 41, 51, 68, 83, 87, 88, 118, 120, 138, 159, 161, 163, 173, 174, 183, 184, 185,186,187,196, 198, 199, 200,201, 207, 212, 215, 217, 218, 220, 228, 240, 241, 242, 243, 245, 246, 248, 249, 250, 251, 257 ,258, 311, 316, 317, 322, 355, 356, 367,372, 379, 396, 403, 404, 406, 407, 438, 508, 509, 768, 802, 1007, 1008, 1009)
            ) x
        "));
    }
    private function getRetentionRates(){
        DB::enableQueryLog();
        $oneDay = DB::table('users')
            ->select(DB::raw('count(*) as oneday'))
            ->where('last_activity', '>', DB::raw("'2020-05-18 11:45'"))
            ->where('last_activity', '>', DB::raw('DATE_ADD(created_at, INTERVAL 1 DAY)'))
            ->whereNotIn('id', [1,3,4,39, 41, 49,51, 68, 83, 87, 88, 118, 120, 138, 159, 161, 163, 173, 174, 183, 
                                184, 185,186,187,196, 198, 199, 200,201, 207, 212, 215, 217, 218, 220, 228, 240, 
                                241, 242, 243, 245, 246, 248, 249, 250, 251, 257 ,258, 311, 316, 317, 322, 355,
                                356, 367, 372, 379, 396, 403, 404, 406, 407, 438, 508, 509, 768, 802, 1007, 1008, 1009])
            ->value('oneday');
        $query = 
        $twoDay = DB::table('users')
            ->select(DB::raw('count(*) as twoday'))
            ->where('last_activity', '>', DB::raw("'2020-05-18 11:45'"))
            ->where('last_activity', '>', DB::raw('DATE_ADD(created_at, INTERVAL 2 DAY)'))
            ->whereNotIn('id', [1,3,4,39, 41, 49,51, 68, 83, 87, 88, 118, 120, 138, 159, 161, 163, 173, 174, 183, 
                                184, 185,186,187,196, 198, 199, 200,201, 207, 212, 215, 217, 218, 220, 228, 240, 
                                241, 242, 243, 245, 246, 248, 249, 250, 251, 257 ,258, 311, 316, 317, 322, 355,
                                356, 367, 372, 379, 396, 403, 404, 406, 407, 438, 508, 509, 768, 802, 1007, 1008, 1009])
            ->value('twoday');
        $oneWeek = DB::table('users')
            ->select(DB::raw('count(*) as oneweek'))
            ->where('last_activity', '>', DB::raw("'2020-05-18 11:45'"))
            ->where('last_activity', '>', DB::raw('DATE_ADD(created_at, INTERVAL 1 WEEK)'))
            ->whereNotIn('id', [1,3,4,39, 41, 49,51, 68, 83, 87, 88, 118, 120, 138, 159, 161, 163, 173, 174, 183, 
                                184, 185,186,187,196, 198, 199, 200,201, 207, 212, 215, 217, 218, 220, 228, 240, 
                                241, 242, 243, 245, 246, 248, 249, 250, 251, 257 ,258, 311, 316, 317, 322, 355,
                                356, 367, 372, 379, 396, 403, 404, 406, 407, 438, 508, 509, 768, 802, 1007, 1008, 1009])
            ->value('oneweek');
        $twoWeek = DB::table('users')
            ->select(DB::raw('count(*) as twoweek'))
            ->where('last_activity', '>', DB::raw("'2020-05-18 11:45'"))
            ->where('last_activity', '>', DB::raw('DATE_ADD(created_at, INTERVAL 2 WEEK)'))
            ->whereNotIn('id', [1,3,4,39, 41, 49,51, 68, 83, 87, 88, 118, 120, 138, 159, 161, 163, 173, 174, 183, 
                                184, 185,186,187,196, 198, 199, 200,201, 207, 212, 215, 217, 218, 220, 228, 240, 
                                241, 242, 243, 245, 246, 248, 249, 250, 251, 257 ,258, 311, 316, 317, 322, 355,
                                356, 367, 372, 379, 396, 403, 404, 406, 407, 438, 508, 509, 768, 802, 1007, 1008, 1009])
            ->value('twoweek');
        $oneMonth = DB::table('users')
            ->select(DB::raw('count(*) as onemonth'))
            ->where('last_activity', '>', DB::raw("'2020-05-18 11:45'"))
            ->where('last_activity', '>', DB::raw('DATE_ADD(created_at, INTERVAL 1 MONTH)'))
            ->whereNotIn('id', [1,3,4,39, 41, 49,51, 68, 83, 87, 88, 118, 120, 138, 159, 161, 163, 173, 174, 183, 
                                184, 185,186,187,196, 198, 199, 200,201, 207, 212, 215, 217, 218, 220, 228, 240, 
                                241, 242, 243, 245, 246, 248, 249, 250, 251, 257 ,258, 311, 316, 317, 322, 355,
                                356, 367, 372, 379, 396, 403, 404, 406, 407, 438, 508, 509, 768, 802, 1007, 1008, 1009])
            ->value('onemonth');
        $twoMonth = DB::table('users')
            ->select(DB::raw('count(*) as twomonth'))
            ->where('last_activity', '>', DB::raw("'2020-05-18 11:45'"))
            ->where('last_activity', '>', DB::raw('DATE_ADD(created_at, INTERVAL 2 MONTH)'))
            ->whereNotIn('id', [1,3,4,39, 41, 49,51, 68, 83, 87, 88, 118, 120, 138, 159, 161, 163, 173, 174, 183, 
                                184, 185,186,187,196, 198, 199, 200,201, 207, 212, 215, 217, 218, 220, 228, 240, 
                                241, 242, 243, 245, 246, 248, 249, 250, 251, 257 ,258, 311, 316, 317, 322, 355,
                                356, 367, 372, 379, 396, 403, 404, 406, 407, 438, 508, 509, 768, 802, 1007, 1008, 1009])
            ->value('twomonth');
        return [
            'oneday' => $oneDay,
            'twoday' => $twoDay,
            'oneweek' => $oneWeek,
            'twoweek' => $twoWeek,
            'onemonth' => $oneMonth,
            'twomonth' => $twoMonth,
        ];  
    }
    private function getUserSplit(){
        return DB::table('users')
            ->select(DB::raw('count(*) as number, userType'))
            ->whereNotIn('id', [1,3,4,39, 41, 49,51, 68, 83, 87, 88, 118, 120, 138, 159, 161, 163, 173, 174, 183, 
                                184, 185,186,187,196, 198, 199, 200,201, 207, 212, 215, 217, 218, 220, 228, 240, 
                                241, 242, 243, 245, 246, 248, 249, 250, 251, 257 ,258, 311, 316, 317, 322, 355,
                                356, 367, 372, 379, 396, 403, 404, 406, 407, 438, 508, 509, 768, 802, 1007, 1008, 1009])
            ->groupBy('userType')
            ->get();
    }
    private function getTrialEnding(){
        return Agency::whereBetween('trial_ends_at', [
            Carbon::today()->addDays(2)->endOfDay(), 
            Carbon::today()->addDays(4)])
            ->get();
    }
}
