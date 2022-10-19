<?php

namespace App\Library;

use OneSignal;
use Illuminate\Support\Facades\URL;
use App\Properties;
use App\User;

class OneSignalHelper{
    public function sendOneSignalNotificationForProperty($streamId, $property, $message){
        if (app()->environment('production')) {
            if (!empty($message)){
                $subscribers = $this->getSubscribersForOneSignalNotification($property);    
                foreach ($subscribers as $subscriber) {
                    OneSignal::sendNotificationToExternalUser(
                        $message,
                        $subscriber,
                        $url = url("/stream/".$streamId),
                        $data = null,
                        $buttons = null,
                        $schedule = null
                    );
                }
            }
        }
    }
    private function getSubscribersForOneSignalNotification($property){
        $subscribers= array();
        if ($property->landlord) {
            array_push($subscribers, $property->landlord->sub);
        }
        if ($property->agent) {
            array_push($subscribers, $property->agent->user_id);
        }
        foreach ($property->tenants as $tenant) {
            if ($tenant->sub!=null) {
                array_push($subscribers, $tenant->sub);
            }
        }
        $subscribers = array_diff($subscribers, array(User::current()->sub));
        return $subscribers;
    }
}
