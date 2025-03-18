<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            "email" => "admin@skyurban.com",
            "password" => Hash::make("Admin123+"),
            "first_name" => "Admin",
            "last_name" => "Skyurban",
            "gender" => "Male"
        ]);
        $admin->assignRole('admin');

        $editor = User::create([
            "email" => "editor@skyurban.com",
            "password" => Hash::make("Editor123+"),
            "first_name" => "Editor",
            "last_name" => "Skyurban",
            "gender" => "Male"
        ]);
        $editor->assignRole('editor');
    }
}
