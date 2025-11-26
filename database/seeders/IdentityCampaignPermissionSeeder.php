<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class IdentityCampaignPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure roles exist
        $employee = Role::firstOrCreate(['name' => 'employee', 'guard_name' => 'web']);
        $csrAdmin = Role::firstOrCreate(['name' => 'csr_admin', 'guard_name' => 'web']);
        $systemAdmin = Role::firstOrCreate(['name' => 'system_admin', 'guard_name' => 'web']);

        // Define campaign permissions (names only)
        $campaignPermissions = [
            'campaign.view',       // view a specific campaign
            'campaign.view_any',   // list/search campaigns
            'campaign.create',     // create a campaign
            'campaign.update_own', // edit campaigns created by the user
            'campaign.update_any', // edit any campaign
            'campaign.delete_own', // delete campaigns created by the user
            'campaign.delete_any', // delete any campaign
            'campaign.moderate',   // approve/close/moderate campaigns
            'campaign.feature',    // mark campaigns as featured
        ];

        // Create all permissions (idempotent)
        foreach ($campaignPermissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Assign permissions to roles
        $employeePerms = [
            'campaign.view',
            'campaign.view_any',
            'campaign.create',
            'campaign.update_own',
            'campaign.delete_own',
        ];

        $csrAdminPerms = array_merge($employeePerms, [
            'campaign.update_any',
            'campaign.delete_any',
            'campaign.moderate',
            'campaign.feature',
        ]);

        $systemAdminPerms = $campaignPermissions; // all campaign permissions

        // Use givePermissionTo for idempotency without removing other custom assignments
        $employee->givePermissionTo($employeePerms);
        $csrAdmin->givePermissionTo($csrAdminPerms);
        $systemAdmin->givePermissionTo($systemAdminPerms);
    }
}
