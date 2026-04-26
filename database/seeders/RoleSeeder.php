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
        $role_admin = Role::firstOrCreate(["name" => "admin"]);
        $role_editor = Role::firstOrCreate(["name" => "editor"]);

        $premission_create_role = Permission::firstOrCreate(["name" => "createRoles"]);
        $premission_read_role = Permission::firstOrCreate(["name" => "readRoles"]);
        $premission_update_role = Permission::firstOrCreate(["name" => "updateRoles"]);
        $premission_delete_role = Permission::firstOrCreate(["name" => "deleteRoles"]);

        $premission_create_tshirt = Permission::firstOrCreate(["name" => "createtshirts"]);
        $premission_read_tshirt = Permission::firstOrCreate(["name" => "readtshirts"]);
        $premission_update_tshirt = Permission::firstOrCreate(["name" => "updatetshirts"]);
        $premission_delete_tshirt = Permission::firstOrCreate(["name" => "deletetshirts"]);

        $premission_create_jogger = Permission::firstOrCreate(["name" => "createjoggers"]);
        $premission_read_jogger = Permission::firstOrCreate(["name" => "readjoggers"]);
        $premission_update_jogger = Permission::firstOrCreate(["name" => "updatejoggers"]);
        $premission_delete_jogger = Permission::firstOrCreate(["name" => "deletejoggers"]);

        $permission_create_event = Permission::firstOrCreate(["name" => "createEvents"]);
        $permission_read_event = Permission::firstOrCreate(["name" => "readEvents"]);
        $permission_update_event = Permission::firstOrCreate(["name" => "updateEvents"]);
        $permission_delete_event = Permission::firstOrCreate(["name" => "deleteEvents"]);

        $permissions_admin = [
            $premission_create_role,
            $premission_read_role,
            $premission_update_role,
            $premission_delete_role,
            $premission_create_tshirt,
            $premission_read_tshirt,
            $premission_update_tshirt,
            $premission_delete_tshirt,
            $premission_create_jogger,
            $premission_read_jogger,
            $premission_update_jogger,
            $premission_delete_jogger,
            $permission_create_event,
            $permission_read_event,
            $permission_update_event,
            $permission_delete_event
        ];

        $permissions_editor = [
            $premission_create_tshirt,
            $premission_read_tshirt,
            $premission_update_tshirt,
            $premission_delete_tshirt,
            $premission_create_jogger,
            $premission_read_jogger,
            $premission_update_jogger,
            $premission_delete_jogger,
            $permission_create_event,
            $permission_read_event,
            $permission_update_event,
            $permission_delete_event
        ];

        $role_admin->syncPermissions($permissions_admin);
        $role_editor->syncPermissions($permissions_editor);
    }
}
