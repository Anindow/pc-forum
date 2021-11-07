<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //user permissions
        Permission::create(['name' => 'Access User', 'slug' => 'access-user', 'for' => 'user']);
        Permission::create(['name' => 'Create User', 'slug' => 'create-user', 'for' => 'user']);
        Permission::create(['name' => 'Update User', 'slug' => 'update-user', 'for' => 'user']);
        Permission::create(['name' => 'Show User', 'slug' => 'show-user', 'for' => 'user']);
        Permission::create(['name' => 'Delete User', 'slug' => 'delete-user', 'for' => 'user']);
        Permission::create(['name' => 'Status Change User', 'slug' => 'status-change-user', 'for' => 'user']);

        //product permissions
        Permission::create(['name' => 'Access Product', 'slug' => 'access-product', 'for' => 'product']);
        Permission::create(['name' => 'Create Product', 'slug' => 'create-product', 'for' => 'product']);
        Permission::create(['name' => 'Update Product', 'slug' => 'update-product', 'for' => 'product']);
        Permission::create(['name' => 'Show Product', 'slug' => 'show-product', 'for' => 'product']);
        Permission::create(['name' => 'Delete Product', 'slug' => 'delete-product', 'for' => 'product']);
        Permission::create(['name' => 'Status Change Product', 'slug' => 'status-change-product', 'for' => 'product']);

        //productLink permissions
        Permission::create(['name' => 'Access Product Link', 'slug' => 'access-product-link', 'for' => 'product-link']);
        Permission::create(['name' => 'Create Product Link', 'slug' => 'create-product-link', 'for' => 'product-link']);
        Permission::create(['name' => 'Update Product Link', 'slug' => 'update-product-link', 'for' => 'product-link']);
        Permission::create(['name' => 'Show Product Link', 'slug' => 'show-product-link', 'for' => 'product-link']);
        Permission::create(['name' => 'Delete Product Link', 'slug' => 'delete-product-link', 'for' => 'product-link']);
        Permission::create(['name' => 'Status Change Product Link', 'slug' => 'status-change-product-link', 'for' => 'product-link']);

        //role permissions
        Permission::create(['name' => 'Access Role', 'slug' => 'access-role', 'for' => 'role']);
        Permission::create(['name' => 'Create Role', 'slug' => 'create-role', 'for' => 'role']);
        Permission::create(['name' => 'Update Role', 'slug' => 'update-role', 'for' => 'role']);
        Permission::create(['name' => 'Show Role', 'slug' => 'show-role', 'for' => 'role']);
        Permission::create(['name' => 'Delete Role', 'slug' => 'delete-role', 'for' => 'role']);


        //other permissions
        Permission::create(['name' => 'Access Setting', 'slug' => 'access-setting', 'for' => 'other']);
        Permission::create(['name' => 'Access Category', 'slug' => 'access-category', 'for' => 'other']);
        Permission::create(['name' => 'Access Tag', 'slug' => 'access-tag', 'for' => 'other']);
        Permission::create(['name' => 'Access Brand', 'slug' => 'access-brand', 'for' => 'other']);
        Permission::create(['name' => 'Access Shop', 'slug' => 'access-shop', 'for' => 'other']);
        Permission::create(['name' => 'Access Review', 'slug' => 'access-review', 'for' => 'other']);
    }
}
