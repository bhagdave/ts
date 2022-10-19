<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Schema;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Mail;
use App\Mail\TenantRemovedFromProperty;

class RemoveTenantFromProperty implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $tenantId;
    private $propertyId;
    private $streamId;

    public function __construct($tenantId, $propertyId, $streamId)
    {
        $this->tenantId = $tenantId;
        $this->propertyId = $propertyId;
        $this->streamId = $streamId;
    }

    public function handle()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('tenants')->where('id', '=', $this->tenantId)
            ->update(['property_id' => null]);
        Schema::enableForeignKeyConstraints();
        DB::table('properties_tenant')->insert(
            [
                'tenant_id' => $this->tenantId,
                'properties_id' => $this->propertyId,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $userId = DB::table('tenants')->join('users', 'users.sub', 'tenants.sub')
            ->where('tenants.id', '=', $this->tenantId)
            ->value('users.id');
        if (isset($userId) && isset($this->streamId)){
            DB::table('stream_user')->where('stream_id', '=', $this->streamId)
                ->where('user_id', '=', $userId)
                ->delete();
        }
//        $this->sendEmail();
    }

    private function sendEmail(){
        $user = DB::table('tenants')->join('users', 'users.sub', 'tenants.sub')
            ->where('tenants.id', '=', $this->tenantId)
            ->get();
        $address = DB::table('properties')->where('id', '=', $this->propertyId)
            ->select(DB::raw("concat(inputAddress, ' ', inputAddress2, ' ', inputPostCode) as address"))
            ->value('address');
        if (isset($user) && isset($address)){
            $mailData = [
                'name' => $user->firstName ?? '',
                'email' => $user->email,
                'address' => address
            ];
            Mail:to($user->email)->queue(new TenantRemovedFromProperty($mailData));
        }
    }
}
