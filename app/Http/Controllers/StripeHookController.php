<?php

namespace App\Http\Controllers;

use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Jobs\SubscriptionDeleted;

class StripeHookController extends CashierController
{
    public function handleCustomerSubscriptionDeleted($payload){
        Log::info('HandleCustomerSubscriptionDeleted');
        $subscription = $payload['data']['object'];
        SubscriptionDeleted::dispatch($subscription);
        parent::handleCustomerSubscriptionDeleted($payload);
    }
}
