<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::availableRoles()->each(function ($roleConfig) {

            $role = Role::firstOrCreate($roleConfig['data']);

            if (count($roleConfig['permissions']) === 1 && $roleConfig['permissions'][0] === '*') {
                $this->assignPermissionToRoleByCollection(Permission::get()->pluck('name'), $role);
            } else {
                if (!empty($roleConfig['permissions'])) {
                    $this->assignPermissionToRoleByCollection(collect($roleConfig['permissions']), $role);
                }
            }
        });
    }

    /**
     * @param Collection $collection
     * @param Role $role
     */
    private function assignPermissionToRoleByCollection(Collection $collection, Role $role): void
    {
        $collection->each(function ($permission) use ($role) {
            $role->givePermissionTo($permission);
        });
    }

}
