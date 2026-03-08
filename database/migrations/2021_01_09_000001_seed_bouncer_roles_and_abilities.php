<?php

use Illuminate\Database\Migrations\Migration;
use Silber\Bouncer\Database\Models;

return new class extends Migration
{
    public function up(): void
    {
        // Admin
        \Bouncer::allow('admin')->everything();

        // Agent abilities
        $agentAbilities = [
            'indexProperty', 'createProperty', 'editProperty', 'storeProperty',
            'viewProperty', 'deleteProperty', 'updateProperty',
            'indexLandlord', 'createLandlord', 'storeLandlord', 'editLandlord',
            'viewLandlord', 'deleteLandlord', 'updateLandlord',
            'indexTenant', 'createTenant', 'storeTenant', 'editTenant',
            'viewTenant', 'deleteTenant', 'updateTenant',
            'indexIssue', 'createIssue', 'editIssue', 'viewIssue',
            'storeIssue', 'deleteIssue', 'updateIssue',
            'viewStream',
        ];
        foreach ($agentAbilities as $ability) {
            \Bouncer::allow('agent')->to($ability);
        }

        // Landlord abilities
        $landlordAbilities = [
            'indexProperty', 'createProperty', 'editProperty', 'viewProperty',
            'storeProperty', 'deleteProperty', 'updateProperty',
            'indexTenant', 'createTenant', 'editTenant', 'viewTenant',
            'storeTenant', 'deleteTenant',
            'indexIssue', 'createIssue', 'editIssue', 'viewIssue',
            'storeIssue', 'deleteIssue', 'updateIssue',
            'viewStream',
        ];
        foreach ($landlordAbilities as $ability) {
            \Bouncer::allow('landlord')->to($ability);
        }

        // Contractor abilities
        $contractorAbilities = [
            'indexIssue', 'createIssue', 'editIssue', 'viewIssue',
            'storeIssue', 'deleteIssue', 'updateIssue',
            'viewStream',
        ];
        foreach ($contractorAbilities as $ability) {
            \Bouncer::allow('contractor')->to($ability);
        }

        // Tenant abilities
        $tenantAbilities = [
            'indexProperty', 'createProperty', 'storeProperty', 'viewProperty',
            'editProperty', 'updateProperty', 'deleteProperty',
            'indexIssue', 'createIssue', 'editIssue', 'viewIssue',
            'deleteIssue', 'storeIssue',
            'viewStream',
        ];
        foreach ($tenantAbilities as $ability) {
            \Bouncer::allow('tenant')->to($ability);
        }
    }

    public function down(): void
    {
        foreach (['admin', 'agent', 'landlord', 'tenant', 'contractor'] as $role) {
            \Bouncer::disallow($role)->everything();
        }
    }
};
