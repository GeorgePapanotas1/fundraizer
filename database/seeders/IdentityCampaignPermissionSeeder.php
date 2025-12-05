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
        $employee = Role::firstOrCreate(['name' => 'employee', 'guard_name' => 'api']);
        $csrAdmin = Role::firstOrCreate(['name' => 'csr_admin', 'guard_name' => 'api']);
        $systemAdmin = Role::firstOrCreate(['name' => 'system_admin', 'guard_name' => 'api']);

        // Define campaign permissions (names only)
        $campaignPermissions = [
            'campaign.view',
            'campaign.view_any',
            'campaign.create',
            'campaign.update_own',
            'campaign.update_any',
            'campaign.delete_own',
            'campaign.delete_any',
            'campaign.moderate',
            'campaign.feature',
        ];

        // Platform-wide permissions
        $platformPermissions = [
            'platform.access_admin',
        ];

        // Create all permissions (idempotent)
        foreach (array_merge($campaignPermissions, $platformPermissions) as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'api']);
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
            'platform.access_admin',
        ]);

        $systemAdminPerms = array_merge($campaignPermissions, $platformPermissions);

        // Use givePermissionTo for idempotency without removing other custom assignments
        $employee->givePermissionTo($employeePerms);
        $csrAdmin->givePermissionTo($csrAdminPerms);
        $systemAdmin->givePermissionTo($systemAdminPerms);
    }
}
