<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure roles exist
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $client = Role::firstOrCreate(['name' => 'client']);

        // Give super_admin all permissions
        $all = Permission::all();
        if ($all->isNotEmpty()) {
            $superAdmin->syncPermissions($all);
        }

        // Client: limit to Achats (purchase) & Trade (trade_operation) resource permissions
        $allowedResources = ['purchase', 'trade_operation'];
        $prefixes = [
            'view', 'view_any', 'create', 'update', 'delete', 'delete_any', 'restore', 'restore_any', 'replicate', 'reorder', 'force_delete', 'force_delete_any',
        ];

        $clientPerms = Permission::query()
            ->where(function ($q) use ($allowedResources, $prefixes) {
                foreach ($allowedResources as $res) {
                    foreach ($prefixes as $p) {
                        $q->orWhere('name', $p . '_' . $res);
                    }
                }
            })
            ->pluck('name')
            ->all();

        if (! empty($clientPerms)) {
            $client->syncPermissions($clientPerms);
        }
    }
}
