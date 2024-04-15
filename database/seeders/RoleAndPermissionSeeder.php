<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        if (config('app.debug')) {
            Schema::disableForeignKeyConstraints();
            DB::table('roles')->truncate();
            DB::table('role_has_permissions')->truncate();
            DB::table('permissions')->truncate();
            DB::table('model_has_permissions')->truncate();
            Schema::enableForeignKeyConstraints();
        }
        $permissionsByRole = [
            'admin' => [
                [
                    'name' => 'view-admin-dashboard',
                ],
            ],
            'agent' => [
                [
                    'name' => 'view-agent-dashboard',
                ],
            ],
        ];

        $permissionIdsByRoles = [];

        foreach ($permissionsByRole as $role => $permissions) {
            $permissionIdsByRoles[] = [
                "role" => str($role)->lower(),
                "guard" => 'web',
                "permissions" => collect($permissions)
                ->map(function ($permission) {
                    $permission['name'] = str($permission['name'])->lower();
                    return (Permission::updateOrCreate($permission, $permission))->id;
                })
                ->toArray(),
            ];
        }

        foreach ($permissionIdsByRoles as $permissionIdsByRole) {
            $role = Role::findOrCreate($permissionIdsByRole['role'], $permissionIdsByRole['guard']);

            DB::table('role_has_permissions')
                ->insert(
                    collect($permissionIdsByRole['permissions'])->map(function ($id) use ($role) {
                        return [
                            'role_id' => $role->id,
                            'permission_id' => $id
                        ];
                    })->toArray()
                );
        }
    }
}
