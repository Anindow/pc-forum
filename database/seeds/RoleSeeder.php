<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'Admin', 'slug' => 'admin', 'created_by' => 1, 'description' => 'Admin example Description']);
        Role::create(['name' => 'Manager', 'slug' => 'manager', 'created_by' => 1, 'description' => 'Manager example Description']);
        Role::create(['name' => 'Editor', 'slug' => 'editor', 'created_by' => 1, 'description' => 'Editor example Description']);
    }
}
