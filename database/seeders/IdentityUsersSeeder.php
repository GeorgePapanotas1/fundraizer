<?php

namespace Database\Seeders;

use Fundraiser\Identity\Adapters\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeds three canonical users used across environments.
 * - Emails:
 *   - admin@fundraizer.gr      → role: system_admin
 *   - csr_admin@fundraizer.gr  → role: csr_admin
 *   - employee@fundraizer.gr   → role: employee
 * - Password: password1234 (only set on first creation to keep seeding idempotent)
 *
 * Notes:
 * - This seeder is idempotent: it uses firstOrCreate and will not overwrite
 *   existing passwords to avoid non-deterministic changes across runs.
 * - Example usage (after seeding):
 *   $user = User::whereEmail('admin@fundraizer.gr')->first();
 *   $user?->hasRole('system_admin');
 *   $user?->can('campaign.update_any');
 */
class IdentityUsersSeeder extends Seeder
{
    public function run(): void
    {
        $definitions = [
            ['name' => 'System Admin', 'email' => 'admin@fundraizer.gr', 'role' => 'system_admin'],
            ['name' => 'CSR Admin', 'email' => 'csr_admin@fundraizer.gr', 'role' => 'csr_admin'],
            ['name' => 'Employee', 'email' => 'employee@fundraizer.gr', 'role' => 'employee'],
        ];

        foreach ($definitions as $def) {
            $user = User::firstOrCreate(
                ['email' => $def['email']],
                [
                    'name' => $def['name'],
                    // Set password only on initial creation to keep seed idempotent
                    'password' => Hash::make('password1234'),
                ]
            );

            // Ensure the intended display name is set (without touching the password on re-seed)
            if ($user->wasRecentlyCreated && $user->name !== $def['name']) {
                $user->name = $def['name'];
                $user->save();
            }

            // Assign role if not yet assigned (idempotent)
            if (! $user->hasRole($def['role'])) {
                $user->assignRole($def['role']);
            }
        }
    }
}
