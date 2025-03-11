<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role_admin = Role::create(["name" => "admin"]);
        $role_editor = Role::create(["name" => "editor"]);

        $premission_create_role = Permission::create(["name" => "createRoles"]);
        $premission_read_role = Permission::create(["name" => "readRoles"]);
        $premission_update_role = Permission::create(["name" => "updateRoles"]);
        $premission_delete_role = Permission::create(["name" => "deleteRoles"]);

        $premission_create_tshirt = Permission::create(["name" => "createtshirts"]);
        $premission_read_tshirt = Permission::create(["name" => "readtshirts"]);
        $premission_update_tshirt = Permission::create(["name" => "updatetshirts"]);
        $premission_delete_tshirt = Permission::create(["name" => "deletetshirts"]);

        $permissions_admin = [
            $premission_create_role,
            $premission_read_role,
            $premission_update_role,
            $premission_delete_role,
            $premission_create_tshirt,
            $premission_read_tshirt,
            $premission_update_tshirt,
            $premission_delete_tshirt
        ];

        $permissions_editor = [$premission_create_tshirt, $premission_read_tshirt, $premission_update_tshirt, $premission_delete_tshirt];

        $role_admin->syncPermissions($permissions_admin);
        $role_editor->syncPermissions($permissions_editor);
    }
}
