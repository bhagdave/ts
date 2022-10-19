<?php

//use Bouncer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BouncerSeeder extends Seeder
{
    public function run()
    {
         /*Setup Permissions/Abilities for Agents*/
        Bouncer::allow('admin')->everything();

        /*Setup Permissions/Abilities for Landlords */
        Bouncer::allow('agent')->to('indexProperty');
        Bouncer::allow('agent')->to('createProperty');
        Bouncer::allow('agent')->to('editProperty');
        Bouncer::allow('agent')->to('storeProperty');
        Bouncer::allow('agent')->to('viewProperty');
        Bouncer::allow('agent')->to('deleteProperty');
        Bouncer::allow('agent')->to('updateProperty');
        Bouncer::allow('agent')->to('indexLandlord');
        Bouncer::allow('agent')->to('createLandlord');
        Bouncer::allow('agent')->to('storeLandlord');
        Bouncer::allow('agent')->to('editLandlord');
        Bouncer::allow('agent')->to('viewLandlord');
        Bouncer::allow('agent')->to('deleteLandlord');
        Bouncer::allow('agent')->to('updateLandlord');
        Bouncer::allow('agent')->to('indexTenant');
        Bouncer::allow('agent')->to('createTenant');
        Bouncer::allow('agent')->to('storeTenant');
        Bouncer::allow('agent')->to('editTenant');
        Bouncer::allow('agent')->to('viewTenant');
        Bouncer::allow('agent')->to('deleteTenant');
        Bouncer::allow('agent')->to('updateTenant');
        Bouncer::allow('agent')->to('indexIssue');
        Bouncer::allow('agent')->to('createIssue');
        Bouncer::allow('agent')->to('editIssue');
        Bouncer::allow('agent')->to('viewIssue');
        Bouncer::allow('agent')->to('storeIssue');
        Bouncer::allow('agent')->to('deleteIssue');
        Bouncer::allow('agent')->to('updateIssue');
        Bouncer::allow('agent')->to('viewStream');
        /*Setup Permissions/Abilities for Tenants */
        Bouncer::allow('landlord')->to('indexProperty');
        Bouncer::allow('landlord')->to('createProperty');
        Bouncer::allow('landlord')->to('editProperty');
        Bouncer::allow('landlord')->to('viewProperty');
        Bouncer::allow('landlord')->to('storeProperty');
        Bouncer::allow('landlord')->to('deleteProperty');
        Bouncer::allow('landlord')->to('updateProperty');
        Bouncer::allow('landlord')->to('indexTenant');
        Bouncer::allow('landlord')->to('createTenant');
        Bouncer::allow('landlord')->to('editTenant');
        Bouncer::allow('landlord')->to('viewTenant');
        Bouncer::allow('landlord')->to('storeTenant');
        Bouncer::allow('landlord')->to('deleteTenant');
        Bouncer::allow('landlord')->to('deleteTenant');
        Bouncer::allow('landlord')->to('indexIssue');
        Bouncer::allow('landlord')->to('createIssue');
        Bouncer::allow('landlord')->to('editIssue');
        Bouncer::allow('landlord')->to('viewIssue');
        Bouncer::allow('landlord')->to('storeIssue');
        Bouncer::allow('landlord')->to('deleteIssue');
        Bouncer::allow('landlord')->to('updateIssue');
        Bouncer::allow('landlord')->to('viewStream');
        Bouncer::allow('tenant')->to('indexIssue');
        Bouncer::allow('tenant')->to('createIssue');
        Bouncer::allow('tenant')->to('editIssue');
        Bouncer::allow('tenant')->to('viewIssue');
        Bouncer::allow('tenant')->to('deleteIssue');
        Bouncer::allow('tenant')->to('storeIssue');
        Bouncer::allow('tenant')->to('viewStream');

        // etc.
    }
}