<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;
use App\Message;
use App\Properties;

class ActivitiesController extends Controller
{
    public function getActivities(){
        $user = Auth::user();
        $activities = $this->getUnreadMessages($user);
        if ($user->userType != 'Contractor'){
            $latestStreamMessages = $this->getStreamMessages();
            $activities = $activities->concat($latestStreamMessages);
        }
        $recentIssues = $this->getRecentIssues($user);
        $activities = $activities->concat($recentIssues);
        $activities = $activities->sortByDesc('updated_at');
        $activities = $activities->take(40);
        return $activities->values()->toJson();
        return 'Err';
    }

    private function getUnreadMessages($user){
        $messages = Message::where('recipient_sub', '=', $user->sub)
            ->where('read', '=', 0)
            ->join('users', 'creator_sub', '=', 'users.sub')
            ->limit(20)
            ->select("message as description", "message.updated_at");
        $messages->addSelect(DB::Raw("'Direct message' as type"));
        $messages->addSelect(DB::Raw("concat('directmessage/' , creator_type, '/', creator_type_id) as link"));
        $messages->addSelect(DB::Raw("concat(firstName , ' ', lastName) as name"));
        return $messages->get();
    }

    private function getStreamMessages(){
        $messages = Properties::select('activity_log.description', 'propertyName as name', 'activity_log.updated_at')
            ->join('stream', 'stream_id', '=', 'stream.id')
            ->join('activity_log', 'log_name', '=', 'stream.id')
            ->whereRaw('activity_log.created_at >= DATE(NOW()) - INTERVAL 7 DAY')  
            ->orderBy('activity_log.updated_at', 'desc')
            ->limit(20);
        $messages->addSelect(DB::Raw("'Stream message' as type"));
        $messages->addSelect(DB::Raw("concat('stream/' , stream.id) as link"));
        return $messages->get();
    }

    private function getRecentIssues($user){
        if ($user->userType == 'Contractor'){
            $issues = Properties::select('description', 'propertyName as name')
                ->whereRaw('issues.created_at >= DATE(NOW()) - INTERVAL 7 DAY')  
                ->orderBy('issues.updated_at','desc')
                ->limit(20);
        } else {
            $issues = Properties::select('description', 'propertyName as name')
                ->whereRaw('issues.created_at >= DATE(NOW()) - INTERVAL 7 DAY')  
                ->join('issues', 'property_id', '=', 'properties.id')
                ->orderBy('issues.updated_at','desc')
                ->limit(10);
        }
        $issues->addSelect(DB::Raw("concat('issue/', issues.id) as link"));
        $issues->addSelect(DB::Raw("'Issue' as type"));
        return $issues->get();
    }
    public function notifications()
    {
        $user = Auth::user();
        // Please note that the properties returned are restricted by a global scope define in the trait - MultiTenatedProperty
        $propertiesQuery = Properties::all();
        if (count($propertiesQuery) == 0) {
            return redirect('/welcome');
        }

        if ($user->userType == "Undefined" || $user->userType == "undefined" || $user->userType == "") {
            return redirect('/welcome');
        }
        $propertyModel = new Properties();
        foreach ($propertiesQuery as $property) {
            $property->streamId = $propertyModel->propertyIdtoStreamId($property->id);
        }
        $streams = $propertiesQuery->pluck('streamId')->toArray();

        $activity = Activity::inLog($streams)->limit(250)->orderBy('created_at', 'desc')->get();
       

        return view('notifications', compact('activity'));
    }
    public function notificationsData()
    {
        // Please note that the properties returned are restricted by a global scope define in the trait - MultiTenatedProperty
        $properties = Properties::all();

        foreach ($properties as $key => &$property) {
            $stream = $property->stream;
            if (isset($stream)) {
                $property->streamid = $stream->id;
                $property->propertyid = $property->id;
            } else {
                unset($properties[$key]);
            }
        }

        $propertiesJson = $properties->toJson();
        return($propertiesJson);
     }
}
